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
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);
        $recipientQuery = $this->buildRecipientQuery($request);
        $count = $recipientQuery->count();

        if ($count === 0) {
            return redirect()->back()->with('error', 'Nenhum destinatário encontrado para os filtros selecionados.');
        }

        $campaign = MarketingCampaign::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $template->content,
            'template_id' => $template->id,
            'filters' => $request->only(['event_ids', 'target_all']),
            'scheduled_at' => $request->scheduled_at,
            'status' => 'draft',
            'total_recipients' => $count,
        ]);

        if (!$request->scheduled_at) {
            \App\Jobs\ProcessMarketingCampaignJob::dispatch($campaign);
            return redirect()->route('admin.marketing.index')->with('success', "Campanha criada! O disparo começará em instantes em segundo plano.");
        }

        return redirect()->route('admin.marketing.index')->with('success', "Campanha agendada com sucesso para " . \Carbon\Carbon::parse($request->scheduled_at)->format('d/m/Y H:i') . "!");
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
