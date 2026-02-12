<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('marketing:process-scheduled', function () {
    $campaigns = \App\Models\MarketingCampaign::where('status', 'draft')
        ->whereNotNull('scheduled_at')
        ->where('scheduled_at', '<=', now())
        ->get();

    foreach ($campaigns as $campaign) {
        \App\Jobs\ProcessMarketingCampaignJob::dispatch($campaign);
        $this->info("Campanha '{$campaign->name}' disparada via agendamento.");
    }
})->purpose('Process scheduled marketing campaigns')->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
