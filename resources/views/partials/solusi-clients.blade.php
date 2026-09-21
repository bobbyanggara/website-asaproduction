{{--
  Section "Clients" (logo partner berjalan) — dipakai di semua halaman
  /solusi/*, markup & animasi sama persis dengan section yang sama di
  landing page (resources/views/home.blade.php) supaya konsisten.

  Kalau logo klien masih sedikit, animasi geser (marquee) dimatikan:
  logo yang di-duplikat 2x untuk bikin loop terlihat aneh/kosong kalau
  cuma ada segelintir, dan geser bolak-balik untuk logo yang muat semua
  di satu baris juga tidak perlu. Ambang batasnya $clientMarqueeMin di
  bawah — di bawah itu logo ditampilkan statis (center, tanpa animasi).
--}}
@if($partners->count())
@php
  $clientMarqueeMin = 6;
  $useMarquee = $partners->count() >= $clientMarqueeMin;
@endphp
@if($useMarquee)
<style>
  /* ===== Partner marquee: geser mulus per-logo lalu berhenti sejenak, loop presisi tanpa skip ===== */
  @php
    $partnerTotal   = $partners->count();
    $rightCount     = (int) ceil($partnerTotal / 2);
    $leftCount      = (int) floor($partnerTotal / 2);
    $moveFraction   = 0.55;
    $rightDuration  = max(8, $rightCount * 1.8);
    $leftDuration   = max(8, $leftCount * 1.8);
  @endphp
  @if($rightCount > 0)
  .partner-marquee--right .partner-marquee_track {
    animation: solusi-partner-marquee-right {{ $rightDuration }}s ease-in-out infinite;
  }
  @keyframes solusi-partner-marquee-right {
    @for($s = 0; $s <= $rightCount; $s++)
      @php
        $posPct     = -50 * ($s / $rightCount);
        $stepStart  = ($s / $rightCount) * 100;
        $stepMoveEnd = $s < $rightCount
          ? $stepStart + (100 / $rightCount) * $moveFraction
          : $stepStart;
      @endphp
      {{ round($stepStart, 4) }}% { transform: translateX({{ round($posPct, 6) }}%); }
      @if($s < $rightCount)
        {{ round($stepMoveEnd, 4) }}% { transform: translateX({{ round(-50 * (($s+1) / $rightCount), 6) }}%); }
      @endif
    @endfor
  }
  @endif
  @if($leftCount > 0)
  .partner-marquee--left .partner-marquee_track {
    animation: solusi-partner-marquee-left {{ $leftDuration }}s ease-in-out infinite;
  }
  @keyframes solusi-partner-marquee-left {
    @for($s = 0; $s <= $leftCount; $s++)
      @php
        $posPct      = -50 + 50 * ($s / $leftCount);
        $stepStart   = ($s / $leftCount) * 100;
        $stepMoveEnd = $s < $leftCount
          ? $stepStart + (100 / $leftCount) * $moveFraction
          : $stepStart;
      @endphp
      {{ round($stepStart, 4) }}% { transform: translateX({{ round($posPct, 6) }}%); }
      @if($s < $leftCount)
        {{ round($stepMoveEnd, 4) }}% { transform: translateX({{ round(-50 + 50 * (($s+1) / $leftCount), 6) }}%); }
      @endif
    @endfor
  }
  @endif
</style>
@else
<style>
  /* Logo masih sedikit — tampil statis, rapat di tengah, tanpa animasi geser */
  .partner-marquee-wrap--static {
    justify-content: center;
  }
  .partner-marquee--static .partner-marquee_track {
    justify-content: center;
    flex-wrap: wrap;
    width: 100%;
    animation: none;
  }
</style>
@endif
<section class="well2 center">
  <div class="container wow fadeInUp" data-wow-duration="0.9s">
    <h2 data-i18n="clients.title">Clients</h2>
    <hr/>
    <p data-i18n="clients.subtitle">Telah dipercaya oleh berbagai bisnis</p>
  </div>
  @if($useMarquee)
  <div class="partner-marquee-wrap">
    <div class="partner-marquee partner-marquee--right">
      <ul class="partner-marquee_track">
        @foreach($partners as $i => $partner)
          @if($i % 2 === 0)
            <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
          @endif
        @endforeach
        @foreach($partners as $i => $partner)
          @if($i % 2 === 0)
            <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
          @endif
        @endforeach
      </ul>
    </div>
    @if($partners->count() > 1)
    <div class="partner-marquee partner-marquee--left">
      <ul class="partner-marquee_track">
        @foreach($partners as $i => $partner)
          @if($i % 2 === 1)
            <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
          @endif
        @endforeach
        @foreach($partners as $i => $partner)
          @if($i % 2 === 1)
            <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
          @endif
        @endforeach
      </ul>
    </div>
    @endif
  </div>
  @else
  <div class="partner-marquee-wrap partner-marquee-wrap--static">
    <div class="partner-marquee partner-marquee--static">
      <ul class="partner-marquee_track">
        @foreach($partners as $partner)
          <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
        @endforeach
      </ul>
    </div>
  </div>
  @endif
</section>
@endif
