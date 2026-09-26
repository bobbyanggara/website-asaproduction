{{-- ======================== CHATBOT WIDGET ======================== --}}
{{-- Partial ini dipakai bersama oleh semua halaman publik (home, blog,
     portfolio, solusi, produk, about, contact, dll) agar tombol chat
     mengambang tampil identik & sinkron di mana pun. Butuh meta
     csrf-token di <head> halaman pemakai.

     Style di bawah ini SENGAJA ditanam langsung di partial (pola yang
     sama dengan resources/views/partials/site-nav.blade.php), BUKAN
     lagi mengandalkan public/css/style.css — sebelumnya widget ini
     tampil beda/berantakan di halaman selain beranda karena cuma satu
     definisi CSS di style.css yang mudah "kalah" tergantung struktur
     & urutan render tiap halaman (z-index sama persis dengan navbar,
     dst). Dengan style ditanam di sini, tampilannya dijamin identik
     & konsisten di semua halaman, terlepas dari CSS lain apa pun yang
     dimuat halaman tsb. Definisi lama di public/css/style.css sudah
     dikosongkan, lihat catatan di sana.

     Tema dirombak jadi "iOS Liquid Glass" — kaca berlapis, blur berat,
     rim-light & specular sheen di tepi, sama seperti navbar — supaya
     konsisten dengan look kaca yang sudah dipakai di seluruh situs.
     Logic buka/tutup & kirim pesan tetap sama, tidak diubah. --}}
