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
        // 'openai/gpt-oss-120b' (model lama) adalah model 120B yang berat &
        // lambat untuk chatbot FAQ sederhana seperti ini. 'llama-3.1-8b-instant'
        // jauh lebih cepat di Groq (biasanya <1 detik untuk time-to-first-token)
        // dan kualitasnya lebih dari cukup untuk menjawab seputar isi website
        // berdasarkan KONTEKS WEBSITE yang sudah disiapkan di system prompt.
        // Kalau butuh kualitas jawaban lebih pintar & masih cukup cepat, coba
        // 'llama-3.3-70b-versatile'. Override lewat GROQ_MODEL di .env.
        'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
        // Model khusus terjemahan konten (dipanggil saat admin menyimpan, jadi
        // kecepatan tidak sekritis chatbot). Kosong = pakai GROQ_MODEL di atas.
        // Kalau hasil terjemahan kurang natural, coba 'llama-3.3-70b-versatile'.
        'translate_model' => env('GROQ_TRANSLATE_MODEL'),
    ],

    // Terjemahan otomatis ID -> EN saat admin menyimpan konten. Set
    // AUTO_TRANSLATE=false di .env untuk mematikan (mis. saat import massal).
    'auto_translate' => [
        'enabled' => env('AUTO_TRANSLATE', true),
    ],

];
