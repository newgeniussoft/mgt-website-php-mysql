@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)<section class="content-section">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-12">
        <img src="{{ assets('img/images/sea.jpg') }}" style="width: 100%" alt="Tours image">
      </div>
    </div>
  </div>
</section>
<section class="container mb-4" style="margin-top: -90px;">
  <div class="card-styled">
    <div class="card-styled-body px-4">
    <h3 class="text-center font-inter-extra-bold fancy text-uppercase mt-1 mb-3">
        {{ $language == "es" ? $page->title_h1_es : $page->title_h1 }}
      </h3>
      <div class="text-center px-4">
      {{ $language == "es" ? $page->content_es : $page->content }}
      </div>
    </div>
  </div>
</section>
<section class="container">
  <div class="movie_card mb-4 mt-2" id="tomb">
    <div class="info_section">
      <div class="movie_header">
        <h3 class="text-left font-inter-extra-bold text-primary">
          {{ trans('land.local.title.why') }}
        </h3>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
          {{ trans('land.local.content.why.p-1') }}
        </p>
        <p class="text-justify">
          {{ trans('land.local.content.why.p-2') }}
        </p>
      </div>
    </div>
    <div class="blur_back nosy_back"></div>
  </div>
</section>
<section class="container mt-2">
  <div class="card card-styled">
    <div class="card-body">
      <h3 class="section-heading mb-2">{{ trans('land.local.title.exp') }}</h3>
      <p class="text-center">{{ trans('land.local.content.exp.p') }}</p>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/lemure.png') }}" alt="lemure icon">
              </div>
              <div class="card-text">
                <h4 class="font-inter-bold text-primary">{{ trans('land.local.subtitle.wild') }}</h4>
                <p class="text-center">
                  {{ trans('land.local.content.exp.wild') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/hill.png') }}" alt="Travel icon">
              </div>
              <div class="card-text">
                <h4 class="font-inter-bold text-primary">{{ trans('land.local.subtitle.adv') }}</h4>
                <p class="text-center">
                  {{ trans('land.local.content.exp.adv') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/vacations.png') }}" alt="vacations icon">
              </div>
              <div class="card-text">
                <h5 class="font-inter-bold text-primary">{{ trans('land.local.subtitle.beach') }}</h5>
                <p class="text-center">
                  {{ trans('land.local.content.exp.beach') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/finish.png') }}" alt="Flag icon">
              </div>
              <div class="card-text">
                <h5 class="font-inter-bold text-primary">{{ trans('land.local.subtitle.cult') }}</h5>
                <p class="text-center">
                  {{ trans('land.local.content.exp.cult') }}
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
  <div class="row">
    <div class="col-lg-6 col-md-6 col-12">
      <div class="card-effect">
        <div class="card-inner">
          <div class="card__content px-1 py-1">
            <div>
              <img src="{{ assets('img/images/window_isalo.jpg') }}" alt="isalo"
                style="width:100%; border: solid 4px #f5f5f5">
            </div>
            <div class="card__text px-4">
              <h3 class=" fs-30 text-primary font-inter-bold text-center">
                {{ trans('land.local.title.pers') }}
              </h3>
              <p class="text-justify">
              {{ trans('land.local.content.pers.p-1') }}
              </p>
              <p class="text-justify">
              {{ trans('land.local.content.pers.p-2') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-12">
      <div class="card-effect">
        <div class="card-inner">
          <div class="card__content px-1 py-1" style="gap: 0">
            <div>
              <img src="{{ assets('img/images/beach.jpg') }}" alt="Beach" style="width:100%; border: solid 4px #f5f5f5">
            </div>
            <div class="card__text px-4">
              <h3 class=" fs-20 text-primary mt-0 font-inter-bold text-center mb-1">
                {{ trans('land.local.title.sust') }}
              </h3>
              <p class="text-justify mb-1">
                {{ trans('land.local.content.sust.p-1') }}
              </p>
              <p class="text-justify">
                {{ trans('land.local.content.sust.p-2') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container mt-4">
  <div class="card-styled">
    <div class="card-styled-body px-4">
      <h3 class="text-center font-inter-extra-bold text-primary">
        {{ trans('land.local.title.plan') }}
      </h3>
      <p>{{ trans('land.local.content.plan.p-1') }}</p>
      <p>{{ trans('land.local.content.plan.p-2') }}</p>
      <p>{{ trans('land.local.content.plan.p-3') }}</p>
      <p>{{ trans('land.local.content.plan.p-4') }} <a href="{{ route('contact') }}" class="text-primary"> Madagascar Green Tours team.</a>
      </p>
    </div>
  </div>
</section>
@include(client.partials.footer)