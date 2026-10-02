<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LegalAppController;
use App\Http\Controllers\Admin\LegalPageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductItemController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SolutionController as AdminSolutionController;
use App\Http\Controllers\Admin\SolutionItemController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\PortfolioController as PublicPortfolioController;
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
| Solusi (halaman publik, dinamis)
|--------------------------------------------------------------------------
| Satu route untuk semua halaman solusi. Halaman baru yang ditambahkan lewat
| Admin > Semua Solusi langsung bisa dibuka di /solusi/{slug} tanpa menambah
| route/kode. Slug lama (jasa-pembuatan-website, jasa-pembuatan-app-mobile,
| jasa-pembuatan-sistem-kustom) tetap sama, jadi URL lama tidak putus.
*/
Route::get('/solusi/{solution:slug}', [SolutionController::class, 'show'])->name('solusi.show');

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
| Halaman legal (publik, isi dinamis dari Admin > Halaman Legal)
|--------------------------------------------------------------------------
*/
Route::get('/ketentuan-layanan', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/kebijakan-privasi', [LegalController::class, 'privacy'])->name('legal.privacy');
// Dokumen legal per aplikasi, mis. /kalkulator-shopee-seller/kebijakan-privasi
// (slug aplikasi yang bentrok dengan alamat situs ditolak, lihat LegalApp::RESERVED).
Route::get('/{appSlug}/ketentuan-layanan', [LegalController::class, 'appTerms'])
    ->where('appSlug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('legal.app.terms');
Route::get('/{appSlug}/kebijakan-privasi', [LegalController::class, 'appPrivacy'])
    ->where('appSlug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('legal.app.privacy');
// Alamat lama (/legal/{app}/...) dialihkan permanen ke alamat baru.
Route::get('/legal/{appSlug}/ketentuan-layanan', fn (string $appSlug) => redirect()->route('legal.app.terms', $appSlug, 301));
Route::get('/legal/{appSlug}/kebijakan-privasi', fn (string $appSlug) => redirect()->route('legal.app.privacy', $appSlug, 301));

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

    // Solusi (dinamis): tambah halaman solusi baru -> otomatis muncul di navbar,
    // footer & punya halaman publik /solusi/{slug}. Keunggulan, Klien, Portofolio,
    // dan FAQ dikelola per halaman solusi.
    Route::resource('solutions', AdminSolutionController::class)->except(['show']);
    Route::prefix('solutions/{solution}/{type}')
        ->whereIn('type', ['benefits', 'clients', 'portfolios', 'faqs'])
        ->name('solution-items.')
        ->group(function () {
            Route::get('/', [SolutionItemController::class, 'index'])->name('index');
            Route::get('create', [SolutionItemController::class, 'create'])->name('create');
            Route::post('/', [SolutionItemController::class, 'store'])->name('store');
            Route::get('{id}/edit', [SolutionItemController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('{id}', [SolutionItemController::class, 'update'])->whereNumber('id')->name('update');
            Route::delete('{id}', [SolutionItemController::class, 'destroy'])->whereNumber('id')->name('destroy');
        });

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

    // Halaman legal: pilih aplikasi dulu -> lihat dokumennya -> edit.
    // Aplikasi bisa ditambah/diubah/dihapus; tiap aplikasi punya Ketentuan Layanan & Kebijakan Privasi sendiri.
    Route::get('legal-pages', [LegalPageController::class, 'index'])->name('legal-pages.index');
    Route::get('legal-pages/general', [LegalPageController::class, 'general'])->name('legal-pages.general');

    // Halaman umum situs Asa Production (tanpa aplikasi).
    Route::get('legal-pages/{slug}/edit', [LegalPageController::class, 'edit'])
        ->whereIn('slug', \App\Models\LegalPage::SLUGS)->name('legal-pages.edit');
    Route::put('legal-pages/{slug}', [LegalPageController::class, 'update'])
        ->whereIn('slug', \App\Models\LegalPage::SLUGS)->name('legal-pages.update');

    // Aplikasi: tambah, pengaturan, hapus.
    Route::post('legal-pages/apps', [LegalAppController::class, 'store'])->name('legal-pages.apps.store');
    Route::get('legal-pages/apps/{legalApp}', [LegalPageController::class, 'app'])
        ->whereNumber('legalApp')->name('legal-pages.apps.show');
    Route::put('legal-pages/apps/{legalApp}', [LegalAppController::class, 'update'])
        ->whereNumber('legalApp')->name('legal-pages.apps.settings');
    Route::delete('legal-pages/apps/{legalApp}', [LegalAppController::class, 'destroy'])
        ->whereNumber('legalApp')->name('legal-pages.apps.destroy');

    // Dokumen milik aplikasi.
    Route::get('legal-pages/apps/{legalApp}/{slug}/edit', [LegalPageController::class, 'editApp'])
        ->whereNumber('legalApp')->whereIn('slug', \App\Models\LegalPage::SLUGS)->name('legal-pages.apps.edit');
    Route::put('legal-pages/apps/{legalApp}/{slug}', [LegalPageController::class, 'updateApp'])
        ->whereNumber('legalApp')->whereIn('slug', \App\Models\LegalPage::SLUGS)->name('legal-pages.apps.update');

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/poll-status', [ContactMessageController::class, 'pollStatus'])->name('contact-messages.poll-status');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
});
