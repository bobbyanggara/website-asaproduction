<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Direktif Blade untuk teks database dua bahasa (ID + EN). Lihat App\Support\Bilingual.
        //   @bi($model, 'field' [, ['nl2br' => true]])   satu field (kolom "field" + "field_en")
        //   @bipair($teksId, $teksEn [, opsi])           pasangan teks bebas
        //   @bibody($model, 'field')                     isi panjang per paragraf <p>
        //   @biexcerpt($post [, 100])                    ringkasan artikel (excerpt / potongan isi)
        Blade::directive('bi', fn (string $expression) => "<?php echo \\App\\Support\\Bilingual::field({$expression}); ?>");
        Blade::directive('bipair', fn (string $expression) => "<?php echo \\App\\Support\\Bilingual::pair({$expression}); ?>");
        Blade::directive('bibody', fn (string $expression) => "<?php echo \\App\\Support\\Bilingual::body({$expression}); ?>");
        Blade::directive('biexcerpt', fn (string $expression) => "<?php echo \\App\\Support\\Bilingual::excerpt({$expression}); ?>");
    }
}
