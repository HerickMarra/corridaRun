<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AutoConfigureTemplate extends Command
{
    protected $signature = 'lp:auto-config {template?}';
    protected $description = 'Varredura automática: Detecta novos Blades, gera JSON de configuração e sincroniza o banco de dados.';

    public function handle()
    {
        $templateName = $this->argument('template');
        $directory = resource_path("views/landing_pages/templates");

        if (!File::exists($directory)) {
            $this->error("Diretório de templates não encontrado!");
            return;
        }

        if ($templateName) {
            $this->info("⚡ Processando template específico: {$templateName}");
            $this->processFile($templateName, $directory);
        } else {
            $this->info("🔍 Buscando novos templates em {$directory}...");
            $files = File::files($directory);
            $count = 0;

            foreach ($files as $file) {
                $filename = $file->getFilename();

                // Detectar apenas arquivos .blade.php que não terminam em .bak
                if (Str::endsWith($filename, '.blade.php') && !Str::endsWith($filename, '.bak.blade.php')) {
                    $name = Str::before($filename, '.blade.php');
                    $jsonPath = $directory . DIRECTORY_SEPARATOR . $name . '.json';

                    if (!File::exists($jsonPath)) {
                        $this->warn("✨ Novo modelo encontrado: {$name}");
                        $this->processFile($name, $directory);
                        $count++;
                    }
                }
            }

            if ($count === 0) {
                $this->comment("Nenhum template novo para configurar.");
            } else {
                $this->info("✅ {$count} novos templates configurados.");
            }
        }

        $this->info("🔄 Sincronizando com o banco de dados...");
        $this->call('lp:sync-templates');
    }

    private function processFile($templateName, $directory)
    {
        $bladePath = $directory . "/{$templateName}.blade.php";

        if (!File::exists($bladePath)) {
            $this->error("Arquivo Blade não encontrado: $bladePath");
            return;
        }

        $content = File::get($bladePath);
        $configSchema = [];

        // 1. Identificar seções
        $sections = [];
        if (preg_match_all('/<section[^>]*id=["\']([^"\']+)["\'][^>]*>.*?<\/section>/is', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $index => $m) {
                $html = $m[0];
                $start = $m[1];
                $end = $start + strlen($html);
                $name = Str::slug($matches[1][$index][0], '_');
                $sections[] = ['name' => $name, 'start' => $start, 'end' => $end];
            }
        } else {
            $sections[] = ['name' => 'geral', 'start' => 0, 'end' => strlen($content)];
        }

        $replacements = [];
        $fieldCounters = [];

        $getSection = function ($offset) use ($sections) {
            foreach ($sections as $s) {
                if ($offset >= $s['start'] && $offset < $s['end'])
                    return $s['name'];
            }
            return 'geral';
        };

        // --- DETECTAR CAMPOS JÁ DINÂMICOS ---
        $dynamicPattern = '/\{\{\s*(?:asset\()?\s*\$content\[[\'"](.*?)[\'"]\]\[[\'"](.*?)[\'"]\]\s*(?:\?\?\s*[\'"](.*?)[\'"]\s*)?\)?\s*\}\}/i';
        if (preg_match_all($dynamicPattern, $content, $vars, PREG_OFFSET_CAPTURE)) {
            foreach ($vars[0] as $i => $m) {
                $section = $vars[1][$i][0];
                $key = $vars[2][$i][0];
                $default = isset($vars[3][$i][0]) && $vars[3][$i][0] !== '' ? $vars[3][$i][0] : '';

                $type = 'text';
                if (strpos($key, 'image') !== false || strpos($key, 'bg_') !== false)
                    $type = 'image';
                elseif (strpos($key, 'description') !== false || strpos($key, 'subtitle') !== false)
                    $type = 'textarea';

                if (!isset($configSchema[$section]))
                    $configSchema[$section] = [];
                $exists = false;
                foreach ($configSchema[$section] as $f) {
                    if ($f['key'] === $key)
                        $exists = true;
                }
                if (!$exists) {
                    $configSchema[$section][] = [
                        'key' => $key,
                        'label' => ucfirst(str_replace('_', ' ', $key)),
                        'type' => $type,
                        'default' => $default
                    ];
                }
            }
        }

        // --- DETECTAR E CONVERTER CAMPOS ESTÁTICOS ---

        // BACKGROUND IMAGES (processar primeiro para evitar conflitos)
        if (preg_match_all('/background-image:\s*url\(["\']?([^"\']+)["\']?\)/i', $content, $bgs, PREG_OFFSET_CAPTURE)) {
            foreach ($bgs[1] as $i => $m) {
                $url = $m[0];
                if (strpos($url, '{{') !== false)
                    continue;

                $section = $getSection($m[1]);
                $fieldCounters[$section]['bg'] = ($fieldCounters[$section]['bg'] ?? 0) + 1;
                $key = "bg_image_" . $fieldCounters[$section]['bg'];

                $configSchema[$section][] = ['key' => $key, 'label' => "Background " . $fieldCounters[$section]['bg'], 'type' => 'image', 'default' => $url];
                $replacements[] = [
                    'start' => $m[1],
                    'end' => $m[1] + strlen($url),
                    'text' => "{{ asset(\$content['{$section}']['{$key}'] ?? '{$url}') }}"
                ];
            }
        }

        // IMAGENS (IMG tags)
        if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $imgs, PREG_OFFSET_CAPTURE)) {
            foreach ($imgs[1] as $i => $m) {
                $url = $m[0];
                if (strpos($url, '{{') !== false)
                    continue;

                $section = $getSection($m[1]);
                $fieldCounters[$section]['img'] = ($fieldCounters[$section]['img'] ?? 0) + 1;
                $key = "image_" . $fieldCounters[$section]['img'];

                $configSchema[$section][] = ['key' => $key, 'label' => "Imagem " . $fieldCounters[$section]['img'], 'type' => 'image', 'default' => $url];
                $replacements[] = [
                    'start' => $m[1],
                    'end' => $m[1] + strlen($url),
                    'text' => "{{ asset(\$content['{$section}']['{$key}'] ?? '{$url}') }}"
                ];
            }
        }

        // TÍTULOS (H1-H6) - Regex mais preciso
        if (preg_match_all('/<(h[1-6])(\s+[^>]*)?>(.+?)<\/\1>/is', $content, $titles, PREG_OFFSET_CAPTURE)) {
            foreach ($titles[0] as $i => $m) {
                $tag = $titles[1][$i][0];
                $attrs = $titles[2][$i][0];
                $inner = $titles[3][$i][0];

                // Ignorar se já tem variável Blade ou está vazio
                if (strpos($inner, '{{') !== false || empty(trim(strip_tags($inner))))
                    continue;

                $section = $getSection($m[1]);
                $fieldCounters[$section]['h'] = ($fieldCounters[$section]['h'] ?? 0) + 1;
                $key = ($tag === 'h1' ? 'title' : "heading_" . $fieldCounters[$section]['h']);
                $cleanText = trim(strip_tags($inner));

                $configSchema[$section][] = ['key' => $key, 'label' => "Título " . ($tag === 'h1' ? 'Principal' : $fieldCounters[$section]['h']), 'type' => 'text', 'default' => $cleanText];

                // Substituir apenas o conteúdo interno
                $replacements[] = [
                    'start' => $titles[3][$i][1],
                    'end' => $titles[3][$i][1] + strlen($inner),
                    'text' => "{{ \$content['{$section}']['{$key}'] ?? '{$cleanText}' }}"
                ];

                // Adicionar {{ $attributes ?? '' }} se não existir
                if (strpos($attrs, 'attributes') === false && strpos($attrs, '{{') === false) {
                    $insertPos = $m[1] + strlen($tag) + 1;
                    $replacements[] = [
                        'start' => $insertPos,
                        'end' => $insertPos,
                        'text' => " {{ \$attributes ?? '' }}"
                    ];
                }
            }
        }

        // PARÁGRAFOS - Excluir SVGs e usar regex preciso
        // Primeiro, vamos marcar todas as áreas de SVG para não processar
        $svgPlaceholders = [];
        $svgIndex = 0;
        $contentWithoutSvg = preg_replace_callback('/<svg[^>]*>.*?<\/svg>/is', function ($m) use (&$svgPlaceholders, &$svgIndex) {
            $placeholder = "___SVG_PLACEHOLDER_{$svgIndex}___";
            $svgPlaceholders[$placeholder] = $m[0];
            $svgIndex++;
            return $placeholder;
        }, $content);

        // Agora processar parágrafos no conteúdo sem SVGs
        // Regex que só pega <p seguido de espaço ou >
        if (preg_match_all('/<p(?:\s+[^>]*)?>(.+?)<\/p>/is', $contentWithoutSvg, $ps, PREG_OFFSET_CAPTURE)) {
            foreach ($ps[0] as $i => $m) {
                $fullMatch = $m[0];
                $inner = $ps[1][$i][0];

                // Ignorar se já tem variável, está vazio ou é muito curto
                if (strpos($inner, '{{') !== false || strlen(trim(strip_tags($inner))) < 10)
                    continue;

                // Encontrar a posição real no conteúdo original
                $realOffset = strpos($content, $fullMatch);
                if ($realOffset === false)
                    continue;

                // Extrair atributos da tag
                preg_match('/<p(\s+[^>]*)?>/i', $fullMatch, $attrMatch);
                $attrs = isset($attrMatch[1]) ? $attrMatch[1] : '';

                $section = $getSection($realOffset);
                $fieldCounters[$section]['p'] = ($fieldCounters[$section]['p'] ?? 0) + 1;
                $key = "description_" . $fieldCounters[$section]['p'];
                $cleanText = trim(strip_tags($inner));

                $configSchema[$section][] = ['key' => $key, 'label' => "Parágrafo " . $fieldCounters[$section]['p'], 'type' => 'textarea', 'default' => $cleanText];

                // Calcular offset do conteúdo interno no arquivo original
                $innerStartInMatch = strpos($fullMatch, $inner);
                $innerOffset = $realOffset + $innerStartInMatch;

                $replacements[] = [
                    'start' => $innerOffset,
                    'end' => $innerOffset + strlen($inner),
                    'text' => "{{ \$content['{$section}']['{$key}'] ?? '{$cleanText}' }}"
                ];

                if (strpos($attrs, 'attributes') === false && strpos($attrs, '{{') === false) {
                    $insertPos = $realOffset + 2;
                    $replacements[] = [
                        'start' => $insertPos,
                        'end' => $insertPos,
                        'text' => " {{ \$attributes ?? '' }}"
                    ];
                }
            }
        }

        // --- APLICAR REPLACEMENTS EM ORDEM REVERSA ---
        usort($replacements, function ($a, $b) {
            return $b['start'] <=> $a['start'];
        });
        foreach ($replacements as $r) {
            $content = substr_replace($content, $r['text'], $r['start'], $r['end'] - $r['start']);
        }

        // Salvar JSON
        $jsonPath = $directory . "/{$templateName}.json";
        $jsonContent = json_encode([
            'name' => ucfirst(str_replace('-', ' ', $templateName)),
            'config_schema' => $configSchema
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        File::put($jsonPath, $jsonContent);

        // Salvar Blade
        File::put($bladePath . '.bak', File::get($bladePath));
        File::put($bladePath, $content);

        $this->info("✅ Template '{$templateName}' configurado com sucesso!");
    }
}
