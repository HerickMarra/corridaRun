<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'active') {
                return response()->json([
                    'message' => 'Este e-mail já está inscrito em nossa newsletter.'
                ], 422);
            }

            $subscriber->update([
                'status' => 'active',
                'subscribed_at' => now(),
            ]);

            return response()->json([
                'message' => 'Inscrição reativada com sucesso! Bem-vindo de volta.'
            ]);
        }

        NewsletterSubscriber::create([
            'email' => $request->email,
            'status' => 'active',
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Inscrição realizada com sucesso! Obrigado por nos acompanhar.'
        ]);
    }
}
