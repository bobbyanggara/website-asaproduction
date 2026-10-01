<?php

namespace App\Support;

/**
 * Isi bawaan halaman legal (Ketentuan Layanan & Kebijakan Privasi), lengkap
 * dalam Bahasa Indonesia dan Inggris. Dipakai LegalPage::forSlug() hanya saat
 * barisnya belum ada di database; setelah itu seluruh isi diedit dari
 * Admin > Halaman Legal.
 *
 * Token yang diganti otomatis saat ditampilkan (lihat LegalPage::prepare):
 *   {site}  nama situs   {email}  email footer   {address}  alamat footer
 *   {phone} nomor WA     {website} URL situs
 *
 * PENTING: ini template umum, bukan nasihat hukum. Sesuaikan dengan praktik
 * bisnis yang sebenarnya dan minta peninjauan penasihat hukum sebelum rilis.
 */
class LegalDefaults
{
    public static function for(string $slug): array
    {
        return $slug === 'terms' ? self::terms() : self::privacy();
    }

    private static function terms(): array
    {
        return [
            'title' => 'Ketentuan Layanan',
            'title_en' => 'Terms of Service',
            'excerpt' => 'Ketentuan ini mengatur penggunaan situs web, produk, dan layanan {site}. Mohon baca dengan saksama sebelum menggunakan layanan kami.',
            'excerpt_en' => 'These terms govern the use of the {site} website, products, and services. Please read them carefully before using our services.',
            'meta_description' => 'Ketentuan Layanan {site}: aturan penggunaan situs dan layanan, hak kekayaan intelektual, serta batasan tanggung jawab.',
            'effective_date' => now()->toDateString(),
            'is_active' => true,
            'content' => <<<'HTML'
<h2>1. Penerimaan Ketentuan</h2>
<p>Dengan mengakses atau menggunakan situs web {site} ("Situs"), serta produk dan layanan yang kami tawarkan, Anda menyatakan telah membaca, memahami, dan menyetujui Ketentuan Layanan ini. Jika Anda tidak setuju dengan sebagian atau seluruh ketentuan, mohon hentikan penggunaan Situs.</p>
<p>Jika Anda menggunakan Situs atas nama perusahaan atau organisasi, Anda menyatakan memiliki kewenangan untuk mengikat pihak tersebut pada ketentuan ini.</p>

<h2>2. Definisi</h2>
<ul>
<li><strong>"Kami"</strong> berarti {site}, penyedia jasa pengembangan perangkat lunak dan website.</li>
<li><strong>"Anda"</strong> berarti setiap pengunjung, calon klien, atau klien yang mengakses Situs atau menggunakan layanan kami.</li>
<li><strong>"Layanan"</strong> berarti jasa pembuatan website, aplikasi mobile, sistem kustom, serta produk perangkat lunak yang kami tawarkan.</li>
<li><strong>"Konten"</strong> berarti seluruh teks, gambar, logo, desain, video, kode, dan materi lain yang tersedia di Situs.</li>
</ul>

<h2>3. Ruang Lingkup Layanan</h2>
<p>Kami menyediakan jasa pembuatan website, aplikasi mobile, dan sistem kustom, serta produk perangkat lunak siap pakai. Informasi di Situs bersifat umum dan tidak otomatis menjadi penawaran yang mengikat.</p>
<p>Ruang lingkup, jadwal, biaya, dan ketentuan teknis untuk setiap proyek ditetapkan dalam proposal, penawaran, atau perjanjian tertulis tersendiri antara Anda dan kami. Apabila terdapat perbedaan antara Ketentuan ini dan perjanjian tertulis tersebut, perjanjian tertulis yang berlaku untuk proyek terkait didahulukan.</p>

<h2>4. Penggunaan Situs yang Diperbolehkan</h2>
<p>Anda setuju untuk menggunakan Situs hanya untuk tujuan yang sah. Anda tidak diperkenankan untuk:</p>
<ul>
<li>melanggar hukum atau peraturan perundang-undangan yang berlaku di Republik Indonesia;</li>
<li>mengirimkan konten yang menyesatkan, menipu, berbahaya, melanggar hak pihak lain, atau mengandung malware;</li>
<li>mencoba memperoleh akses tidak sah ke sistem, server, basis data, atau panel administrasi kami;</li>
<li>mengganggu atau membebani kinerja Situs, termasuk dengan pengiriman permintaan otomatis secara berlebihan (scraping, spam, atau serangan sejenis);</li>
<li>menyalin, memodifikasi, atau mendistribusikan Konten tanpa izin tertulis dari kami.</li>
</ul>

<h2>5. Proyek, Pembayaran, dan Revisi</h2>
<p>Untuk layanan berbasis proyek, hal-hal berikut berlaku kecuali ditentukan lain dalam perjanjian tertulis:</p>
<ul>
<li>pekerjaan dimulai setelah kedua pihak menyepakati ruang lingkup dan pembayaran awal (jika ada) diterima;</li>
<li>jadwal pengerjaan bergantung pada kelengkapan dan ketepatan waktu materi, data, serta persetujuan dari Anda;</li>
<li>permintaan perubahan di luar ruang lingkup yang disepakati dapat memengaruhi biaya dan jadwal, dan akan dikonfirmasi terlebih dahulu;</li>
<li>pembayaran dilakukan sesuai termin dan metode yang tercantum dalam penawaran atau perjanjian.</li>
</ul>

<h2>6. Hak Kekayaan Intelektual</h2>
<p>Seluruh Konten di Situs, termasuk nama, logo, desain, dan kode sumber Situs, dilindungi oleh hukum hak cipta dan kekayaan intelektual yang berlaku, dan merupakan milik {site} atau pemberi lisensinya.</p>
<p>Hak atas hasil pekerjaan proyek (termasuk kode sumber, desain, dan dokumentasi) diatur dalam perjanjian proyek masing-masing. Kecuali disepakati lain secara tertulis, kami tetap memiliki hak atas komponen, pustaka, kerangka kerja, dan alat bantu yang kami kembangkan sebelumnya atau secara umum dan dapat digunakan kembali, serta atas komponen pihak ketiga yang tunduk pada lisensinya masing-masing.</p>
<p>Kami berhak menampilkan nama dan hasil karya proyek dalam portofolio, kecuali Anda menyatakan keberatan secara tertulis.</p>

<h2>7. Informasi yang Anda Kirimkan</h2>
<p>Pesan, formulir kontak, dan percakapan yang Anda kirimkan melalui Situs (termasuk chatbot) harus akurat dan tidak melanggar hak pihak lain. Anda bertanggung jawab atas informasi yang Anda berikan. Penggunaan data pribadi Anda diatur dalam <a href="/kebijakan-privasi">Kebijakan Privasi</a> kami.</p>
<p>Chatbot di Situs menggunakan teknologi kecerdasan buatan dan dimaksudkan sebagai bantuan informasi umum. Jawabannya dapat tidak lengkap atau tidak akurat dan bukan merupakan penawaran, janji, atau nasihat profesional.</p>

<h2>8. Tautan dan Layanan Pihak Ketiga</h2>
<p>Situs dapat memuat tautan ke situs atau layanan pihak ketiga (misalnya WhatsApp, media sosial, dan penyedia font). Kami tidak mengendalikan dan tidak bertanggung jawab atas konten, kebijakan, atau praktik pihak ketiga tersebut. Penggunaan layanan pihak ketiga tunduk pada ketentuan masing-masing penyedia.</p>

<h2>9. Penafian Jaminan</h2>
<p>Situs dan informasi di dalamnya disediakan "sebagaimana adanya" dan "sebagaimana tersedia". Kami berupaya menjaga keakuratan dan ketersediaan Situs, tetapi tidak menjamin bahwa Situs akan selalu bebas dari kesalahan, gangguan, atau komponen berbahaya.</p>

<h2>10. Batasan Tanggung Jawab</h2>
<p>Sejauh diizinkan oleh hukum yang berlaku, {site} tidak bertanggung jawab atas kerugian tidak langsung, insidental, khusus, atau konsekuensial (termasuk kehilangan keuntungan atau data) yang timbul dari penggunaan atau ketidakmampuan menggunakan Situs.</p>
<p>Untuk layanan berbasis proyek, batas tanggung jawab kami ditentukan dalam perjanjian proyek terkait. Ketentuan ini tidak mengecualikan tanggung jawab yang menurut hukum tidak dapat dikecualikan.</p>

<h2>11. Ganti Rugi</h2>
<p>Anda setuju untuk membebaskan {site} dari tuntutan, kerugian, dan biaya (termasuk biaya hukum yang wajar) yang timbul akibat pelanggaran Anda terhadap Ketentuan ini atau pelanggaran hak pihak ketiga melalui penggunaan Situs oleh Anda.</p>

<h2>12. Penangguhan dan Pengakhiran</h2>
<p>Kami berhak membatasi, menangguhkan, atau menghentikan akses Anda ke Situs atau Layanan sewaktu-waktu apabila Anda melanggar Ketentuan ini atau jika diperlukan untuk menjaga keamanan dan integritas sistem kami.</p>

<h2>13. Perubahan Ketentuan</h2>
<p>Kami dapat memperbarui Ketentuan Layanan ini dari waktu ke waktu. Versi terbaru akan dipublikasikan di halaman ini beserta tanggal pembaruannya. Penggunaan Situs setelah perubahan dipublikasikan berarti Anda menerima ketentuan yang diperbarui.</p>

<h2>14. Hukum yang Berlaku dan Penyelesaian Sengketa</h2>
<p>Ketentuan ini diatur oleh dan ditafsirkan berdasarkan hukum Republik Indonesia. Setiap sengketa akan diupayakan penyelesaiannya secara musyawarah untuk mufakat. Apabila tidak tercapai kesepakatan, sengketa diselesaikan melalui pengadilan negeri yang berwenang di wilayah hukum kedudukan {site}, kecuali ditentukan lain dalam perjanjian tertulis.</p>

<h2>15. Hubungi Kami</h2>
<p>Jika Anda memiliki pertanyaan tentang Ketentuan Layanan ini, silakan hubungi kami:</p>
<ul>
<li><strong>Nama:</strong> {site}</li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Alamat:</strong> {address}</li>
<li><strong>WhatsApp:</strong> {phone}</li>
</ul>
HTML,
            'content_en' => <<<'HTML'
<h2>1. Acceptance of Terms</h2>
<p>By accessing or using the {site} website (the "Site") and the products and services we offer, you confirm that you have read, understood, and agreed to these Terms of Service. If you do not agree with any part of these terms, please stop using the Site.</p>
<p>If you use the Site on behalf of a company or organization, you represent that you have the authority to bind that party to these terms.</p>

<h2>2. Definitions</h2>
<ul>
<li><strong>"We"</strong> means {site}, a provider of software and website development services.</li>
<li><strong>"You"</strong> means any visitor, prospective client, or client who accesses the Site or uses our services.</li>
<li><strong>"Services"</strong> means the website development, mobile app development, custom system development, and software products we offer.</li>
<li><strong>"Content"</strong> means all text, images, logos, designs, videos, code, and other materials available on the Site.</li>
</ul>

<h2>3. Scope of Services</h2>
<p>We provide website development, mobile app development, and custom system development, as well as ready-to-use software products. Information on the Site is general in nature and does not automatically constitute a binding offer.</p>
<p>The scope, schedule, fees, and technical terms of each project are set out in a separate proposal, quotation, or written agreement between you and us. If these Terms conflict with such a written agreement, the written agreement for the relevant project prevails.</p>

<h2>4. Permitted Use of the Site</h2>
<p>You agree to use the Site for lawful purposes only. You must not:</p>
<ul>
<li>violate any law or regulation applicable in the Republic of Indonesia;</li>
<li>submit content that is misleading, fraudulent, harmful, infringes the rights of others, or contains malware;</li>
<li>attempt to gain unauthorized access to our systems, servers, databases, or administration panel;</li>
<li>disrupt or overload the Site, including through excessive automated requests (scraping, spam, or similar attacks);</li>
<li>copy, modify, or distribute Content without our written permission.</li>
</ul>

<h2>5. Projects, Payment, and Revisions</h2>
<p>For project-based services, the following applies unless otherwise stated in a written agreement:</p>
<ul>
<li>work begins once both parties have agreed on the scope and any initial payment has been received;</li>
<li>the schedule depends on the completeness and timely delivery of materials, data, and approvals from you;</li>
<li>change requests outside the agreed scope may affect cost and schedule and will be confirmed with you first;</li>
<li>payment is made according to the terms and methods stated in the quotation or agreement.</li>
</ul>

<h2>6. Intellectual Property</h2>
<p>All Content on the Site, including the name, logo, designs, and source code of the Site, is protected by applicable copyright and intellectual property laws and is owned by {site} or its licensors.</p>
<p>Rights in project deliverables (including source code, designs, and documentation) are governed by the respective project agreement. Unless agreed otherwise in writing, we retain rights in components, libraries, frameworks, and tools that we developed previously or that are reusable in general, as well as in third-party components subject to their own licenses.</p>
<p>We may display the name and work product of a project in our portfolio unless you object in writing.</p>

<h2>7. Information You Submit</h2>
<p>Messages, contact forms, and conversations you submit through the Site (including the chatbot) must be accurate and must not infringe the rights of others. You are responsible for the information you provide. The use of your personal data is governed by our <a href="/kebijakan-privasi">Privacy Policy</a>.</p>
<p>The chatbot on the Site uses artificial intelligence and is intended as general informational assistance. Its answers may be incomplete or inaccurate and do not constitute an offer, a promise, or professional advice.</p>

<h2>8. Third-Party Links and Services</h2>
<p>The Site may contain links to third-party websites or services (for example WhatsApp, social media, and font providers). We do not control and are not responsible for the content, policies, or practices of those third parties. Use of third-party services is subject to each provider's own terms.</p>

<h2>9. Disclaimer of Warranties</h2>
<p>The Site and the information on it are provided "as is" and "as available". We strive to keep the Site accurate and available, but we do not guarantee that it will always be free of errors, interruptions, or harmful components.</p>

<h2>10. Limitation of Liability</h2>
<p>To the extent permitted by applicable law, {site} is not liable for indirect, incidental, special, or consequential damages (including loss of profits or data) arising from the use of, or inability to use, the Site.</p>
<p>For project-based services, our limit of liability is set out in the relevant project agreement. These terms do not exclude liability that cannot be excluded under applicable law.</p>

<h2>11. Indemnification</h2>
<p>You agree to hold {site} harmless from claims, losses, and expenses (including reasonable legal fees) arising from your breach of these Terms or from your use of the Site in violation of third-party rights.</p>

<h2>12. Suspension and Termination</h2>
<p>We may restrict, suspend, or terminate your access to the Site or Services at any time if you violate these Terms or if necessary to protect the security and integrity of our systems.</p>

<h2>13. Changes to These Terms</h2>
<p>We may update these Terms of Service from time to time. The latest version will be published on this page together with its update date. Continued use of the Site after changes are published means you accept the updated terms.</p>

<h2>14. Governing Law and Dispute Resolution</h2>
<p>These Terms are governed by and construed in accordance with the laws of the Republic of Indonesia. Any dispute will first be resolved through good-faith discussion. If no agreement is reached, the dispute will be settled by the competent district court in the jurisdiction where {site} is domiciled, unless otherwise stated in a written agreement.</p>

<h2>15. Contact Us</h2>
<p>If you have any questions about these Terms of Service, please contact us:</p>
<ul>
<li><strong>Name:</strong> {site}</li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Address:</strong> {address}</li>
<li><strong>WhatsApp:</strong> {phone}</li>
</ul>
HTML,
        ];
    }

