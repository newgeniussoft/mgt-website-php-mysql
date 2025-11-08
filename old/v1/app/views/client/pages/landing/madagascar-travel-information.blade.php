@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<section class="content-section">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-12">
        <div id="iview-overlay">
          <div class="bottom_inside_divider"></div>
        </div>
        <img src="{{ assets('img/images/plage.jpg') }}" style="width: 100%" alt="Tours image">
      </div>
    </div>
  </div>
</section>
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
  <div class="card-styled">
    <div class="card-styled-body">
      <img src="{{ assets('img/images/hotel-ranomafana.jpg') }}" class="float-image desktop-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
        
        <h1 class="text-center text-primary font-inter-extra-bold fs-20">
          {{ $language == "es" ? $page->title_es : $page->title }}
        </h1>
      <h3 class="text-center font-inter-bold text-secondary fs-14">
        {{ $language == "es" ? $page->title_h2_es : $page->title_h2 }}
      </h3>
      <img src="{{ assets('img/images/hotel-beach.jpg') }}" class="float-image mobile-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
      {{ $language == "es" ? $page->content_es : $page->content }}
      <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
    </div>
  </div>
</section>
<section>
  <div class="top_inside_divider"></div>
  <img src="{{ assets('img/images/hotel_ranomafana.jpg') }}" style="width: 100%; margin-top: -32px" alt="shape-6">

</section>
<section class="container mb-4" style="margin-top: -90px">
  <div class="card-styled">
    <div class="card-styled-body px-4">
      <h3 class="text-center font-inter-extra-bold text-primary">
         {{ trans('land.travel.title.camp') }}
      </h3>
      <p>{{ trans('land.travel.content.camp.p-1') }}</p>
      <p>{{ trans('land.travel.content.camp.p-2') }}</p>
    </div>
  </div>
</section>
<section class="container content-section bg-light">
  <div class="card-styled">
    <div class="card-styled-body">
      <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
          <img src="{{ assets('img/uploads/images/img_6861a311506581.80191269.webp') }}" alt="car"
            style="width:100%; border-radius: 10px">
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12">
          <h4 class="text-primary text-left font-inter-extra-bold fs-16">
          {{ trans('land.travel.title.veh') }}</h4>
          <p class="text text-justify">
            {{ trans('land.travel.content.veh.p-1') }}
          </p>
          <p>
          {{ trans('land.travel.content.veh.p-1') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container content-section bg-light mb-4">
  <div class="card-styled">
    <div class="card-styled-body">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h4 class="text-primary text-left font-inter-extra-bold fs-16">
          {{ trans('land.travel.title.trav') }}</h4>
          <p class="text text-justify">
          {{ trans('land.travel.content.trav.p-1') }}
          </p>
          <p>
          {{ trans('land.travel.content.trav.p-2') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container content-section bg-light mb-4">
  <div class="card-styled">
    <div class="card-styled-body px-4 py-4">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h3 class="text-primary text-left font-inter-extra-bold fs-16">
          {{ trans('land.travel.title.trav-kit') }}</h3>
          <p class="text">
          {{ trans('land.travel.content.trav-kit.p-1') }}</p>
          <ul class="font-inter-regular">
            <li>{{ trans('land.travel.ul.li-1') }}</li>
            <li>{{ trans('land.travel.ul.li-2') }}</li>
            <li>{{ trans('land.travel.ul.li-3') }}</li>
            <li>{{ trans('land.travel.ul.li-4') }}</li>
          </ul>
          <p>{{ trans('land.travel.content.trav-kit.p-2') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="container content-section bg-light">
  <div class="card-styled">
    <div class="card-styled-body px-4 py-4">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h3 class="text-primary text-left font-inter-extra-bold fs-16">
          {{ trans('land.travel.title.conc') }}</h3>
          <p class="text">{{ trans('land.travel.content.conc.p') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@include(client.partials.footer)