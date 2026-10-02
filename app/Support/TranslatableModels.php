<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\LegalPage;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductBenefit;
use App\Models\ProductFaq;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\SolutionBenefit;
use App\Models\SolutionFaq;
use App\Models\SolutionPortfolio;
use App\Models\TeamMember;
use App\Models\Testimonial;

/**
 * Daftar semua model yang punya terjemahan otomatis (trait AutoTranslates).
 * Dipakai halaman Admin > Terjemahan dan perintah `php artisan content:translate`.
 * Model baru yang memakai trait cukup ditambahkan ke sini.
 */
class TranslatableModels
{
    /** @return array<string, array{label: string, class: class-string}> */
    public static function all(): array
    {
        return [
            'setting' => ['label' => 'Pengaturan Situs (hero, tentang, footer, promo)', 'class' => Setting::class],
            'service' => ['label' => 'Layanan', 'class' => Service::class],
            'team' => ['label' => 'Tim Kami', 'class' => TeamMember::class],
            'portfolio' => ['label' => 'Portofolio', 'class' => Portfolio::class],
            'testimonial' => ['label' => 'Testimoni', 'class' => Testimonial::class],
            'faq' => ['label' => 'FAQ (beranda)', 'class' => Faq::class],
            'post' => ['label' => 'Artikel Blog', 'class' => Post::class],
            'legal-page' => ['label' => 'Halaman Legal (Ketentuan Layanan & Kebijakan Privasi)', 'class' => LegalPage::class],

            'solution' => ['label' => 'Halaman Solusi', 'class' => Solution::class],
            'solution-benefit' => ['label' => 'Keunggulan Solusi', 'class' => SolutionBenefit::class],
            'solution-faq' => ['label' => 'FAQ Solusi', 'class' => SolutionFaq::class],
            'solution-portfolio' => ['label' => 'Portofolio Solusi', 'class' => SolutionPortfolio::class],

            'product' => ['label' => 'Halaman Produk', 'class' => Product::class],
            'product-benefit' => ['label' => 'Keunggulan Produk', 'class' => ProductBenefit::class],
            'product-faq' => ['label' => 'FAQ Produk', 'class' => ProductFaq::class],
        ];
    }
}
