@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<section class="content-section">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-12">
        <div>
          <div class="bottom_inside_divider"></div>
        </div>
        <img src="{{ assets('img/images/cover-beach.jpg') }}" style="width: 100%" alt="Tours image">
      </div>
    </div>
  </div>
</section>
<section class="container">
  <div class="card-styled">
    <div class="card-body">
      <img src="{{ assets('img/images/nosy-be.jpg') }}" class="float-image desktop-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">

      <h1 class="text-center text-primary font-inter-extra-bold fs-28">
        {{ $language == "es" ? $page->title_es : $page->title }}</h1>

      <img src="{{ assets('img/images/nosy-be.jpg') }}" class="float-image mobile-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
      {{ $language == "es" ? $page->content_es : $page->content }}
      <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
      
      <h3 class="text-primary font-inter-extra-bold fs-28">
        {{ trans('land.nosy.title.why') }}
      </h3>
      <p>{{ trans('land.nosy.content.why.p-1') }}</p>
      <p>{{ trans('land.nosy.content.why.p-2') }}</p>
      <ul class="font-inter-regular">
        <?php for($i = 1; $i <= 5; $i++): ?>
        <li>
          <span class="font-inter-bold">
            {{ trans('land.nosy.sub.title-'.$i) }}
          </span>
          {{ trans('land.nosy.ul.why.li-'.$i) }}  
        </li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
</section>

<section class="container">
  <div class="movie_card mb-4 mt-2" id="tomb" style="background-image: url('{{ assets('img/images/baobab.jpg') }}')">
    <div class="info_section">
      <div class="movie_header">
        <h3 class="text-left font-inter-extra-bold text-primary">
          {{ trans('land.nosy.title.more') }}
        </h3>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
          
        {{ trans('land.nosy.content.more.p-1') }}
        </p>
        <p class="text-justify">
          
        {{ trans('land.nosy.content.more.p-1') }}
        </p>

      </div>
    </div>
    <div class="blur_back nosy_back"></div>
  </div>
</section>
<section class="container mt-2">
  <div class="card card-styled">
    <div class="card-body">
      <h3 class="section-heading mb-2">
      {{ trans('land.nosy.title.book') }}
      </h3>
      <p class="text-center">
      {{ trans('land.nosy.content.book.p-1') }}</p>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/tour-guide.png') }}" alt="guide icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.nosy.ul.book.li-1') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/map.png') }}" alt="map icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.nosy.ul.book.li-2') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/group.png') }}" alt="users icon">
              </div>
              <div class="card-text">
                <p class="text-center">{{ trans('land.nosy.ul.book.li-3') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="mt-2 text-center">
        {{ trans('land.nosy.content.book.p-2') }} 
        <a href="{{ route('tours/nosy_be_wonders_tour') }}" class="text-primary">
          Nosy Be Wonders Tour.
        </a>
      </p>
      <p class="text-justify">
        {{ trans('land.nosy.content.book.p-3') }}
      </p>
    </div>
  </div>
</section>
@include(client.partials.footer)