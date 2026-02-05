<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LandingPageTemplate;
use Illuminate\Support\Facades\File;

class SyncLandingPageTemplates extends Command
{
    protected $signature = 'lp:sync-templates';
    protected $description = 'Sincroniza os templates de Landing Page a partir dos arquivos JSON em resources/views/landing_pages/templates';

    public function handle()
    {
        $directory = resource_path('views/landing_pages/templates');

        if (!File::exists($directory)) {
            $this->error("Diretório não encontrado: $directory");
            return;
        }

        $files = File::files($directory);
        $count = 0;

        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                $identifier = $file->getFilenameWithoutExtension();
                $config = json_decode(File::get($file->getRealPath()), true);

                if (!$config) {
                    $this->warn("Arquivo JSON inválido: {$file->getFilename()}");
                    continue;
                }

                LandingPageTemplate::updateOrCreate(
                    ['identifier' => $identifier],
                    [
                        'name' => $config['name'] ?? ucfirst(str_replace('-', ' ', $identifier)),
                        'config_schema' => $config['config_schema'] ?? []
                    ]
                );

                $this->info("Template sincronizado: {$identifier}");
                $count++;
            }
        }

        $this->info("🚀 Pronto! {$count} templates sincronizados com sucesso.");
    }
}