<style>
  .chatbot{
    position: fixed !important;
    right: 24px;
    bottom: 24px;
    z-index: 99999;
    isolation: isolate;
    font-family: "Ubuntu", sans-serif;
  }

  /* ===== Tombol mengambang (FAB) — kapsul kaca bulat khas iOS ===== */
  .chatbot_toggle{
    position: relative;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.4);
    cursor: pointer;
    background:
      linear-gradient(180deg, rgba(255,255,255,.32) 0%, rgba(255,255,255,.04) 42%, rgba(255,255,255,.08) 100%) no-repeat,
      linear-gradient(135deg, rgba(66,153,255,.5), rgba(0,81,181,.55) 55%, rgba(0,32,74,.6));
    -webkit-backdrop-filter: blur(28px) saturate(220%) brightness(1.08);
    backdrop-filter: blur(28px) saturate(220%) brightness(1.08);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow:
      0 16px 34px -10px rgba(0, 34, 76, 0.55),
      inset 0 1.5px 0 rgba(255, 255, 255, .55),
      inset 0 -1px 0 rgba(0,0,0,.16),
      inset 0 0 16px rgba(255,255,255,.08);
    transition: transform 0.25s cubic-bezier(.22,1,.36,1), box-shadow 0.25s ease;
  }
  .chatbot_toggle:before{
    content: '';
    position: absolute;
    top: 6%;
    left: 14%;
    width: 46%;
    height: 30%;
    border-radius: 50%;
    background: radial-gradient(closest-side, rgba(255,255,255,.65), rgba(255,255,255,0));
    pointer-events: none;
  }
  .chatbot_toggle:hover{
    transform: scale(1.06) translateY(-1px);
    box-shadow:
      0 20px 40px -10px rgba(0, 34, 76, 0.7),
      inset 0 1.5px 0 rgba(255, 255, 255, .7),
      inset 0 -1px 0 rgba(0,0,0,.2),
      inset 0 0 20px rgba(255,255,255,.14);
  }
  .chatbot_toggle:active{ transform: scale(0.96); }
  .chatbot_toggle-ping{
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid var(--brand-accent, #2E8BFF);
    opacity: 0.55;
    animation: chatbot-ping 2.4s cubic-bezier(0, 0, 0.2, 1) infinite;
    pointer-events: none;
  }
  .chatbot.is-open .chatbot_toggle-ping{ display: none; }
  @keyframes chatbot-ping{
    0%{ transform: scale(0.85); opacity: 0.55; }
    75%, 100%{ transform: scale(1.35); opacity: 0; }
  }
  .chatbot_toggle-icon-open,
  .chatbot_toggle-icon-close{
    position: relative;
    transition: opacity 0.15s ease, transform 0.2s ease;
  }
  .chatbot_toggle-icon-close{ display: none; }
  .chatbot.is-open .chatbot_toggle-icon-open{ display: none; }
  .chatbot.is-open .chatbot_toggle-icon-close{ display: block; }

  /* ===== Jendela chat — panel kaca, rounded besar khas iOS sheet ===== */
  .chatbot_window{
    position: absolute;
    right: 0;
    bottom: 78px;
    width: 366px;
    max-width: calc(100vw - 32px);
    height: 500px;
    max-height: calc(100vh - 140px);
    max-height: calc(100dvh - 140px);
    border-radius: 26px;
    background: #f4f7fb;
    box-shadow:
      0 34px 76px -16px rgba(0, 12, 36, 0.45),
      0 6px 18px -6px rgba(0, 12, 36, 0.2),
      inset 0 1.5px 0 rgba(255,255,255,.7);
    border: 1px solid rgba(255, 255, 255, 0.55);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    opacity: 0;
    transform: translateY(18px) scale(0.96);
    transform-origin: bottom right;
    pointer-events: none;
    transition: opacity 0.25s cubic-bezier(.22,1,.36,1), transform 0.3s cubic-bezier(.22,1,.36,1);
  }
  .chatbot.is-open .chatbot_window{
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
  }

  /* ===== Header — kaca gelap bertekstur, sama seperti navbar ===== */
  .chatbot_header{
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 16px 18px;
    background:
      linear-gradient(180deg, rgba(255,255,255,.16) 0%, rgba(255,255,255,0) 45%),
      linear-gradient(120deg, var(--brand-dark, #00224C), var(--brand-mid, #003D8F) 60%, var(--brand-light, #0051B5));
    -webkit-backdrop-filter: blur(30px) saturate(200%) brightness(1.08);
    backdrop-filter: blur(30px) saturate(200%) brightness(1.08);
    color: #fff;
    flex-shrink: 0;
    overflow: hidden;
  }
  {{-- Rim-light tipis di tepi atas + sapuan cahaya diagonal (specular
       sheen) — pola yang sama persis dipakai di .site-nav agar terasa
       seperti satu keluarga material kaca. --}}
  .chatbot_header::before{
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(180deg, rgba(255,255,255,.5) 0%, rgba(255,255,255,.12) 14%, rgba(255,255,255,0) 45%);
  }
  .chatbot_header::after{
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(115deg, rgba(255,255,255,0) 28%, rgba(255,255,255,.22) 46%, rgba(255,255,255,0) 62%);
    mix-blend-mode: overlay;
  }
  .chatbot_header-info{
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }
  .chatbot_header-avatar{
    position: relative;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
  }
  .chatbot_header-avatar img{
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: #fff;
    border: 2px solid rgba(255, 255, 255, 0.6);
    display: block;
  }
  .chatbot_header-status{
    position: absolute;
    right: -1px;
    bottom: -1px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #2ecc71;
    border: 2px solid var(--brand-dark, #00224C);
    box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.25);
  }
  .chatbot_header-text{ position: relative; min-width: 0; }
  .chatbot_header-text strong{
    display: block;
    font-size: 14.5px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .chatbot_header-sub{
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    color: rgba(255, 255, 255, 0.8);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .chatbot_header-dot{
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2ecc71;
    flex-shrink: 0;
  }
  .chatbot_header-close{
    position: relative;
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.28);
    background:
      linear-gradient(180deg, rgba(255,255,255,.24) 0%, rgba(255,255,255,.02) 100%) no-repeat,
      rgba(255, 255, 255, 0.1);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
  }
  .chatbot_header-close:hover{
    background:
      linear-gradient(180deg, rgba(255,255,255,.3) 0%, rgba(255,255,255,.04) 100%) no-repeat,
      rgba(255, 255, 255, 0.18);
    transform: rotate(90deg);
  }

  /* ===== Area pesan — kaca terang/frosted supaya teks tetap jelas ===== */
  .chatbot_messages{
    flex: 1;
    overflow-y: auto;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
    padding: 18px 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background:
      radial-gradient(120% 100% at 100% 0%, rgba(46, 139, 255, 0.08) 0%, rgba(46, 139, 255, 0) 45%),
      #f4f7fb;
    scrollbar-width: thin;
    scrollbar-color: #c7d0dc transparent;
  }
  .chatbot_messages::-webkit-scrollbar{ width: 6px; }
  .chatbot_messages::-webkit-scrollbar-thumb{
    background: #c7d0dc;
    border-radius: 10px;
  }
  .chatbot_msg{
    max-width: 80%;
    padding: 11px 15px;
    border-radius: 16px;
    font-size: 13.5px;
    line-height: 20px;
    white-space: pre-wrap;
    word-wrap: break-word;
    animation: chatbot-msg-in 0.28s cubic-bezier(.22,1,.36,1);
  }
  @keyframes chatbot-msg-in{
    from{ opacity: 0; transform: translateY(6px) scale(0.98); }
    to{ opacity: 1; transform: translateY(0) scale(1); }
  }
  .chatbot_msg-bot{
    align-self: flex-start;
    background: rgba(255,255,255,.85);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
    backdrop-filter: blur(14px) saturate(160%);
    color: #2c3542;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 10px -2px rgba(0, 20, 46, 0.1);
    border: 1px solid rgba(255,255,255,.6);
  }
  .chatbot_msg-user{
    align-self: flex-end;
    background: linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
    color: #fff;
    border-bottom-right-radius: 4px;
    border: 1px solid rgba(255,255,255,.25);
    box-shadow: 0 6px 16px -4px rgba(46, 139, 255, 0.55), inset 0 1px 0 rgba(255,255,255,.3);
  }
  .chatbot_msg-typing{
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 13px 16px;
  }
  .chatbot_msg-typing span{
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #b7bdc7;
    animation: chatbot-typing 1s infinite ease-in-out;
  }
  .chatbot_msg-typing span:nth-child(2){ animation-delay: 0.15s; }
  .chatbot_msg-typing span:nth-child(3){ animation-delay: 0.3s; }
  @keyframes chatbot-typing{
    0%, 60%, 100%{ transform: translateY(0); opacity: 0.5; }
    30%{ transform: translateY(-4px); opacity: 1; }
  }

  /* ===== Input — pill kaca + tombol kirim gradient kaca ===== */
  .chatbot_input{
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: rgba(255,255,255,.7);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    backdrop-filter: blur(20px) saturate(180%);
    border-top: 1px solid rgba(0,20,46,.06);
    flex-shrink: 0;
  }
  .chatbot_input input{
    flex: 1;
    min-width: 0;
    border: 1.5px solid rgba(0,20,46,.1);
    border-radius: 999px;
    padding: 10px 16px;
    font-size: 13.5px;
    outline: none;
    background: rgba(255,255,255,.75);
    transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
  }
  .chatbot_input input:focus{
    border-color: var(--brand-accent, #2E8BFF);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(46, 139, 255, 0.14);
  }
  .chatbot_send{
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.3);
    background:
      linear-gradient(180deg, rgba(255,255,255,.3) 0%, rgba(255,255,255,0) 55%) no-repeat,
      linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    box-shadow: 0 8px 18px -4px rgba(46, 139, 255, 0.6), inset 0 1px 0 rgba(255,255,255,.4);
    transition: transform 0.2s cubic-bezier(.22,1,.36,1), box-shadow 0.2s ease;
  }
  .chatbot_send:hover{
    transform: scale(1.08);
    box-shadow: 0 10px 22px -4px rgba(46, 139, 255, 0.7), inset 0 1px 0 rgba(255,255,255,.45);
  }
  .chatbot_send:active{ transform: scale(0.94); }

  @media (prefers-reduced-motion: reduce){
    .chatbot_toggle, .chatbot_window, .chatbot_msg, .chatbot_toggle-ping{ transition: none; animation: none; }
  }

  @media (max-width: 479px){
    .chatbot{
      right: max(14px, env(safe-area-inset-right));
      bottom: max(14px, env(safe-area-inset-bottom));
    }
    .chatbot_window{
      width: calc(100vw - 20px);
      height: calc(100vh - 96px);
      height: calc(100dvh - 96px);
      max-height: none;
      bottom: 70px;
      border-radius: 22px;
    }
  }
</style>

<div id="chatbot" class="chatbot">
  <button type="button" id="chatbotToggle" class="chatbot_toggle" data-i18n-aria-label="chatbot.openLabel" aria-label="Buka chat">
    <span class="chatbot_toggle-ping" aria-hidden="true"></span>
    <svg class="chatbot_toggle-icon-open" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
    </svg>
    <svg class="chatbot_toggle-icon-close" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
  </button>

  <div class="chatbot_window" id="chatbotWindow" role="dialog" data-i18n-aria-label="chatbot.windowLabel" aria-label="Live chat">
    <div class="chatbot_header">
      <div class="chatbot_header-info">
        <span class="chatbot_header-avatar">
          <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title }}"/>
          <span class="chatbot_header-status" aria-hidden="true"></span>
        </span>
        <div class="chatbot_header-text">
          <strong>{{ $setting->site_title }}</strong>
          <span class="chatbot_header-sub">
            <i class="chatbot_header-dot" aria-hidden="true"></i>
            <span data-i18n="chatbot.subtitle">Biasanya balas dalam beberapa detik</span>
          </span>
        </div>
      </div>
      <button type="button" id="chatbotClose" class="chatbot_header-close" data-i18n-aria-label="chatbot.closeLabel" aria-label="Tutup chat">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <div class="chatbot_messages" id="chatbotMessages">
      <div class="chatbot_msg chatbot_msg-bot" id="chatbotGreeting" data-i18n="chatbot.greeting" data-i18n-var-site="{{ $setting->site_title }}">
        Halo! 👋 Ada yang bisa dibantu seputar layanan {{ $setting->site_title }}?
      </div>
    </div>

    <form id="chatbotForm" class="chatbot_input">
      <input type="text" id="chatbotInput" data-i18n-placeholder="chatbot.placeholder" placeholder="Tulis pertanyaan..." maxlength="500" autocomplete="off" required/>
      <button type="submit" class="chatbot_send" data-i18n-aria-label="chatbot.sendLabel" aria-label="Kirim">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
      </button>
    </form>
  </div>
</div>

<script>
  (function () {
    var chatbot = document.getElementById('chatbot');
    var chatToggle = document.getElementById('chatbotToggle');
    var chatClose = document.getElementById('chatbotClose');
    var chatWindow = document.getElementById('chatbotWindow');
    var chatMessages = document.getElementById('chatbotMessages');
    var chatForm = document.getElementById('chatbotForm');
    var chatInput = document.getElementById('chatbotInput');

    if (!(chatbot && chatToggle && chatWindow && chatForm && chatInput)) { return; }

    var history = [];
    var sending = false;

    function openChat() {
      chatbot.classList.add('is-open');
      chatInput.focus();
    }

    function closeChat() {
      chatbot.classList.remove('is-open');
    }

    chatToggle.addEventListener('click', function () {
      if (chatbot.classList.contains('is-open')) {
        closeChat();
      } else {
        openChat();
      }
    });

    if (chatClose) {
      chatClose.addEventListener('click', closeChat);
    }

    function appendMessage(text, role) {
      var el = document.createElement('div');
      el.className = 'chatbot_msg chatbot_msg-' + (role === 'user' ? 'user' : 'bot');
      el.textContent = text;
      chatMessages.appendChild(el);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      return el;
    }

    function showTyping() {
      var el = document.createElement('div');
      el.className = 'chatbot_msg chatbot_msg-bot chatbot_msg-typing';
      el.id = 'chatbotTyping';
      el.innerHTML = '<span></span><span></span><span></span>';
      chatMessages.appendChild(el);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTyping() {
      var el = document.getElementById('chatbotTyping');
      if (el) { el.remove(); }
    }

    chatForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (sending) { return; }

      var message = chatInput.value.trim();
      if (!message) { return; }

      appendMessage(message, 'user');
      chatInput.value = '';
      sending = true;
      showTyping();

      var tokenMeta = document.querySelector('meta[name="csrf-token"]');
      var token = tokenMeta ? tokenMeta.getAttribute('content') : '';

      fetch('{{ route('chat.send') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ message: message, history: history, lang: (window.__siteLang ? window.__siteLang() : 'id') })
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          hideTyping();
          var reply = (data && data.reply) ? data.reply : (window.__t ? window.__t('chatbot.errorGeneral') : 'Maaf, terjadi kendala. Silakan coba lagi.');
          appendMessage(reply, 'bot');

          // Bantuan debug: kalau server balas field "debug" (hanya muncul
          // saat APP_DEBUG=true di .env server), tampilkan di Console
          // browser (F12 > Console) supaya penyebab error kelihatan tanpa
          // perlu buka log server. Tidak pernah ditampilkan ke pengunjung.
          if (data && data.debug) {
            console.error('[Chatbot debug]', data.debug);
          }

          history.push({ role: 'user', content: message });
          history.push({ role: 'assistant', content: reply });
          if (history.length > 12) {
            history = history.slice(history.length - 12);
          }
        })
        .catch(function () {
          hideTyping();
          appendMessage(window.__t ? window.__t('chatbot.errorConnection') : 'Maaf, tidak dapat terhubung ke server. Silakan coba lagi.', 'bot');
        })
        .finally(function () {
          sending = false;
        });
    });
  })();
</script>
