<?php

namespace App\Support;

/**
 * Isi bawaan dokumen legal PER APLIKASI (mis. "Kalkulator Shopee Seller").
 * Dipakai LegalPage::forSlug($slug, $app) hanya saat dokumen aplikasi itu belum
 * ada; setelah itu seluruh isi diedit dari Admin > Halaman Legal > (pilih aplikasi).
 *
 * Token yang diganti saat ditampilkan: {app} nama aplikasi, {site} nama perusahaan,
 * {email}, {address}, {phone}, {website} (lihat LegalPage::tokens).
 *
 * PENTING: ini template umum, bukan nasihat hukum. Sesuaikan dengan fitur & praktik
 * data aplikasi yang sebenarnya, lalu minta peninjauan penasihat hukum sebelum rilis.
 */
class LegalAppDefaults
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
            'excerpt' => 'Ketentuan ini mengatur penggunaan aplikasi {app} yang disediakan oleh {site}. Mohon baca dengan saksama sebelum menggunakan aplikasi.',
            'excerpt_en' => 'These terms govern the use of the {app} application provided by {site}. Please read them carefully before using the application.',
            'meta_description' => 'Ketentuan Layanan aplikasi {app} oleh {site}: aturan penggunaan, hak kekayaan intelektual, dan batasan tanggung jawab.',
            'effective_date' => now()->toDateString(),
            'is_active' => true,
            'content' => <<<'HTML'
<h2>1. Penerimaan Ketentuan</h2>
<p>Dengan mengunduh, mengakses, atau menggunakan aplikasi {app} ("Aplikasi") yang disediakan oleh {site} ("Kami"), Anda menyatakan telah membaca, memahami, dan menyetujui Ketentuan Layanan ini. Jika Anda tidak setuju, mohon hentikan penggunaan Aplikasi.</p>

<h2>2. Tentang Aplikasi</h2>
<p>{app} adalah aplikasi yang dikembangkan dan dikelola oleh {site}. Fitur, tampilan, dan cakupan layanan dapat berubah sewaktu-waktu sesuai pengembangan Aplikasi.</p>

<h2>3. Penggunaan yang Diperbolehkan</h2>
<p>Anda setuju untuk menggunakan Aplikasi hanya untuk tujuan yang sah dan sesuai hukum yang berlaku. Anda tidak diperbolehkan untuk:</p>
<ul>
<li>membongkar, melakukan rekayasa balik, atau menyalin Aplikasi tanpa izin tertulis dari Kami;</li>
<li>mengganggu keamanan, kinerja, atau ketersediaan Aplikasi dan sistem terkait;</li>
<li>menggunakan Aplikasi untuk kegiatan yang melanggar hukum atau merugikan pihak lain;</li>
<li>menjual kembali atau mendistribusikan Aplikasi tanpa persetujuan Kami.</li>
</ul>

<h2>4. Hasil dan Informasi Bersifat Estimasi</h2>
<p>Hasil perhitungan, saran, atau informasi yang ditampilkan Aplikasi disediakan sebagai bantuan dan bersifat estimasi. Hasil tersebut dapat berbeda dengan kondisi sebenarnya, misalnya karena perubahan biaya, tarif, atau kebijakan pihak ketiga. Anda bertanggung jawab memeriksa kembali hasil tersebut sebelum mengambil keputusan.</p>

<h2>5. Merek dan Layanan Pihak Ketiga</h2>
<p>Nama, logo, dan merek pihak ketiga yang disebut di dalam Aplikasi (termasuk platform marketplace) adalah milik pemiliknya masing-masing. Kecuali dinyatakan lain, {app} tidak berafiliasi, disponsori, atau didukung oleh pihak tersebut.</p>

<h2>6. Hak Kekayaan Intelektual</h2>
<p>Aplikasi, termasuk kode, desain, teks, dan logo, dilindungi oleh hukum hak cipta dan kekayaan intelektual lainnya dan tetap menjadi milik {site}. Kami memberi Anda lisensi terbatas, non-eksklusif, dan tidak dapat dialihkan untuk menggunakan Aplikasi sesuai ketentuan ini.</p>

