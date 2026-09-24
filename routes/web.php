<?php

use App\Http\Controllers\Admin\AppMobileBenefitController;
use App\Http\Controllers\Admin\AppMobileClientController;
use App\Http\Controllers\Admin\AppMobileFaqController;
use App\Http\Controllers\Admin\AppMobilePageController;
use App\Http\Controllers\Admin\AppMobilePortfolioController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomSystemBenefitController;
use App\Http\Controllers\Admin\CustomSystemClientController;
use App\Http\Controllers\Admin\CustomSystemFaqController;
use App\Http\Controllers\Admin\CustomSystemPageController;
use App\Http\Controllers\Admin\CustomSystemPortfolioController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductItemController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\WebsiteBenefitController;
use App\Http\Controllers\Admin\WebsiteClientController;
use App\Http\Controllers\Admin\WebsiteFaqController;
use App\Http\Controllers\Admin\WebsitePageController;
use App\Http\Controllers\Admin\WebsitePortfolioController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AppMobileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomSystemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PortfolioController as PublicPortfolioController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes (halaman utama Asa Production)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Tentang Asa Production (halaman publik, terpisah dari landing page)
|--------------------------------------------------------------------------
*/
Route::get('/tentang-asa-production', [AboutController::class, 'index'])->name('about');

/*
|--------------------------------------------------------------------------
| Solusi: Jasa Pembuatan Aplikasi Mobile (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/solusi/jasa-pembuatan-app-mobile', [AppMobileController::class, 'index'])->name('solusi.app-mobile');

/*
|--------------------------------------------------------------------------
| Solusi: Jasa Pembuatan Website (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/solusi/jasa-pembuatan-website', [WebsiteController::class, 'index'])->name('solusi.website');

/*
|--------------------------------------------------------------------------
| Solusi: Jasa Pembuatan Sistem Kustom (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/solusi/jasa-pembuatan-sistem-kustom', [CustomSystemController::class, 'index'])->name('solusi.custom-system');

/*
|--------------------------------------------------------------------------
| Produk (halaman publik, dinamis)
|--------------------------------------------------------------------------
| Satu route untuk semua produk. Produk baru yang ditambahkan lewat Admin >
| Produk langsung bisa dibuka di /produk/{slug} tanpa menambah route/kode.
| Slug lama (absen-asa, klinik-asa) tetap sama, jadi URL lama tidak putus.
*/
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('produk.show');

/*
|--------------------------------------------------------------------------
| Kontak (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Blog (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post:slug}/like', [BlogController::class, 'like'])
    ->middleware('throttle:30,1')
    ->name('blog.like');

/*
|--------------------------------------------------------------------------
| Portofolio (halaman publik)
|--------------------------------------------------------------------------
*/
Route::get('/portofolio', [PublicPortfolioController::class, 'index'])->name('portfolio.index');

/*
|--------------------------------------------------------------------------
| Chatbot (widget di halaman utama, dibatasi hanya seputar isi website)
|--------------------------------------------------------------------------
*/
Route::post('/chat', [ChatbotController::class, 'send'])
    ->middleware('throttle:20,1')
    ->name('chat.send');

Route::post('/chat/stream', [ChatbotController::class, 'stream'])
    ->middleware('throttle:20,1')
    ->name('chat.stream');

/*
|--------------------------------------------------------------------------
| Auth routes (login admin)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin dashboard routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Isi versi Inggris untuk konten lama (konten baru otomatis diterjemahkan saat disimpan).
    Route::get('translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('translations/run', [TranslationController::class, 'run'])->name('translations.run');

    Route::resource('posts', PostController::class)->except(['show']);
    Route::resource('team', TeamMemberController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('portfolio', PortfolioController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('partners', PartnerController::class)->except(['show']);
    Route::resource('certificates', CertificateController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);

    // Halaman "Jasa Pembuatan Aplikasi Mobile" (konten dinamis)
    Route::get('app-mobile', [AppMobilePageController::class, 'edit'])->name('app-mobile.edit');
    Route::put('app-mobile', [AppMobilePageController::class, 'update'])->name('app-mobile.update');
    Route::resource('app-mobile-benefits', AppMobileBenefitController::class)->except(['show']);
    Route::resource('app-mobile-clients', AppMobileClientController::class)->except(['show']);
    Route::resource('app-mobile-portfolios', AppMobilePortfolioController::class)->except(['show']);
    Route::resource('app-mobile-faqs', AppMobileFaqController::class)->except(['show']);

    // Halaman "Jasa Pembuatan Website" (konten dinamis)
    Route::get('website', [WebsitePageController::class, 'edit'])->name('website.edit');
    Route::put('website', [WebsitePageController::class, 'update'])->name('website.update');
    Route::resource('website-benefits', WebsiteBenefitController::class)->except(['show']);
    Route::resource('website-clients', WebsiteClientController::class)->except(['show']);
    Route::resource('website-portfolios', WebsitePortfolioController::class)->except(['show']);
    Route::resource('website-faqs', WebsiteFaqController::class)->except(['show']);

    // Halaman "Jasa Pembuatan Sistem Kustom" (konten dinamis)
    Route::get('custom-system', [CustomSystemPageController::class, 'edit'])->name('custom-system.edit');
    Route::put('custom-system', [CustomSystemPageController::class, 'update'])->name('custom-system.update');
    Route::resource('custom-system-benefits', CustomSystemBenefitController::class)->except(['show']);
    Route::resource('custom-system-clients', CustomSystemClientController::class)->except(['show']);
    Route::resource('custom-system-portfolios', CustomSystemPortfolioController::class)->except(['show']);
    Route::resource('custom-system-faqs', CustomSystemFaqController::class)->except(['show']);

    // Produk (dinamis): tambah produk baru -> otomatis muncul di navbar & punya
    // halaman publik /produk/{slug}. Keunggulan, Klien, dan FAQ dikelola per produk.
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::prefix('products/{product}/{type}')
        ->whereIn('type', ['benefits', 'clients', 'faqs'])
        ->name('product-items.')
        ->group(function () {
            Route::get('/', [ProductItemController::class, 'index'])->name('index');
            Route::get('create', [ProductItemController::class, 'create'])->name('create');
            Route::post('/', [ProductItemController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ProductItemController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('{id}', [ProductItemController::class, 'update'])->whereNumber('id')->name('update');
            Route::delete('{id}', [ProductItemController::class, 'destroy'])->whereNumber('id')->name('destroy');
        });

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/poll-status', [ContactMessageController::class, 'pollStatus'])->name('contact-messages.poll-status');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
});
