{{--
  Section "F.A.Q" — markup & style disamakan persis dengan section FAQ di
  landing page (resources/views/home.blade.php, class .faq-section/.faq-list/
  .faq-item), supaya konsisten di semua halaman /solusi/*. Perlu variabel
  $faqs (Collection<WebsiteFaq|AppMobileFaq|CustomSystemFaq>).
--}}
@if($faqs->count())
<section class="well faq-section" id="faq">
  <div class="container center wow fadeInUp" data-wow-duration="0.9s">
    <h2 class="section-heading" data-i18n="faq.title">F.A.Q</h2>
    <hr class="section-heading_bar"/>
    <p data-i18n="faq.subtitle">Pertanyaan yang sering diajukan</p>
  </div>
  <div class="container">
    <div class="faq-list">
      @foreach($faqs as $faq)
      <details class="faq-item wow fadeInUp" data-wow-delay="{{ 0.08 * ($loop->index % 6) }}s" data-wow-duration="0.7s">
        <summary>
          <span>@bi($faq, 'question')</span>
          <i class="fa fa-angle-right" aria-hidden="true"></i>
        </summary>
        <div class="faq-answer">
          <p>@bi($faq, 'answer', ['nl2br' => true])</p>
        </div>
      </details>
      @endforeach
    </div>
  </div>
</section>
@endif