<h2>7. Penafian Jaminan</h2>
<p>Aplikasi disediakan "sebagaimana adanya" dan "sebagaimana tersedia". Kami tidak menjamin Aplikasi akan selalu bebas dari kesalahan, gangguan, atau tersedia tanpa henti.</p>

<h2>8. Batasan Tanggung Jawab</h2>
<p>Sejauh diizinkan oleh hukum, {site} tidak bertanggung jawab atas kerugian tidak langsung, insidental, atau konsekuensial yang timbul dari penggunaan atau ketidakmampuan menggunakan Aplikasi, termasuk kerugian usaha akibat keputusan yang didasarkan pada hasil Aplikasi.</p>

<h2>9. Penangguhan dan Pengakhiran</h2>
<p>Kami dapat membatasi, menangguhkan, atau mengakhiri akses Anda ke Aplikasi jika Anda melanggar ketentuan ini. Anda dapat berhenti menggunakan Aplikasi kapan saja dengan menghapusnya dari perangkat Anda.</p>

<h2>10. Perubahan Ketentuan</h2>
<p>Kami dapat memperbarui Ketentuan Layanan ini dari waktu ke waktu. Tanggal "terakhir diperbarui" di bagian atas halaman menunjukkan versi terbaru. Dengan terus menggunakan Aplikasi setelah perubahan berlaku, Anda dianggap menyetujui ketentuan yang diperbarui.</p>

<h2>11. Hukum yang Berlaku</h2>
<p>Ketentuan ini diatur oleh hukum Republik Indonesia. Perselisihan akan diupayakan diselesaikan secara musyawarah terlebih dahulu sebelum menempuh jalur hukum.</p>

<h2>12. Hubungi Kami</h2>
<p>Pertanyaan tentang Ketentuan Layanan ini dapat disampaikan melalui email <strong>{email}</strong> atau kunjungi {website}.</p>
HTML,
            'content_en' => <<<'HTML'
<h2>1. Acceptance of Terms</h2>
<p>By downloading, accessing, or using the {app} application (the "Application") provided by {site} ("We"), you confirm that you have read, understood, and agreed to these Terms of Service. If you do not agree, please stop using the Application.</p>

<h2>2. About the Application</h2>
<p>{app} is an application developed and operated by {site}. Features, appearance, and scope of service may change at any time as the Application evolves.</p>

<h2>3. Permitted Use</h2>
<p>You agree to use the Application only for lawful purposes and in accordance with applicable law. You may not:</p>
<ul>
<li>decompile, reverse engineer, or copy the Application without our written permission;</li>
<li>interfere with the security, performance, or availability of the Application and related systems;</li>
<li>use the Application for unlawful activity or in a way that harms others;</li>
<li>resell or distribute the Application without our consent.</li>
</ul>

<h2>4. Results and Information Are Estimates</h2>
<p>Calculation results, suggestions, or information shown in the Application are provided as an aid and are estimates. They may differ from actual conditions, for example due to changes in fees, rates, or third-party policies. You are responsible for double-checking results before making decisions.</p>

<h2>5. Third-Party Brands and Services</h2>
<p>Names, logos, and brands of third parties mentioned in the Application (including marketplace platforms) belong to their respective owners. Unless stated otherwise, {app} is not affiliated with, sponsored by, or endorsed by those parties.</p>

<h2>6. Intellectual Property</h2>
<p>The Application, including its code, design, text, and logos, is protected by copyright and other intellectual property laws and remains the property of {site}. We grant you a limited, non-exclusive, non-transferable license to use the Application in accordance with these terms.</p>

<h2>7. Disclaimer of Warranties</h2>
<p>The Application is provided "as is" and "as available". We do not guarantee that the Application will always be error-free, uninterrupted, or continuously available.</p>

<h2>8. Limitation of Liability</h2>
<p>To the extent permitted by law, {site} is not liable for indirect, incidental, or consequential damages arising from the use of, or inability to use, the Application, including business losses from decisions based on the Application's results.</p>

<h2>9. Suspension and Termination</h2>
<p>We may restrict, suspend, or terminate your access to the Application if you breach these terms. You may stop using the Application at any time by removing it from your device.</p>

