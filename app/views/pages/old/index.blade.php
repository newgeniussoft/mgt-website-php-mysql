@import('app/utils/helpers/helper.php')
@include(partials.head)
<body class="home page-template-default page page-id-108  footer-static header-2">
    <div id="wrapper">
        @include(partials.header)
        <div class="slide clearfix" id="wrapper-content">
            <main role="main" class="site-content-page">
                <div class="site-content-page-inner">
                    <div class="page-content">
                        <h3 class="iview-title fontInterRegularExtraBold text-center">{{ trans('slogan') }}</h3>
                        <div class="iview-overlay"></div>
                        <div id="iview">
                            @for($i = 1; $i < 8; $i++)
                            <div 
                                data-iview:thumbnail="{{ assets('img/slide/slide-0'.$i.'.jpg') }}"
                                data-iview:image="{{ assets('img/slide/slide-0'.$i.'.jpg') }}">
                            </div>
                            @endfor
                        </div>
                    </div>
              </div>
            </main>
        </div>
        <section class="container block-home">
            <div class="card card-main">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                            <img src="{{ assets('img/images/lemur.jpg') }}" alt="Lemur">
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                            <h3 class="fontRegular mb-4 text-uppercase text-center">
                                {{ trans('welcome-to') }} Madagascar Green Tours
                            </h3>
                            <h3 class="card-title text-center mb-0">
                                {{ trans('home.subtitle-1') }}
                            </h3>
                            <h4 class="text-center mt-2 mb-4">{{ trans('home.subtitle-2') }}</h4>
                            <center>
                                <div style="height: 3px; background: #4a5568; width: 140px; margin-top:16px; margin-bottom: 16px"></div>

                            </center>
                              <p class="text-justify fs-13">
                                  {{ trans('home.p1') }}
                              </p>
                              <p class="text-justify fs-13">
                                  {{ trans('home.p2') }}
                              </p>
                              <p class="text-justify fs-13">
                                  {{ trans('home.p3') }}
                              </p>
                              <h4 class="text-center">{{ trans('home.sub1') }}</h4>
                        </div>
                    </div>
                </div>
              </div>
        </section>
        <section class="px-2 mt-6">
            <div class="two alt-two">
                <h3 class="title">{{ trans('menu.our-tours') }}
                    <span>{{ trans('title.tour.sub') }}</span>
                </h3>
            </div>
            <div class="container">
                <div class="row mt-6 mb-4">
                    @foreach($tours as $tour)
                    @if($tour->id_tour == 14 || $tour->id_tour == 13)
                    <div class="col-lg-6 col-md-12 col-sm-12"> 
                        @else
                        <div class="col-lg-4 col-md-6 col-sm-12"> 

                    @endif
                        
                    @if($tour->id_tour == 14 ||  $tour->id_tour == 13)
                        <h3 class="card-title text-uppercase text-center mb-0">
                            {{ $tour->id_tour == 13 ? trans('most-b-tours') : 'A spectacular new tour' }}
                        </h3><center>
                            <div style="height: 3px; background: #4a5568; width: 140px; margin-top:16px; margin-bottom: 16px"></div>

                        </center>
                        @endif
                        <div class="">
                            <img class="card-img-top" src="{{ assets('assets'.$tour->image ) }}" alt="{{ $tour->name }}">
                            <div class="card-body card-body-comment px-0">
                                
                                <div style="max-height: 150px; overflow-y: hidden">
                                <h5 class="card-title text-center fontInterRegularExtraBold"
                                    style="font-size: 18pt; text-transform: capitalize;">
                                    {{ strtolower($LANG == "en" ? $tour->name : $tour->name_es) }}
                                </h5>
                                    <p class="card-text fontInterRegular text-justify">
                                        {{ $LANG == "en" ? $tour->desc_no_format : $tour->desc_no_format_es }}
                                       
                                       
                                    </p>

                                    <span class="hidden-mobile" style="position: absolute; bottom:62px; background: #ffffff; right:12px">
                                        ... <a href="{{ assets('tours/'.strtolower(str_replace(' ', '_', $tour->name))) }}">{{ trans('btn.more') }}</a>
    
                                    </span>
                                </div>
                                <div class="text-center">
                                    <a href="{{ assets('tours/'.strtolower(str_replace(' ', '_', $tour->name))) }}" class="btn-effect">
                                        {{ trans('btn.show') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                                <h5 class="card-title text-center fontInterRegularExtraBold"
                                    style="font-size: 18pt; text-transform: capitalize;">
                                    Adventure tours 28 days<br>
                                </h5>
                                    <p class="text-center fontRegular">Thanks to Benoit Maheux Family for capturing video during the trip with Madagascar Green Tours!</p>
                    <iframe style="width: 100%" height="340"
src="https://www.youtube.com/embed/rEmaEklG0Ek">
</iframe>
                    
                </div>
                <div class="col-lg-6">
                                <h5 class="card-title text-center fontInterRegularExtraBold"
                                    style="font-size: 18pt; text-transform: capitalize;">
                                    Combination tours<br>
                                </h5>
                                    <p class="text-center fontRegular">Gracias a Gorka Ferrer por capturar vídeo durante el viaje con Madagascar Green Tours</p>
                    <iframe style="width: 100%" height="340"
src="https://www.youtube.com/embed/W0joop_UvCM">
</iframe>
                    
                </div>
            </div>
            
        </section>
        <div class="services">
            <div class="container">
                <div class="two alt-two">
                    <h3 class="title">{{ trans('title.our-serv') }}
                        <span></span>
                    </h3>
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="n_card">
            
                                    <h2 style="z-index: 30">{{ trans('menu.car-rental') }}</h2>
                                    <p></p>
                                    <div class="pic"
                                        style="background-image: url('{{ assets('img/images/CAR04.jpg') }}')"></div>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <button>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="n_card card2">
                                    <h2 class="fontRegular" style="z-index: 30">{{ trans('title.hotel-b') }}</h2>
                                    <p></p>
                                    <div class="pic" style="background-image: url('{{ assets('img/images/hotel-booking-350.jpg') }}')">
                                    </div>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <button>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                              <div class="n_card">
                                  <h2 class="fontRegular" style="z-index: 30">{{ trans('title.flight-b') }}</h2>
                                  <p></p>
                                  <div class="pic"
                                      style="background-image: url('{{ assets('img/images/air_madagascar.jpg') }}')"></div>
                                  <ul>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                      <li></li>
                                  </ul>
                                  <button>
                                  </button>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
        
            </div>
        </div>
        <section>
            <div class="container d-flex justify-content-center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="two alt-two mb-4">
                            <h3 class="title">{{ trans('menu.reviews') }}
                                <span>{{ trans('title.sub.review') }}</span>
                            </h3>
                        </div>
              
                      <div class="card">
                                          <div class="card-body">
                                              <h1 style="display: none;">Madagascar Tours: Explore Nature's Unique Wonders</h1>
                                              <h2 style="display: none;">Explore Unforgettable Madagascar Tours Today!</h2>
              
                                          <div class="comment-widgets m-b-20">
              
                                            @foreach($reviews  as $review)
                                              <div class="d-flex flex-row comment-row">
                                                  <div class="p-2">
                                                    <span class="round">
                                                        <img src="{{ assets('img/images/av.png') }}" alt="user" width="50">
                                                    </span>
                                                </div>
                                                  <div class="comment-text fontRegular mt-2 w-100">
                                                      <h5 class="fontRegular">
                                                          <a href="mailto:{{ $review->email_user }}">{{ $review->name_user }}</a>
                                                      <!--@if($review->email_user != "bbaker@gmx.de")
                                                      | <a href="mailto:{{ $review->email_user }}">{{ $review->email_user }}</a></h5>
                                                      
                                                        @endif-->
                                                      <div class="comment-footer">
                                                        <span>
                                        @if($review->rating == 0)
                                          @for($i = 0; $i < 5; $i++)
                                            <img style="" src="{{ assets('img/icons/star4.png') }}" width="15" alt="star">
                                          @endfor
                                        @else
                                        @for($i = 0; $i < 5; $i++)
                                            <img src="{{ assets('img/icons/star4.png') }}" {{ $i < $review->rating ? '' : "style='filter: grayscale(100%);'" }} width="15" alt="star">
                                        @endfor
                                        @endif
                                                        </span>
                                                      </div>
                                                      <p class=" m-t-10 mb-0 text-justify">
                                                        {{ $review->message }}
                                                      </p>
                                                      <span class="date">{{ trans('btn.shared')." ". $review->daty }}</span>
                                                  </div>
                                              </div>
                                              @endforeach
                                              <div class="text-right">
                                                <a href="{{ assets('reviews') }}" class="btn-effect mt-2 mb-4">
                                                    {{ trans('btn.show-more') }} 
                                                </a>
                                              </div>
                                          </div>
                                      </div>
              
                    </div>
                </div>
              </div>
        </section>
        
        <section class="container px-4">
            <div class="two alt-two">
                <h3 class="title">{{ trans('title.gallery') }}
                    <span></span>
                </h3>
            </div>
            <div class="row mt-4" id="aniimated-thumbnials">
                @for($i = 1; $i < 45; $i++)
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 mb-3 px-2">
                    <a href="{{ assets('img/gallery/'.$i.'.jpg') }}"  data-sub-html="">
                        <img class="thumbnials" src="{{ assets('img/gallery/'.$i.'-thumbnails.jpg') }}" alt="Gallery">
                        <img class="img-responsive thumbnail" alt="gallery Madagascar green tours" style="display: none;" src="{{ assets('img/gallery/'.$i.'-thumbnails.jpg') }}">
                    </a>
                </div>
                @endfor
            </div>
        </section>
    </div>
    

@include(partials.script)

</body>
</html>