<?php

namespace App\Console\Commands;

use App\Services\AutoTranslateService;
use App\Support\TranslatableModels;
use Illuminate\Console\Command;

class TranslateContent extends Command
{
    protected $signature = 'content:translate
        {--model= : Batasi ke satu jenis konten, mis. post, service, faq (lihat App\Support\TranslatableModels)}
        {--force : Terjemahkan ulang SEMUA, termasuk yang sudah ada versi Inggrisnya (menimpa edit manual!)}';

    protected $description = 'Isi versi Inggris (kolom *_en) untuk konten lama yang belum diterjemahkan';

    public function handle(AutoTranslateService $translator): int
    {
        if (! $translator->isConfigured()) {
            $this->error('GROQ_API_KEY belum diset di .env — tidak ada yang bisa diterjemahkan.');

            return self::FAILURE;
        }

        $models = TranslatableModels::all();

        if ($only = $this->option('model')) {
            if (! isset($models[$only])) {
                $this->error('Model tidak dikenal. Pilihan: '.implode(', ', array_keys($models)));

                return self::FAILURE;
            }
            $models = [$only => $models[$only]];
        }

        $force = (bool) $this->option('force');
        if ($force && ! $this->confirm('--force akan menimpa terjemahan yang sudah ada, termasuk yang diedit manual. Lanjut?')) {
            return self::SUCCESS;
        }

        $totalFields = 0;
        $totalFailed = 0;

        foreach ($models as $key => $meta) {
            $class = $meta['class'];
            $query = $force ? $class::query() : $class::needsTranslation();
            $count = (clone $query)->count();

            if ($count === 0) {
                $this->line("<fg=gray>- {$meta['label']}: sudah lengkap</>");

                continue;
            }

            $this->info("- {$meta['label']}: {$count} data");

            $query->chunkById(10, function ($rows) use (&$totalFields, &$totalFailed, $force) {
                foreach ($rows as $row) {
                    $n = $row->translateNow($force);
                    $n > 0 ? $totalFields += $n : $totalFailed++;
                    $this->output->write($n > 0 ? '.' : 'x');
                }
            });
            $this->newLine();
        }

        $this->newLine();
        $this->info("Selesai. {$totalFields} field diterjemahkan".($totalFailed ? ", {$totalFailed} data gagal (cek storage/logs, lalu jalankan lagi)." : '.'));

        return $totalFailed ? self::FAILURE : self::SUCCESS;
    }
}