    private static function privacy(): array
    {
        return [
            'title' => 'Kebijakan Privasi',
            'title_en' => 'Privacy Policy',
            'excerpt' => 'Kami menghargai privasi Anda. Kebijakan ini menjelaskan data pribadi apa yang kami kumpulkan, bagaimana kami menggunakannya, dan hak Anda atas data tersebut.',
            'excerpt_en' => 'We respect your privacy. This policy explains what personal data we collect, how we use it, and your rights over that data.',
            'meta_description' => 'Kebijakan Privasi {site}: data yang kami kumpulkan, cookie, chatbot, keamanan data, dan hak Anda sesuai UU Pelindungan Data Pribadi.',
            'effective_date' => now()->toDateString(),
            'is_active' => true,
            'content' => <<<'HTML'
<h2>1. Pendahuluan</h2>
<p>{site} ("kami") berkomitmen melindungi privasi dan data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi Anda ketika Anda mengunjungi situs {website} ("Situs") atau menggunakan layanan kami, sejalan dengan Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi dan peraturan terkait lainnya.</p>
<p>Dengan menggunakan Situs, Anda memahami praktik yang dijelaskan dalam kebijakan ini.</p>

<h2>2. Data yang Kami Kumpulkan</h2>
<p><strong>Data yang Anda berikan secara langsung</strong></p>
<ul>
<li>Formulir kontak: nama, alamat email, subjek, dan isi pesan.</li>
<li>Chatbot: pesan dan pertanyaan yang Anda ketik pada widget chat.</li>
<li>Komunikasi lain: informasi yang Anda sampaikan melalui email atau WhatsApp saat menghubungi kami.</li>
</ul>
<p><strong>Data yang dikumpulkan otomatis</strong></p>
<ul>
<li>Data teknis seperti alamat IP, jenis peramban dan perangkat, sistem operasi, serta halaman yang diakses, yang umumnya tercatat pada log server untuk keperluan keamanan dan pemeliharaan.</li>
<li>Data yang disimpan pada peramban Anda, seperti preferensi bahasa, token keamanan (CSRF), dan sesi (lihat bagian Cookie).</li>
</ul>
<p>Kami tidak meminta data pribadi yang bersifat spesifik (misalnya data kesehatan, biometrik, atau keuangan) melalui Situs ini. Mohon tidak mengirimkannya melalui formulir kontak atau chatbot.</p>

<h2>3. Tujuan Penggunaan Data</h2>
<p>Kami menggunakan data pribadi Anda untuk:</p>
<ul>
<li>menanggapi pertanyaan, permintaan penawaran, dan korespondensi Anda;</li>
<li>menyediakan fitur Situs, termasuk chatbot dan pilihan bahasa;</li>
<li>menjaga keamanan, mencegah penyalahgunaan dan spam, serta memelihara kinerja Situs;</li>
<li>memahami dan memperbaiki kualitas Situs dan layanan kami;</li>
<li>memenuhi kewajiban hukum yang berlaku.</li>
</ul>
<p>Dasar pemrosesan kami adalah persetujuan Anda (misalnya saat mengirim formulir), kepentingan yang sah (keamanan dan peningkatan layanan), pelaksanaan perjanjian atau langkah pra-perjanjian atas permintaan Anda, dan kepatuhan terhadap kewajiban hukum.</p>

<h2>4. Cookie dan Teknologi Serupa</h2>
<p>Situs menggunakan cookie dan penyimpanan lokal peramban yang terbatas pada hal-hal yang diperlukan untuk berfungsinya Situs:</p>
<ul>
<li><strong>Cookie sesi dan keamanan</strong> untuk melindungi formulir dari pemalsuan permintaan (CSRF) dan menjaga sesi.</li>
<li><strong>Penyimpanan lokal</strong> untuk mengingat pilihan bahasa Anda.</li>
</ul>
<p>Anda dapat mengatur atau menghapus cookie melalui pengaturan peramban. Menonaktifkan cookie tertentu dapat memengaruhi fungsi Situs.</p>

<h2>5. Chatbot dan Pemrosesan oleh Pihak Ketiga</h2>
<p>Chatbot di Situs menggunakan layanan model bahasa dari penyedia pihak ketiga untuk menghasilkan jawaban. Pesan yang Anda ketik pada chatbot dapat dikirim ke penyedia tersebut untuk diproses. Mohon tidak memasukkan data pribadi yang sensitif ke dalam chatbot.</p>
<p>Kami juga dapat memakai penyedia layanan pihak ketiga lain untuk pengiriman font, hosting, dan infrastruktur Situs. Penyedia tersebut memproses data hanya untuk menjalankan layanan terkait dan tunduk pada kebijakan privasi masing-masing.</p>

<h2>6. Pengungkapan Data kepada Pihak Lain</h2>
<p>Kami tidak menjual data pribadi Anda. Kami hanya membagikan data kepada:</p>
<ul>
<li>penyedia layanan (hosting, infrastruktur, email, dan chatbot) yang membantu kami mengoperasikan Situs, dengan kewajiban menjaga kerahasiaan dan keamanan;</li>
<li>pihak berwenang atau lembaga penegak hukum apabila diwajibkan oleh hukum atau perintah yang sah;</li>
<li>penerus usaha, apabila terjadi penggabungan, akuisisi, atau restrukturisasi, dengan tetap menjaga perlindungan data Anda.</li>
</ul>

<h2>7. Penyimpanan Data</h2>
<p>Kami menyimpan data pribadi Anda selama diperlukan untuk memenuhi tujuan yang dijelaskan dalam kebijakan ini, atau selama diwajibkan oleh hukum. Setelah tidak diperlukan, data akan dihapus atau dianonimkan secara aman. Data Anda disimpan pada server yang kami kelola atau penyedia hosting yang kami gunakan, yang dapat berlokasi di dalam maupun di luar Indonesia.</p>

<h2>8. Transfer Data Lintas Negara</h2>
<p>Apabila data Anda diproses di luar wilayah Indonesia (misalnya oleh penyedia layanan pihak ketiga), kami berupaya memastikan tingkat pelindungan yang memadai sesuai ketentuan hukum yang berlaku.</p>

<h2>9. Keamanan Data</h2>
<p>Kami menerapkan langkah teknis dan organisasi yang wajar untuk melindungi data pribadi Anda, antara lain koneksi terenkripsi (HTTPS), pembatasan akses ke panel administrasi, dan pembatasan laju permintaan (rate limiting). Meski demikian, tidak ada metode transmisi atau penyimpanan data yang sepenuhnya aman, sehingga kami tidak dapat menjamin keamanan absolut.</p>

<h2>10. Hak Anda</h2>
<p>Berdasarkan hukum yang berlaku, Anda berhak untuk:</p>
<ul>
<li>memperoleh informasi tentang pemrosesan data pribadi Anda;</li>
<li>mengakses dan memperoleh salinan data pribadi Anda;</li>
<li>memperbaiki data yang tidak akurat atau tidak lengkap;</li>
<li>meminta penghapusan atau pemusnahan data pribadi Anda;</li>
<li>menarik persetujuan yang telah diberikan;</li>
<li>mengajukan keberatan atau meminta pembatasan pemrosesan tertentu.</li>
</ul>
<p>Untuk menggunakan hak tersebut, hubungi kami melalui kontak di bagian akhir kebijakan ini. Kami dapat meminta verifikasi identitas dan akan menanggapi dalam jangka waktu yang ditentukan peraturan yang berlaku.</p>

<h2>11. Privasi Anak</h2>
<p>Situs ini tidak ditujukan bagi anak di bawah usia 18 tahun dan kami tidak dengan sengaja mengumpulkan data pribadi anak tanpa persetujuan orang tua atau wali. Jika Anda yakin kami telah mengumpulkan data anak, hubungi kami agar dapat kami hapus.</p>

<h2>12. Tautan ke Situs Lain</h2>
<p>Situs dapat memuat tautan ke situs pihak ketiga. Kami tidak bertanggung jawab atas praktik privasi atau isi situs tersebut, dan menyarankan Anda membaca kebijakan privasi masing-masing.</p>

<h2>13. Perubahan Kebijakan</h2>
<p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan dipublikasikan di halaman ini dengan tanggal pembaruan terbaru. Untuk perubahan yang material, kami akan berupaya memberi pemberitahuan yang layak.</p>

<h2>14. Hubungi Kami</h2>
<p>Pertanyaan, permintaan, atau keluhan terkait data pribadi dapat disampaikan kepada:</p>
<ul>
<li><strong>Nama:</strong> {site}</li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Alamat:</strong> {address}</li>
<li><strong>WhatsApp:</strong> {phone}</li>
</ul>
HTML,
            'content_en' => <<<'HTML'
<h2>1. Introduction</h2>
<p>{site} ("we") is committed to protecting your privacy and personal data. This Privacy Policy explains how we collect, use, store, and protect your personal data when you visit {website} (the "Site") or use our services, in line with Indonesia's Law No. 27 of 2022 on Personal Data Protection and other related regulations.</p>
<p>By using the Site, you acknowledge the practices described in this policy.</p>

<h2>2. Data We Collect</h2>
<p><strong>Data you provide directly</strong></p>
<ul>
<li>Contact form: your name, email address, subject, and message.</li>
<li>Chatbot: the messages and questions you type into the chat widget.</li>
<li>Other communications: information you share by email or WhatsApp when you contact us.</li>
</ul>
<p><strong>Data collected automatically</strong></p>
<ul>
<li>Technical data such as IP address, browser and device type, operating system, and pages visited, which is generally recorded in server logs for security and maintenance.</li>
<li>Data stored in your browser, such as language preference, security tokens (CSRF), and sessions (see the Cookies section).</li>
</ul>
<p>We do not ask for specific or sensitive personal data (for example health, biometric, or financial data) through the Site. Please do not submit such data through the contact form or the chatbot.</p>

<h2>3. How We Use Your Data</h2>
<p>We use your personal data to:</p>
<ul>
<li>respond to your inquiries, quotation requests, and correspondence;</li>
<li>provide Site features, including the chatbot and language selection;</li>
<li>maintain security, prevent abuse and spam, and keep the Site performing well;</li>
<li>understand and improve the quality of our Site and services;</li>
<li>comply with applicable legal obligations.</li>
</ul>
<p>Our legal bases are your consent (for example when you submit a form), legitimate interests (security and service improvement), performance of a contract or pre-contractual steps at your request, and compliance with legal obligations.</p>

<h2>4. Cookies and Similar Technologies</h2>
<p>The Site uses cookies and browser local storage limited to what is necessary for the Site to function:</p>
<ul>
<li><strong>Session and security cookies</strong> to protect forms from cross-site request forgery (CSRF) and to maintain sessions.</li>
<li><strong>Local storage</strong> to remember your language choice.</li>
</ul>
<p>You can manage or delete cookies through your browser settings. Disabling certain cookies may affect how the Site works.</p>

<h2>5. Chatbot and Third-Party Processing</h2>
<p>The chatbot on the Site uses a language-model service from a third-party provider to generate answers. Messages you type into the chatbot may be sent to that provider for processing. Please do not enter sensitive personal data into the chatbot.</p>
<p>We may also use other third-party providers for font delivery, hosting, and Site infrastructure. These providers process data only to deliver the relevant service and are subject to their own privacy policies.</p>

<h2>6. Disclosure of Data</h2>
<p>We do not sell your personal data. We share data only with:</p>
<ul>
<li>service providers (hosting, infrastructure, email, and chatbot) who help us operate the Site, under obligations of confidentiality and security;</li>
<li>authorities or law enforcement where required by law or a valid order;</li>
<li>a successor to our business in the event of a merger, acquisition, or restructuring, with your data protection maintained.</li>
</ul>

<h2>7. Data Retention</h2>
<p>We retain your personal data for as long as necessary to fulfil the purposes described in this policy, or as required by law. When no longer needed, data is securely deleted or anonymized. Your data is stored on servers we manage or on hosting providers we use, which may be located inside or outside Indonesia.</p>

<h2>8. Cross-Border Data Transfers</h2>
<p>If your data is processed outside Indonesia (for example by third-party service providers), we take steps to ensure an adequate level of protection in accordance with applicable law.</p>

<h2>9. Data Security</h2>
<p>We apply reasonable technical and organizational measures to protect your personal data, including encrypted connections (HTTPS), restricted access to the administration panel, and request rate limiting. However, no method of transmission or storage is completely secure, so we cannot guarantee absolute security.</p>

<h2>10. Your Rights</h2>
<p>Under applicable law, you have the right to:</p>
<ul>
<li>obtain information about the processing of your personal data;</li>
<li>access and obtain a copy of your personal data;</li>
<li>correct inaccurate or incomplete data;</li>
<li>request deletion or destruction of your personal data;</li>
<li>withdraw consent you have given;</li>
<li>object to, or request restriction of, certain processing.</li>
</ul>
<p>To exercise these rights, contact us using the details at the end of this policy. We may ask you to verify your identity and will respond within the period set by applicable regulations.</p>

<h2>11. Children's Privacy</h2>
<p>The Site is not directed at children under 18 and we do not knowingly collect children's personal data without the consent of a parent or guardian. If you believe we have collected a child's data, please contact us so we can delete it.</p>

<h2>12. Links to Other Sites</h2>
<p>The Site may contain links to third-party websites. We are not responsible for their privacy practices or content, and we encourage you to read their privacy policies.</p>

<h2>13. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Changes will be published on this page with the latest update date. For material changes, we will make reasonable efforts to notify you.</p>

<h2>14. Contact Us</h2>
<p>Questions, requests, or complaints regarding personal data can be sent to:</p>
<ul>
<li><strong>Name:</strong> {site}</li>
<li><strong>Email:</strong> {email}</li>
<li><strong>Address:</strong> {address}</li>
<li><strong>WhatsApp:</strong> {phone}</li>
</ul>
HTML,
        ];
    }
}
