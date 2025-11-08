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
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
  <div class="card-styled">
    <div class="card-styled-body">
      <h3 class="text-center font-inter-extra-bold fancy text-uppercase mt-1 mb-3">
        {{ $language == "es" ? $page->title_h1_es : $page->title_h1 }}
      </h3>
      <div class="text-center px-4">
      {{ $language == "es" ? $page->content_es : $page->content }}

      </div>
      <h4 class="section-heading mt-4 mb-3">{{ trans('land.mada.title.why') }}</h4>
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card-effect">
            <div class="card-inner">
              <div class="card__liquid"></div>
              <div class="card__shine"></div>
              <div class="card__glow"></div>
              <div class="card__content">
                <div class="text-center">
                  <img src="{{ assets('img/logos/star.png') }}" alt="Star" style="width:100px">
                </div>
                <div class="card__text">
                  <h6 class="card__headline font-inter-bold text-center">
                    {{ trans('land.mada.subtitle.why.exp') }}
                  </h6>
                  <p class="card__description">
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.mada.ul.why.li-1') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-2') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-3') }}</li>
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
              <div class="card__liquid"></div>
              <div class="card__shine"></div>
              <div class="card__glow"></div>
              <div class="card__content">
                <div class="text-center">
                  <img src="{{ assets('img/logos/shield.png') }}" alt="Star" style="width:100px">
                </div>
                <div class="card__text">
                  <h6 class="card__headline font-inter-bold text-center">
                    {{ trans('land.mada.subtitle.why.safe') }}
                  </h6>
                  <p class="card__description">
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.mada.ul.why.li-4') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-5') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-6') }}</li>
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
              <div class="card__liquid"></div>
              <div class="card__shine"></div>
              <div class="card__glow"></div>
              <div class="card__content">
                <div class="text-center">
                  <img src="{{ assets('img/logos/compass.png') }}" alt="Star" style="width:100px">
                </div>
                <div class="card__text">
                  <h6 class="card__headline font-inter-bold text-center">
                    {{ trans('land.mada.subtitle.why.auth') }}
                  </h6>
                  <p class="card__description">
                  <ul class="font-inter-regular">
                    <li>{{ trans('land.mada.ul.why.li-7') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-8') }}</li>
                    <li>{{ trans('land.mada.ul.why.li-9') }}</li>
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
  <div class="movie_card mb-4 mt-2" id="tomb" style="background-image: url('{{ assets('img/images/baobab.jpg') }}')">
    <div class="info_section">
      <div class="movie_header">
        <h5 class="text-left fontInterRegularExtraBold">{{ trans('land.mada.title.travel') }}</h5>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
          {{ trans('land.mada.content.travel.p-1') }}
        </p>
        <ul class="font-inter-regular">
          <li>{{ trans('land.mada.ul.travel.li-1') }}</li>
          <li>{{ trans('land.mada.ul.travel.li-2') }}</li>
        </ul>
      </div>
    </div>
    <div class="blur_back tomb_back"></div>
  </div>
  <div class="card-styled">
    <div class="card-styled-body">
      <h4 class="section-heading">
        {{ trans('land.mada.title.commit') }}
      </h4>
      <div class="row mt-4">
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card mb-4 ">
            <div class="card-body __content">
              <div class="div1" style=" align-items: center; justify-content: center;">
                <img src="{{ assets('img/logos/sofa.png') }}"
                  style="width: 60px; margin: 0 auto; background:#fcfcfc; padding: 10px; border: solid 1px #f4f4f4  "
                  alt="Couch">
              </div>
              <div class="div2">
                <h5 class="card-title">{{ trans('land.mada.subtitle.commit.comf') }}</h5>
                <p class="card-text text-left">
                  {{ trans('land.mada.content.commit.p-1') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card mb-2">
            <div class="card-body __content">
              <div class="div1" style=" align-items: center; justify-content: center;">
                <img src="{{ assets('img/logos/wave.png') }}"
                  style="width: 60px; margin: 0 auto; background:#fcfcfc; padding: 10px; border: solid 1px #f4f4f4"
                  alt="Couch">
              </div>
              <div class="div2">
                <h5 class="card-title">{{ trans('land.mada.subtitle.commit.flex') }}</h5>
                <p class="card-text text-left">
                  {{ trans('land.mada.content.commit.p-2') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card mb-2">
            <div class="card-body __content">
              <div class="div1" style=" align-items: center; justify-content: center;">
                <img src="{{ assets('img/logos/check.png') }}"
                  style="width: 60px; margin: 0 auto; background:#fcfcfc; padding: 10px; border: solid 1px #f4f4f4"
                  alt="Couch">
              </div>
              <div class="div2">
                <h5 class="card-title">{{ trans('land.mada.subtitle.commit.satisf') }}</h5>
                <p class="card-text text-left">
                  {{ trans('land.mada.content.commit.p-3') }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="container mt-4">
  <div class="card-styled">
    <div class="card-styled-body">
      <img src="{{ assets('img/images/baobab1.jpg') }}" alt="Baobab"
        style="width: 350px;float: right; margin-left: 12px">
      <h5 class="section-heading mb-4" style="font-size:22pt">
        {{ trans('land.mada.title.tours') }}
      </h5>

      <p>
        {{ trans('land.mada.content.tours.p-1') }}
      </p>
      <ul class="font-inter-regular">
        <li>{{ trans('land.mada.ul.tours.li-1') }}</li>
        <li>{{ trans('land.mada.ul.tours.li-2') }}</li>
        <li>{{ trans('land.mada.ul.tours.li-3') }}</li>
      </ul>
      <p>
        {{ trans('land.mada.content.tours.p-2') }}
      </p>
      <img src="{{ assets('img/images/shape-6.webp') }}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
    </div>
  </div>

</section>
<section class="container mt-4">
  <div class="card-styled">
    <div class="card-styled-body">

      <img src="{{ assets('img/images/lemur.jpeg') }}" alt="Lemur" style="width: 250px;float: left; margin-right: 12px">
      <h4 class="section-heading" style="font-size:24pt">
        {{ trans('land.mada.title.exp') }}
      </h4>
      <p class="mt-2">
        {{ trans('land.mada.content.exp.p-1') }}
      </p>
      <p>{{ trans('land.mada.content.exp.p-2') }}</p>
      <center>

        <a class="btn__styled mt-2" style="margin-top: 10px " href="https://wa.me/261347107100?text=Hello" role="button"
          aria-label="Show more tours">
          <span class="text">{{ trans('btn.book') }}</span>
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.66669 11.3334L11.3334 4.66669" stroke="white" stroke-width="1.33333" stroke-linecap="round"
              stroke-linejoin="round" />
            <path d="M4.66669 4.66669H11.3334V11.3334" stroke="white" stroke-width="1.33333" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </a>
      </center>
      <img src="{{ assets('img/images/shape-6.webp') }}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
    </div>
  </div>

</section>
@include(client.partials.footer)