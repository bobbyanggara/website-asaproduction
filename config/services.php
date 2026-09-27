<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'groq' => [
        'key' => env('GROQ_API_KEY'),
        // CATATAN (2026-09-25): 'llama-3.1-8b-instant' dan
        // 'llama-3.3-70b-versatile' yang dulu dipakai di sini SUDAH PINDAH
        // ke tier Enterprise (contact-sales) di Groq per pengecekan
        // console.groq.com/docs/models — API key Free/Developer biasa akan
        // dapat error 404 "model_not_found" kalau masih pakai salah satu
        // dari keduanya. Diganti ke 'openai/gpt-oss-20b': model pay-as-you-go
        // yang masih tersedia untuk akun biasa, dan justru lebih cepat
        // (~1000 token/detik) untuk chatbot FAQ sederhana seperti ini.
        // Kalau butuh kualitas jawaban lebih pintar (agak lebih lambat &
        // lebih mahal), coba 'openai/gpt-oss-120b'. Override lewat
        // GROQ_MODEL di .env. Selalu cek console.groq.com/docs/models kalau
        // suatu saat dapat error 404 lagi — daftar model aktif Groq sering
        // berubah.
        'model' => env('GROQ_MODEL', 'openai/gpt-oss-20b'),
        // Model khusus terjemahan konten (dipanggil saat admin menyimpan, jadi
        // kecepatan tidak sekritis chatbot). Kosong = pakai GROQ_MODEL di atas.
        // Kalau hasil terjemahan kurang natural, coba 'openai/gpt-oss-120b'.
        'translate_model' => env('GROQ_TRANSLATE_MODEL'),
    ],

    // Terjemahan otomatis ID -> EN saat admin menyimpan konten. Set
    // AUTO_TRANSLATE=false di .env untuk mematikan (mis. saat import massal).
    'auto_translate' => [
        'enabled' => env('AUTO_TRANSLATE', true),
    ],

    // Token statis untuk endpoint otomatisasi n8n (routes/api.php + VerifyN8nToken).
    // WAJIB di-generate sendiri per environment, jangan dikosongkan di production —
    // kalau kosong, VerifyN8nToken menutup endpoint (abort 500) daripada terbuka bebas.
    // Contoh generate: php artisan tinker --execute="echo bin2hex(random_bytes(32));"
    'n8n' => [
        'token' => env('N8N_API_TOKEN'),
    ],

];
