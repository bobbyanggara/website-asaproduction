/*========================================================
  SITE_I18N — single source of truth for every translatable
  string on the public site (navbar, footer, home, about,
  contact, blog index/show). Loaded before i18n-lib.js is
  initialized on each page. Keep keys namespaced by section
  so pages never collide with each other.
========================================================*/
window.SITE_I18N = {
  id: {
    // ---- Navbar (shared across all pages) ----
    'nav.home': 'Beranda',
    'nav.servicesToggle': 'Layanan',
    'nav.about': 'Tentang Kami',
    'nav.servicesItem': 'Layanan',
    'nav.portfolio': 'Portofolio',
    'nav.infoToggle': 'Info',
    'nav.blog': 'Blog',
    'nav.faq': 'FAQ',
    'nav.contact': 'Kontak',
    'nav.toggleLabel': 'Buka menu',
    'nav.servicesWebsite': 'Jasa Pembuatan Website',
    'nav.servicesWebsiteDesc': 'Website profesional & responsif sesuai kebutuhan bisnis Anda',
    'nav.servicesAppMobile': 'Jasa Pembuatan App Mobile',
    'nav.servicesAppMobileDesc': 'Aplikasi Android & iOS yang disesuaikan dengan kebutuhan bisnis',
    'nav.servicesCustomSystem': 'Jasa Pembuatan Sistem Kustom',
    'nav.servicesCustomSystemDesc': 'Sistem/software kustom untuk mengotomasi proses bisnis Anda',
    'nav.whatsappCta': 'WhatsApp Kami',
    'nav.langChangeLabel': 'Ganti bahasa',
    'nav.langChooseLabel': 'Pilih bahasa',

    // ---- Chatbot widget (shared across all pages) ----
    'chatbot.windowLabel': 'Live chat',

    // ---- Home: hero ----
    'hero.ctaWhatsapp': 'Hubungi Kami',

    // ---- Home: about section ----
    'about.label': 'Tentang Kami',
    'about.cta': 'Pelajari Lebih Lanjut',

    // ---- Home: clients ----
    'clients.title': 'Klien',
    'clients.subtitle': 'Telah dipercaya oleh berbagai bisnis',

    // ---- Home: testimonials ----
    'testimonials.title': 'Testimoni',
    'testimonials.subtitle': 'Apa kata mereka yang sudah menggunakan layanan kami',

    // ---- Home: blog preview ----
    'blog.title': 'Artikel Terbaru',
    'blog.subtitle': 'Tips dan wawasan seputar dunia produksi kreatif',
    'blog.cta': 'Lihat Semua Artikel',
    'blog.readmore': 'Baca Selengkapnya →',

    // ---- Home: FAQ ----
    'faq.title': 'F.A.Q',
    'faq.subtitle': 'Pertanyaan yang sering diajukan',

    // ---- Footer (shared across all pages) ----
    'footer.navTitle': 'Navigasi',
    'footer.home': 'Beranda',
    'footer.about': 'Tentang',
    'footer.services': 'Layanan',
    'footer.portfolio': 'Portofolio',
    'footer.blog': 'Blog',
    'footer.faq': 'FAQ',
    'footer.contact': 'Kontak',
    'footer.servicesTitle': 'Layanan',
    'footer.companyTitle': 'Perusahaan',
    'footer.aboutUs': 'Tentang Kami',
    'footer.ourServices': 'Layanan Kami',
    'footer.contactUs': 'Hubungi Kami',
    'footer.certTitle': 'Sertifikat',
    'footer.findUsTitle': 'Temukan Kami',
    'footer.phoneLabel': 'Telepon:',
    'footer.emailLabel': 'Email:',
    'footer.rights': 'Seluruh Hak Cipta Dilindungi',

    // ---- Home: chatbot widget ----
    'chatbot.openLabel': 'Buka chat',
    'chatbot.closeLabel': 'Tutup chat',
    'chatbot.subtitle': 'Biasanya balas dalam beberapa detik',
    'chatbot.placeholder': 'Tulis pertanyaan...',
    'chatbot.sendLabel': 'Kirim',
    'chatbot.greeting': 'Halo! \uD83D\uDC4B Ada yang bisa dibantu seputar layanan {site}?',
    'chatbot.errorGeneral': 'Maaf, terjadi kendala. Silakan coba lagi.',
    'chatbot.errorConnection': 'Maaf, tidak dapat terhubung ke server. Silakan coba lagi.',

    // ---- About page ----
    'aboutPage.mastheadEyebrow': 'Tentang Kami',
    'aboutPage.mastheadTitle': 'Berkolaborasi & bertumbuh bersama membangun ekonomi digital Indonesia',
    'aboutPage.mastheadDesc': 'Bergabung bersama {site} untuk kemudahan dan kemajuan bisnis Anda. Kami menghadirkan solusi digital seperti website, aplikasi, hingga sistem custom yang dirancang sesuai kebutuhan Anda.',
    'aboutPage.sectionTitle': 'Tentang {site}',
    'aboutPage.sectionParagraph1': '{site} adalah studio pengembangan website dan aplikasi yang berlokasi di Sidoarjo, Jawa Timur. Kami membantu bisnis dari berbagai skala untuk hadir secara profesional di dunia digital, mulai dari website profil perusahaan, sistem informasi custom, hingga aplikasi web yang disesuaikan dengan alur kerja masing-masing klien.',
    'aboutPage.sectionParagraph2': 'Kami percaya setiap bisnis punya kebutuhan yang berbeda, sehingga setiap proyek kami kerjakan dengan pendekatan yang disesuaikan — bukan template yang dipaksakan sama untuk semua orang.',
    'aboutPage.teamEyebrow': 'Founder & Tim',
    'aboutPage.teamTitle': 'Tak kenal maka tak sayang',

    // ---- Contact page ----
    'contactPage.eyebrow': 'Kontak',
    'contactPage.mastheadTitle': 'Hubungi Kami, dan Mari Mulai Cerita Baru',
    'contactPage.mastheadDesc': 'Punya pertanyaan atau ingin konsultasi proyek website, aplikasi, atau sistem custom? Tim {site} siap membantu Anda.',
    'contactPage.addressTitle': 'Alamat',
    'contactPage.callTitle': 'Hubungi Kami',
    'contactPage.emailTitle': 'Email Kami',
    'contactPage.hoursTitle': 'Jam Operasional',
    'contactPage.hoursWeekday': 'Senin - Jumat',
    'contactPage.formNameLabel': 'Nama Anda',
    'contactPage.formEmailLabel': 'Email Anda',
    'contactPage.formSubjectLabel': 'Subjek',
    'contactPage.formMessageLabel': 'Pesan',
    'contactPage.formSubmit': 'Kirim Pesan',

    // ---- Blog index page ----
    'blogIndex.eyebrow': 'Blog',
    'blogIndex.mastheadTitle': 'Artikel & Insight',
    'blogIndex.mastheadDesc': 'Tips, cerita di balik layar, dan wawasan seputar dunia produksi kreatif dari {site}.',
    'blogIndex.readMore': 'Baca selengkapnya →',
    'blogIndex.empty': 'Belum ada artikel yang dipublikasikan.',

    // ---- Portfolio page ----
    'portfolioPage.eyebrow': 'Portofolio',
    'portfolioPage.mastheadTitle': 'Temukan Inspirasimu',
    'portfolioPage.mastheadDesc': 'Kumpulan proyek website, aplikasi, dan sistem custom yang telah dikerjakan tim {site} untuk berbagai klien.',
    'portfolioPage.filterAll': 'Semua',
    'portfolioPage.viewProject': 'Lihat Proyek →',
    'portfolioPage.empty': 'Belum ada portofolio yang dipublikasikan.',
    'portfolioPage.emptyFiltered': 'Belum ada proyek pada kategori ini.',
    'solusiPortfolio.empty': 'Belum ada portofolio yang dipublikasikan untuk layanan ini.',
    'portfolioPage.viewDetail': 'Lihat detail proyek',
    'portfolioPage.lightboxClose': 'Tutup',
    'portfolioPage.lightboxPrev': 'Sebelumnya',
    'portfolioPage.lightboxNext': 'Berikutnya',

    // ---- Blog show (article) page ----
    'blogShow.backToBlog': 'Kembali ke Blog',
    'blogShow.shareLabel': 'Bagikan',
    'blogShow.shareFacebook': 'Bagikan ke Facebook',
    'blogShow.shareWhatsapp': 'Bagikan ke WhatsApp',
    'blogShow.shareTelegram': 'Bagikan ke Telegram',
    'blogShow.shareLinkedin': 'Bagikan ke LinkedIn',
    'blogShow.shareX': 'Bagikan ke X',
    'blogShow.copyLink': 'Salin tautan artikel',
    'blogShow.copyLinkTooltip': 'Salin Link',
    'blogShow.likeLabel': 'Suka',
    'blogShow.relatedTitle': 'Artikel Lainnya',

    // ---- Solusi pages (website / app-mobile / custom-system) shared CTAs ----
    'solusi.ctaFreeConsult': 'Konsultasi Gratis',
    'solusi.ctaViewPortfolio': 'Lihat Portofolio',
    'solusi.ctaViewAllPortfolio': 'Lihat Semua Portofolio',
    'solusi.ctaWhatsapp': 'Chat via WhatsApp',
    'solusi.ctaContactForm': 'Isi Form Kontak'
  },

  en: {
    // ---- Navbar (shared across all pages) ----
    'nav.home': 'Home',
    'nav.servicesToggle': 'Services',
    'nav.about': 'About Us',
    'nav.servicesItem': 'Services',
    'nav.portfolio': 'Portfolio',
    'nav.infoToggle': 'Info',
    'nav.blog': 'Blog',
    'nav.faq': 'FAQ',
    'nav.contact': 'Contact',
    'nav.toggleLabel': 'Toggle menu',
    'nav.servicesWebsite': 'Website Development Services',
    'nav.servicesWebsiteDesc': 'Professional, responsive websites tailored to your business',
    'nav.servicesAppMobile': 'Mobile App Development Services',
    'nav.servicesAppMobileDesc': 'Android & iOS apps built around your business needs',
    'nav.servicesCustomSystem': 'Custom System Development Services',
    'nav.servicesCustomSystemDesc': 'Custom software to automate your business processes',
    'nav.whatsappCta': 'WhatsApp Us',
    'nav.langChangeLabel': 'Change language',
    'nav.langChooseLabel': 'Choose language',

    // ---- Chatbot widget (shared across all pages) ----
    'chatbot.windowLabel': 'Live chat',

    // ---- Home: hero ----
    'hero.ctaWhatsapp': 'Chat via WhatsApp',

    // ---- Home: about section ----
    'about.label': 'Who We Are',
    'about.cta': 'Learn More',

    // ---- Home: clients ----
    'clients.title': 'Clients',
    'clients.subtitle': 'Trusted by businesses across industries',

    // ---- Home: testimonials ----
    'testimonials.title': 'Testimonials',
    'testimonials.subtitle': 'What our clients say about working with us',

    // ---- Home: blog preview ----
    'blog.title': 'Latest Articles',
    'blog.subtitle': 'Tips and insights from the world of creative production',
    'blog.cta': 'View All Articles',
    'blog.readmore': 'Read More →',

    // ---- Home: FAQ ----
    'faq.title': 'F.A.Q',
    'faq.subtitle': 'Frequently asked questions',

    // ---- Footer (shared across all pages) ----
    'footer.navTitle': 'Navigation',
    'footer.home': 'Home',
    'footer.about': 'About',
    'footer.services': 'Services',
    'footer.portfolio': 'Portfolio',
    'footer.blog': 'Blog',
    'footer.faq': 'FAQ',
    'footer.contact': 'Contact',
    'footer.servicesTitle': 'Services',
    'footer.companyTitle': 'Company',
    'footer.aboutUs': 'About Us',
    'footer.ourServices': 'Our Services',
    'footer.contactUs': 'Contact Us',
    'footer.certTitle': 'Certificates',
    'footer.findUsTitle': 'Find Us',
    'footer.phoneLabel': 'Phone:',
    'footer.emailLabel': 'Email:',
    'footer.rights': 'All Rights Reserved',

    // ---- Home: chatbot widget ----
    'chatbot.openLabel': 'Open chat',
    'chatbot.closeLabel': 'Close chat',
    'chatbot.subtitle': 'Usually replies within a few seconds',
    'chatbot.placeholder': 'Type your question...',
    'chatbot.sendLabel': 'Send',
    'chatbot.greeting': 'Hi! \uD83D\uDC4B Need help with {site}\u2019s services?',
    'chatbot.errorGeneral': 'Sorry, something went wrong. Please try again.',
    'chatbot.errorConnection': 'Sorry, we could not reach the server. Please try again.',

    // ---- About page ----
    'aboutPage.mastheadEyebrow': 'About Us',
    'aboutPage.mastheadTitle': "Collaborating and growing together to build Indonesia's digital economy",
    'aboutPage.mastheadDesc': 'Join {site} for a smoother, more successful business journey. We deliver digital solutions — websites, applications, and custom systems — built around your needs.',
    'aboutPage.sectionTitle': 'About {site}',
    'aboutPage.sectionParagraph1': '{site} is a website and application development studio based in Sidoarjo, East Java. We help businesses of all sizes show up professionally online — from company profile websites and custom information systems to web applications tailored to each client\u2019s workflow.',
    'aboutPage.sectionParagraph2': 'We believe every business has different needs, so every project is approached on its own terms — never a one-size-fits-all template.',
    'aboutPage.teamEyebrow': 'Founder & Team',
    'aboutPage.teamTitle': 'Get to know the people behind the work',

    // ---- Contact page ----
    'contactPage.eyebrow': 'Contact',
    'contactPage.mastheadTitle': "Get in Touch, Let's Start a New Story",
    'contactPage.mastheadDesc': 'Have a question or want to discuss a website, application, or custom system project? The {site} team is ready to help.',
    'contactPage.addressTitle': 'Address',
    'contactPage.callTitle': 'Call Us',
    'contactPage.emailTitle': 'Email Us',
    'contactPage.hoursTitle': 'Open Hours',
    'contactPage.hoursWeekday': 'Monday - Friday',
    'contactPage.formNameLabel': 'Your Name',
    'contactPage.formEmailLabel': 'Your Email',
    'contactPage.formSubjectLabel': 'Subject',
    'contactPage.formMessageLabel': 'Message',
    'contactPage.formSubmit': 'Send Message',

    // ---- Blog index page ----
    'blogIndex.eyebrow': 'Blog',
    'blogIndex.mastheadTitle': 'Articles & Insights',
    'blogIndex.mastheadDesc': 'Tips, behind-the-scenes stories, and insights from the world of creative production at {site}.',
    'blogIndex.readMore': 'Read more →',
    'blogIndex.empty': 'No articles have been published yet.',

    // ---- Portfolio page ----
    'portfolioPage.eyebrow': 'Portfolio',
    'portfolioPage.mastheadTitle': 'Find Your Inspiration',
    'portfolioPage.mastheadDesc': 'A collection of website, application, and custom system projects delivered by the {site} team for various clients.',
    'portfolioPage.filterAll': 'All',
    'portfolioPage.viewProject': 'View Project →',
    'portfolioPage.empty': 'No portfolio items have been published yet.',
    'portfolioPage.emptyFiltered': 'No projects in this category yet.',
    'solusiPortfolio.empty': 'No portfolio items have been published yet for this service.',
    'portfolioPage.viewDetail': 'View project detail',
    'portfolioPage.lightboxClose': 'Close',
    'portfolioPage.lightboxPrev': 'Previous',
    'portfolioPage.lightboxNext': 'Next',

    // ---- Blog show (article) page ----
    'blogShow.backToBlog': 'Back to Blog',
    'blogShow.shareLabel': 'Share',
    'blogShow.shareFacebook': 'Share on Facebook',
    'blogShow.shareWhatsapp': 'Share on WhatsApp',
    'blogShow.shareTelegram': 'Share on Telegram',
    'blogShow.shareLinkedin': 'Share on LinkedIn',
    'blogShow.shareX': 'Share on X',
    'blogShow.copyLink': 'Copy article link',
    'blogShow.copyLinkTooltip': 'Copy Link',
    'blogShow.likeLabel': 'Like',
    'blogShow.relatedTitle': 'More Articles',

    // ---- Solusi pages (website / app-mobile / custom-system) shared CTAs ----
    'solusi.ctaFreeConsult': 'Free Consultation',
    'solusi.ctaViewPortfolio': 'View Portfolio',
    'solusi.ctaViewAllPortfolio': 'View All Portfolio',
    'solusi.ctaWhatsapp': 'Chat via WhatsApp',
    'solusi.ctaContactForm': 'Fill Contact Form'
  }
};
