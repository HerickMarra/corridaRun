<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    protected $url;
    protected $key;

    public function __construct()
    {
        $this->url = config('services.asaas.url');
        $this->key = config('services.asaas.key');
    }

    /**
     * Create or retrieve an Asaas Customer.
     */
    public function createCustomer(User $user)
    {
        if ($user->asaas_customer_id) {
            return $user->asaas_customer_id;
        }

        // Try to find by CPF/Email first
        try {
            $response = $this->request()->get("/customers", [
                'cpfCnpj' => $user->cpf,
            ]);

            if ($response->successful() && !empty($response->json('data'))) {
                $customer = $response->json('data')[0];
                $user->update(['asaas_customer_id' => $customer['id']]);
                Log::info('Asaas Customer Found', ['customer_id' => $customer['id'], 'user_id' => $user->id]);
                return $customer['id'];
            }
        } catch (\Exception $e) {
            Log::warning('Asaas Find Customer Error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        // Create new customer
        $customerData = [
            'name' => $user->name,
            'email' => $user->email,
            'cpfCnpj' => $user->cpf,
        ];

        // Adicionar telefone apenas se existir
        if ($user->phone) {
            $customerData['mobilePhone'] = $user->phone;
        }

        Log::info('Creating Asaas Customer', ['user_id' => $user->id, 'data' => $customerData]);

        $response = $this->request()->post("/customers", $customerData);

        if ($response->successful()) {
            $id = $response->json('id');
            $user->update(['asaas_customer_id' => $id]);
            Log::info('Asaas Customer Created', ['customer_id' => $id, 'user_id' => $user->id]);
            return $id;
        }

        // Log detalhado do erro
        $errorData = [
            'user_id' => $user->id,
            'status' => $response->status(),
            'response' => $response->json(),
            'body' => $response->body(),
        ];

        Log::error('Asaas Create Customer Error', $errorData);

        // Lançar exceção com mensagem mais descritiva
        $errorMessage = 'Erro ao criar cliente no Asaas';
        if ($response->json('errors')) {
            $errors = $response->json('errors');
            $errorMessage .= ': ' . json_encode($errors);
        }

        throw new \Exception($errorMessage);
    }

    /**
     * Create a payment charge.
     * 
     * @param Order $order
     * @param string $billingType (PIX, BOLETO, CREDIT_CARD)
     * @param array|null $creditCardInfo
     */
    public function createPayment(Order $order, string $billingType, array $creditCardInfo = null)
    {
        $customer = $this->createCustomer($order->user);

        if (!$customer) {
            throw new \Exception('Erro ao criar cliente no Asaas.');
        }

        $payload = [
            'customer' => $customer,
            'billingType' => $billingType,
            'value' => $order->total_amount,
            'dueDate' => now()->addDays(2)->format('Y-m-d'),
            'description' => "Pedido #{$order->order_number} - Sisters Esportes",
            'externalReference' => $order->id,
        ];

        if ($billingType === 'CREDIT_CARD' && $creditCardInfo) {
            $payload['creditCard'] = [
                'holderName' => $creditCardInfo['holderName'],
                'number' => $creditCardInfo['number'],
                'expiryMonth' => $creditCardInfo['expiryMonth'],
                'expiryYear' => $creditCardInfo['expiryYear'],
                'ccv' => $creditCardInfo['ccv']
            ];
            $payload['creditCardHolderInfo'] = [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'cpfCnpj' => $order->user->cpf,
                'postalCode' => '00000000', // Should be fetched from user address
                'addressNumber' => '0',
                'phone' => $order->user->phone ?? '00000000000',
            ];
        }

        $response = $this->request()->post("/payments", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Asaas Create Payment Error', ['order_id' => $order->id, 'response' => $response->json()]);
        throw new \Exception('Erro ao criar pagamento no Asaas: ' . ($response->json('errors')[0]['description'] ?? 'Erro desconhecido'));
    }

    /**
     * Get Pix QR Code and Payload.
     */
    public function getPixQrCode($paymentId)
    {
        $response = $this->request()->get("/payments/{$paymentId}/pixQrCode");

        Log::info('Asaas Pix QR Code Request', [
            'payment_id' => $paymentId,
            'status' => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::warning('Asaas Pix QR Code Failed', [
            'payment_id' => $paymentId,
            'status' => $response->status(),
            'error' => $response->json()
        ]);

        return null;
    }

    protected function request()
    {
        return Http::withHeaders([
            'access-token' => $this->key,
            'Content-Type' => 'application/json',
            'User-Agent' => 'SistersEsportes',
        ])->baseUrl($this->url);
    }
}
