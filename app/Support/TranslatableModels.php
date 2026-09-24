<?php

namespace App\Support;

use App\Models\AppMobileBenefit;
use App\Models\AppMobileFaq;
use App\Models\AppMobilePage;
use App\Models\AppMobilePortfolio;
use App\Models\CustomSystemBenefit;
use App\Models\CustomSystemFaq;
use App\Models\CustomSystemPage;
use App\Models\CustomSystemPortfolio;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductBenefit;
use App\Models\ProductFaq;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\WebsiteBenefit;
use App\Models\WebsiteFaq;
use App\Models\WebsitePage;
use App\Models\WebsitePortfolio;

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

            'website-page' => ['label' => 'Halaman Jasa Website', 'class' => WebsitePage::class],
            'website-benefit' => ['label' => 'Keunggulan Website', 'class' => WebsiteBenefit::class],
            'website-faq' => ['label' => 'FAQ Website', 'class' => WebsiteFaq::class],
            'website-portfolio' => ['label' => 'Portofolio Website', 'class' => WebsitePortfolio::class],

            'app-mobile-page' => ['label' => 'Halaman Jasa App Mobile', 'class' => AppMobilePage::class],
            'app-mobile-benefit' => ['label' => 'Keunggulan App Mobile', 'class' => AppMobileBenefit::class],
            'app-mobile-faq' => ['label' => 'FAQ App Mobile', 'class' => AppMobileFaq::class],
            'app-mobile-portfolio' => ['label' => 'Portofolio App Mobile', 'class' => AppMobilePortfolio::class],

            'custom-system-page' => ['label' => 'Halaman Jasa Sistem Kustom', 'class' => CustomSystemPage::class],
            'custom-system-benefit' => ['label' => 'Keunggulan Sistem Kustom', 'class' => CustomSystemBenefit::class],
            'custom-system-faq' => ['label' => 'FAQ Sistem Kustom', 'class' => CustomSystemFaq::class],
            'custom-system-portfolio' => ['label' => 'Portofolio Sistem Kustom', 'class' => CustomSystemPortfolio::class],

            'product' => ['label' => 'Halaman Produk', 'class' => Product::class],
            'product-benefit' => ['label' => 'Keunggulan Produk', 'class' => ProductBenefit::class],
            'product-faq' => ['label' => 'FAQ Produk', 'class' => ProductFaq::class],
        ];
    }
}
