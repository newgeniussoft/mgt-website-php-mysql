@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<section id="home" class="content-section">
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
<section class="container content-section bg-light">
  <div class="movie_card mb-4 mt-0" id="tomb" style="background-image: url('{{ assets('img/images/baobab.jpg') }}')">
    <div class="info_section">
      <div class="movie_header">
        <h1 class="text-left font-inter-extra-bold text-primary mb-0 fs-32">
          {{ $language == "es" ? $page->title_es : $page->title }}
        </h1>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
          {{ $language == "es" ? $page->content_es : $page->content }}
        </p>
      </div>
    </div>
    <div class="blur_back tomb_back"></div>
  </div>
  <div class="card-styled">
    <div class="card-styled-body">
      <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
          <img src="{{ assets('img/images/hotel.jpg') }}" alt="hotel" style="width:100%; border-radius: 10px">
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">
          <h4 class="text-primary text-left font-inter-extra-bold fs-16">{{ trans('land.incoming.title.why') }}</h4>
          <p>{{ trans('land.incoming.content.why.p-1') }}</p>
          <ul class="font-inter-regular list-checked">
            <li>{{ trans('land.incoming.ul.why.li-1') }}</li>
            <li>{{ trans('land.incoming.ul.why.li-2') }}</li>
            <li>{{ trans('land.incoming.ul.why.li-3') }}</li>
            <li>{{ trans('land.incoming.ul.why.li-4') }}</li>
          </ul>
          <p>{{ trans('land.incoming.content.why.p-2') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="container mt-2">
  <div class="card-styled">
    <div class="card-styled-body">
      <h4 class="section-heading mt-4 mb-3">{{ trans('land.incoming.title.services') }}</h4>
      <p class="text-center">{{ trans('land.incoming.content.services.p') }}</p>
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card-effect">
            <div class="card-inner">
              <div class="card__content px-1 py-1">
                <div>
                  <img src="{{ assets('img/images/window_isalo.jpg') }}" alt="Aeroport"
                    style="width:100%; border: solid 4px #f5f5f5">
                </div>
                <div class="card__text px-4">
                  <h3 class="card__headline font-inter-bold text-center">
                    {{ trans('land.incoming.subtitle.org') }}
                  </h3>
                  <p class="card__description">
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.incoming.ul.org.li-1') }}</li>
                    <li>{{ trans('land.incoming.ul.org.li-2') }}</li>
                    <li>{{ trans('land.incoming.ul.org.li-3') }}</li>
                  </ul>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card-effect">
            <div class="card-inner">
              <div class="card__content px-1 py-1">
                <div>
                  <img src="{{ assets('img/images/beach.jpg') }}" alt="Aeroport"
                    style="width:100%; border: solid 4px #f5f5f5">
                </div>
                <div class="card__text px-4">
                  <h3 class="card__headline font-inter-bold text-center">
                    {{ trans('land.incoming.subtitle.tailor') }}
                  </h3>
                  <p class="card__description">
                    {{ trans('land.incoming.content.tailor.p') }}
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.incoming.ul.tailor.li-1') }}</li>
                    <li>{{ trans('land.incoming.ul.tailor.li-2') }}</li>
                    <li>{{ trans('land.incoming.ul.tailor.li-3') }}</li>
                  </ul>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card-effect">
            <div class="card-inner">
              <div class="card__content px-1 py-1">
                <div>
                  <img src="{{ assets('img/images/arrival_client.jpg') }}" alt="Aeroport"
                    style="width:100%; border: solid 4px #f5f5f5">
                </div>
                <div class="card__text px-4">
                  <h3 class="card__headline font-inter-bold text-center">
                    {{ trans('land.incoming.subtitle.full') }}
                  </h3>
                  <p class="card__description">
                    {{ trans('land.incoming.content.full.p') }}
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.incoming.ul.full.li-1') }}</li>
                    <li>{{ trans('land.incoming.ul.full.li-2') }}</li>
                    <li>{{ trans('land.incoming.ul.full.li-3') }}</li>
                  </ul>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
    </div>
  </div>
</section>
<section class="container mt-2">
  <div class="card card-styled">
    <div class="card-body">
      <h3 class="section-heading mb-2">{{ trans('land.incoming.title.why-trust') }}</h3>
      <p class="text-center">{{ trans('land.incoming.content.why-trust.p') }}</p>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/travel-agency.png') }}" alt="Travel icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.incoming.ul.why-trust.li-1') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/insurance.png') }}" alt="Travel icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.incoming.ul.why-trust.li-2') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/map.png') }}" alt="map icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.incoming.ul.why-trust.li-3') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon px-0">
              <div class="icon">
                <img src="{{ assets('img/logos/tour-guide.png') }}" alt="tour-guide icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.incoming.ul.why-trust.li-4') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="contact" class="container mt-2">
  <div class="card-styled">
    <div class="card-styled-body pb-2">
      <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
          <img src="{{ assets('img/images/isalo.jpg') }}" alt="hotel" style="width:100%; border-radius: 10px">
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">
          <h2 class="text-primary tex font-inter-extra-bold fs-32 mb-0">Contact us</h2>
          <p class="mb-1">{{ trans('land.incoming.content.contact.p') }} <br>
          </p>
          <a target="_blank" href="https://wa.me/261347107100?text=Hello" class="btn btn-primary btn-sm font-inter-regular">
            {{ trans('btn.contact-wa') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@include(client.partials.footer)