<h2>10. Changes to These Terms</h2>
<p>We may update these Terms of Service from time to time. The "last updated" date at the top of the page shows the latest version. By continuing to use the Application after changes take effect, you accept the updated terms.</p>

<h2>11. Governing Law</h2>
<p>These terms are governed by the laws of the Republic of Indonesia. Disputes will first be resolved amicably before any legal action is taken.</p>

<h2>12. Contact Us</h2>
<p>Questions about these Terms of Service can be sent to <strong>{email}</strong> or visit {website}.</p>
HTML,
        ];
    }

    private static function privacy(): array
    {
        return [
            'title' => 'Kebijakan Privasi',
            'title_en' => 'Privacy Policy',
            'excerpt' => 'Kebijakan ini menjelaskan data apa yang dikumpulkan aplikasi {app}, bagaimana {site} menggunakannya, dan pilihan yang Anda miliki.',
            'excerpt_en' => 'This policy explains what data the {app} application collects, how {site} uses it, and the choices you have.',
            'meta_description' => 'Kebijakan Privasi aplikasi {app} oleh {site}: data yang dikumpulkan, penggunaan, keamanan, dan hak Anda atas data pribadi.',
            'effective_date' => now()->toDateString(),
            'is_active' => true,
            'content' => <<<'HTML'
<h2>1. Pendahuluan</h2>
<p>{site} ("Kami") menghargai privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana aplikasi {app} ("Aplikasi") mengumpulkan, menggunakan, dan melindungi informasi Anda. Dengan menggunakan Aplikasi, Anda menyetujui praktik yang dijelaskan di sini.</p>

<h2>2. Data yang Kami Kumpulkan</h2>
<p>Data yang dikumpulkan bergantung pada fitur Aplikasi yang Anda gunakan. Secara umum dapat meliputi:</p>
<ul>
<li><strong>Data yang Anda masukkan</strong>, misalnya angka, pengaturan, atau pilihan yang Anda isi untuk keperluan perhitungan atau fitur lain di Aplikasi;</li>
<li><strong>Data akun</strong> (jika Aplikasi menyediakan login), seperti nama dan alamat email;</li>
<li><strong>Data teknis perangkat</strong>, seperti jenis perangkat, versi sistem operasi, versi Aplikasi, dan laporan kerusakan (crash);</li>
<li><strong>Data penggunaan</strong>, seperti fitur yang diakses dan waktu penggunaan, untuk membantu Kami meningkatkan Aplikasi.</li>
</ul>

<h2>3. Cara Kami Menggunakan Data</h2>
<ul>
<li>menjalankan dan menyediakan fitur Aplikasi;</li>
<li>memperbaiki masalah teknis, menjaga keamanan, dan mencegah penyalahgunaan;</li>
<li>memahami cara Aplikasi digunakan untuk pengembangan dan peningkatan layanan;</li>
<li>menanggapi pertanyaan atau permintaan dukungan dari Anda;</li>
<li>memenuhi kewajiban hukum yang berlaku.</li>
</ul>

<h2>4. Pembagian Data</h2>
<p>Kami tidak menjual data pribadi Anda. Data hanya dapat dibagikan kepada penyedia layanan yang membantu operasional Aplikasi (misalnya hosting, analitik, atau pelaporan kerusakan) dengan kewajiban menjaga kerahasiaan, atau kepada pihak berwenang jika diwajibkan oleh hukum.</p>

<h2>5. Penyimpanan dan Keamanan</h2>
<p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi data dari akses, perubahan, atau pengungkapan yang tidak sah. Namun tidak ada metode penyimpanan atau transmisi data yang sepenuhnya aman, sehingga Kami tidak dapat menjamin keamanan mutlak.</p>

<h2>6. Penyimpanan Data dan Retensi</h2>
<p>Kami menyimpan data selama diperlukan untuk tujuan yang dijelaskan dalam kebijakan ini atau selama diwajibkan oleh hukum. Setelah tidak diperlukan, data dihapus atau dianonimkan.</p>

<h2>7. Hak Anda</h2>
<p>Sesuai peraturan pelindungan data pribadi yang berlaku di Indonesia (termasuk UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi), Anda dapat meminta akses, perbaikan, atau penghapusan data pribadi Anda, serta menarik persetujuan penggunaan data. Permintaan dapat dikirim ke <strong>{email}</strong>.</p>

<h2>8. Anak-Anak</h2>
<p>Aplikasi tidak ditujukan bagi anak di bawah usia yang diizinkan hukum untuk memberikan persetujuan sendiri. Kami tidak dengan sengaja mengumpulkan data pribadi anak. Jika Anda mengetahui hal tersebut terjadi, hubungi Kami agar data dapat dihapus.</p>

<h2>9. Layanan Pihak Ketiga</h2>
<p>Aplikasi dapat memuat tautan atau memakai layanan pihak ketiga (misalnya analitik atau iklan) yang memiliki kebijakan privasinya sendiri. Kami tidak bertanggung jawab atas praktik privasi pihak ketiga tersebut.</p>

<h2>10. Perubahan Kebijakan</h2>
<p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Versi terbaru selalu tersedia di halaman ini dengan tanggal "terakhir diperbarui" yang sesuai.</p>

<h2>11. Hubungi Kami</h2>
<p>Pertanyaan atau permintaan terkait privasi dapat dikirim ke <strong>{email}</strong>.</p>
HTML,
            'content_en' => <<<'HTML'
<h2>1. Introduction</h2>
<p>{site} ("We") respects your privacy. This Privacy Policy explains how the {app} application (the "Application") collects, uses, and protects your information. By using the Application, you agree to the practices described here.</p>

<h2>2. Data We Collect</h2>
<p>The data collected depends on the Application features you use. In general it may include:</p>
<ul>
<li><strong>Data you enter</strong>, such as numbers, settings, or choices you provide for calculations or other features in the Application;</li>
<li><strong>Account data</strong> (if the Application offers login), such as your name and email address;</li>
<li><strong>Device technical data</strong>, such as device type, operating system version, Application version, and crash reports;</li>
<li><strong>Usage data</strong>, such as features accessed and time of use, to help us improve the Application.</li>
</ul>

<h2>3. How We Use Data</h2>
<ul>
<li>to operate and provide the Application's features;</li>
<li>to fix technical problems, maintain security, and prevent abuse;</li>
<li>to understand how the Application is used for development and improvement;</li>
<li>to respond to your questions or support requests;</li>
<li>to comply with applicable legal obligations.</li>
</ul>

<h2>4. Sharing of Data</h2>
<p>We do not sell your personal data. Data may only be shared with service providers who help operate the Application (for example hosting, analytics, or crash reporting) under confidentiality obligations, or with authorities when required by law.</p>

<h2>5. Storage and Security</h2>
<p>We apply reasonable security measures to protect data against unauthorized access, alteration, or disclosure. However, no method of storage or transmission is completely secure, so we cannot guarantee absolute security.</p>

<h2>6. Data Retention</h2>
<p>We keep data for as long as needed for the purposes described in this policy or as required by law. Once no longer needed, data is deleted or anonymized.</p>

<h2>7. Your Rights</h2>
<p>In line with applicable personal data protection regulations in Indonesia (including Law No. 27 of 2022 on Personal Data Protection), you may request access to, correction of, or deletion of your personal data, and withdraw your consent to its use. Requests can be sent to <strong>{email}</strong>.</p>

<h2>8. Children</h2>
<p>The Application is not intended for children below the age at which they may legally give their own consent. We do not knowingly collect personal data from children. If you become aware that this has happened, contact us so the data can be deleted.</p>

<h2>9. Third-Party Services</h2>
<p>The Application may contain links to, or use, third-party services (for example analytics or advertising) that have their own privacy policies. We are not responsible for the privacy practices of those third parties.</p>

<h2>10. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. The latest version is always available on this page with the corresponding "last updated" date.</p>

<h2>11. Contact Us</h2>
<p>Privacy questions or requests can be sent to <strong>{email}</strong>.</p>
HTML,
        ];
    }
}
