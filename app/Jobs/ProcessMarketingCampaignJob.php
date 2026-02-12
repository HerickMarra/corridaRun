<?php

namespace App\Jobs;

use App\Models\MarketingCampaign;
use App\Models\User;
use App\Mail\DynamicMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Enums\UserRole;

class ProcessMarketingCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct(MarketingCampaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->campaign->status === 'sent') {
            return;
        }

        $this->campaign->update(['status' => 'sending']);

        $recipients = $this->getRecipients();
        $total = $recipients->count();
        $this->campaign->update(['total_recipients' => $total]);

        $successCount = 0;
        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new DynamicMail($this->campaign->template, [
                    'nome' => $recipient->name,
                    'email' => $recipient->email,
                ]));
                $successCount++;
                $this->campaign->increment('processed_recipients');
            } catch (\Exception $e) {
                \Log::error("Erro no Job de marketing para {$recipient->email}: " . $e->getMessage());
            }
        }

        $this->campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    private function getRecipients()
    {
        $filters = $this->campaign->filters;

        if (isset($filters['target_newsletter']) && $filters['target_newsletter']) {
            return \App\Models\NewsletterSubscriber::where('status', 'active')
                ->get()
                ->map(function ($sub) {
                    return (object) [
                        'name' => 'Assinante',
                        'email' => $sub->email
                    ];
                });
        }

        $query = User::where('role', UserRole::Client);

        if (isset($filters['target_all']) && $filters['target_all']) {
            return $query->get();
        }

        if (isset($filters['event_ids']) && is_array($filters['event_ids'])) {
            $query->whereHas('orders.items.category.event', function ($q) use ($filters) {
                $q->whereIn('events.id', $filters['event_ids']);
            });
        }

        return $query->get();
    }
}
