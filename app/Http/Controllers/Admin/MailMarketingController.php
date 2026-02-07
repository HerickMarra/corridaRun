<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\MarketingCampaign;
use App\Models\OrderItem;
use App\Mail\DynamicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Enums\UserRole;

class MailMarketingController extends Controller
{
    public function index()
    {
        $campaigns = MarketingCampaign::with('template')->latest()->paginate(15);
        return view('admin.marketing.index', compact('campaigns'));
    }

    public function create()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();
        return view('admin.marketing.create', compact('events', 'templates'));
    }

    public function getRecipientCount(Request $request)
    {
        $query = $this->buildRecipientQuery($request);
        $count = $query->count();
        return response()->json(['count' => $count]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'template_id' => 'required|exists:email_templates,id',
            'subject' => 'required|string|max:255',
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);
        $recipients = $this->buildRecipientQuery($request)->get();

        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum destinatário encontrado para os filtros selecionados.');
        }

        $campaign = MarketingCampaign::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $template->content,
            'template_id' => $template->id,
            'filters' => $request->only(['event_ids', 'target_all']),
            'status' => 'sending',
            'total_recipients' => $recipients->count(),
        ]);

        // Disparo real (Simplificado para este contexto, idealmente via Queue)
        $successCount = 0;
        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new DynamicMail($template, [
                    'nome' => $recipient->name,
                    'email' => $recipient->email,
                ]));
                $successCount++;
            } catch (\Exception $e) {
                \Log::error("Erro ao enviar marketing para {$recipient->email}: " . $e->getMessage());
            }
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.marketing.index')->with('success', "Campanha enviada com sucesso para {$successCount} atletas!");
    }

    private function buildRecipientQuery(Request $request)
    {
        $query = User::where('role', UserRole::Client);

        if ($request->target_all) {
            return $query;
        }

        if ($request->event_ids && is_array($request->event_ids)) {
            $query->whereHas('orders.items.category.event', function ($q) use ($request) {
                $q->whereIn('events.id', $request->event_ids);
            });
        }

        return $query;
    }
}
