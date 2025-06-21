@import('app/utils/helpers/helper.php')
@include(partials.head)
        <section id="home" class="content-section">
            <div class="container-fluid p-0">
                <div class="row no-gutters">
                    <div class="col-12">
                        <div id="iview-overlay"
                            style="width:100%;height:100%;position:absolute;top:0;left:0;background-color:rgba(0,0,0,0.4);z-index:1;">
                        </div>
                        <h3 class="text-center text-white display-2 font-inter-extra-bold title-slogan">
                        {{ $language == "es" ? $page['title_es'] : $page['title'] }}
                        </h3>
                        <div id="heroCarousel" class="carousel slide" data-ride="carousel"
                            aria-label="Main Highlights Carousel">

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ assets('img/slides/slide-01-800.webp') }}"
                                        srcset="{{ assets('img/slides/slide-01-480.webp') }} 480w, {{ assets('img/slides/slide-01-800.webp') }} 800w, {{ assets('img/slides/slide-01-1200.webp 1200w') }}, {{ assets('img/slides/slide-01-1920.webp') }} 1920w"
                                        sizes="(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px"
                                        class="carousel-img w-100" alt="Madagascar rainforest landscape" width="1920"
                                        height="700" type="image/webp" decoding="async" fetchpriority="high">
                                </div>
                                
                                <!--div class="carousel-item">
                                    Next slide here
                                </div-->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
            <h2 id="about-heading" class="sr-only">About Madagascar Green Tours</h2>

            <div class="card-styled">
                <div class="card-styled-body">
                    <img src="{{ assets('img/images/lemur.webp') }}" class="float-image desktop-img"
                        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">

                    <h3 class="text-center font-inter-extra-bold fancy text-uppercase">
                        {{ $language == "es" ? $page['title_h1_es'] : $page['title_h1'] }}
                    </h3>
                    <h6 class="text-center font-inter-bold">
                        {{ $language == "es" ? $page['title_h2_es'] : $page['title_h2'] }}
                    </h6>
                        <img src="{{ assets('img/images/lemur.webp') }}" class="float-image mobile-img"
                            alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">

                            
                                {{ $language == "es" ? $page['content_es'] : $page['content'] }}
                            

                </div>
            </div>
        </section>
        <!-- Our Tours Section with Rich Content -->
        <section id="our_tours" class="py-5 content-section">
            <div class="container">
                <h2 class="section-heading">
                    {{ isset($contents[0]) ? ($language == 'es' ? ($contents[0]['val_es'] ?? $contents[0]['val']) : $contents[0]['val']) : 'Our Tours' }}
                </h2>
                <h6 class="section-subtitle">
                {{ isset($contents[1]) ? ($language == 'es' ? ($contents[1]['val_es'] ?? $contents[1]['val']) : $contents[1]['val']) : 'Our Tours' }}
                    
                </h6>
                <center>
                    <div class="line-title"></div>
                </center>
                <div class="row mt-6">

                <?php foreach($tours as $t): ?>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <article class="card__styled card--1">
                            <div class="card__info-hover">
                                <svg class="card__like" viewBox="0 0 24 24">
                                    <path fill="#000000"
                                        d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                </svg>
                                <div class="card__clock-info">
                                    <svg class="card__clock" viewBox="0 0 24 24">
                                        <path
                                            d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                    </svg><span class="card__time">15 days</span>
                                </div>

                            </div>
                            <div class="card__img"
                                style="background-image: url('{{ assets('img/tours/combination-tour.webp') }}');">
                            </div>
                            <a href="{{ route('tours/'.$t->path) }}" class="card_link">
                                <div class="card__img--hover"
                                    style="background-image: url('{{ assets('img/tours/combination-tour.webp') }}');">
                                </div>
                            </a>
                            <div class="card__info">
                                <span class="card__category"></span>
                                <h3 class="card__title">{{ $language == "es" ? $t->name_es : $t->name }}</h3>
                                <p class="card__by"> {{ $language == "es" ? $t->text_for_customer_es : $t->text_for_customer }}
                                    <a href="{{ route('tours/'.$t->path) }}" class="card__author" title="author">Show more</a>
                                </p>
                            </div>
                        </article>
                    </div>

                <?php endforeach ?>

                </div>





            </div>
            <center>

                <a class="btn__styled mt-6" href="{{ route('tours') }}" role="button" aria-label="Show more tours">
                    <span class="text">{{ trans('btn.show-more') }}</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.66669 11.3334L11.3334 4.66669" stroke="white" stroke-width="1.33333"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66669 4.66669H11.3334V11.3334" stroke="white" stroke-width="1.33333"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </center>
        </section>

        <section id="video" class="container">
            <h2 class="section-heading">
                {{ isset($contents[2]) ? ($language == 'es' ? ($contents[2]['val_es'] ?? $contents[2]['val']) : $contents[2]['val']) : 'Videos' }}
            </h2>
            <h6 class="section-subtitle">
            {{ isset($contents[3]) ? ($language == 'es' ? ($contents[3]['val_es'] ?? $contents[3]['val']) : $contents[3]['val']) : 'Videos' }}
        </h6>
            <center>
                <div class="line-title"></div>
            </center>
            <div class="row" style="margin-top: 50px;">
            <?php foreach($videos as $video): ?>
                <div class="col-lg-6">
                    <h5 class="card-title section-heading text-center fontInterRegularExtraBold"
                        style="font-size: 18pt; text-transform: capitalize;">
                        {{ $language == "es" ? $video['title_es'] : $video['title_es'] }}
                    </h5>
                    <p class="text-center fontRegular">
                    {{ $language == "es" ? $video['subtitle_es'] : $video['subtitle_es'] }}
                    
                    </p>
                    <a href="{{ $video['link'] }}" class="text-center" target="_blank"
                        rel="noopener noreferrer">
                        <img src="{{ assets('img/logos/play-youtube.png') }}" alt="youtube" class="youtube-play">
                        <img src="https://img.youtube.com/vi/{{ idYoutubeVideo($video['link']) }}/hqdefault.jpg" style="width: 100%"
                            alt="{{ $video['title_es'] }}" class="img-thumbnail" width="320" height="180">
                    </a>
                </div>
                
            <?php endforeach ?>
            </div>
        </section>
        <section id="services" class="container" style="padding-bottom: 50px;">

        <h2 class="section-heading">
                {{ isset($contents[4]) ? ($language == 'es' ? ($contents[4]['val_es'] ?? $contents[4]['val']) : $contents[4]['val']) : 'Services' }}
            </h2>
            <h6 class="section-subtitle">
            {{ isset($contents[5]) ? ($language == 'es' ? ($contents[5]['val_es'] ?? $contents[5]['val']) : $contents[5 ]['val']) : 'Yout best service' }}
        </h6>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="car-rental-card awesome-card position-relative overflow-hidden rounded shadow-sm">
                        <img src="{{ assets('img/images/car_rental.webp') }}" alt="Car rental" class="car-rental-img w-100"
                            loading="lazy" width="350" height="220" style="object-fit:cover; border-radius:1.2rem;">
                        <div class="car-rental-title-overlay">
                            <h3 class="car-rental-title">Car rental</h3>
                        </div>
                        <div class="car-rental-content p-3 text-center">
                            <a href="#" class="btn btn-success car-rental-btn">Show more</a>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="car-rental-card awesome-card position-relative overflow-hidden rounded shadow-sm">
                        <!-- Consider compressing air_madagascar.jpg to WebP for better performance -->
                        <img src="{{ assets('img/images/flight_booking.webp') }}"
                            alt="Air Madagascar airplane for flight booking" class="car-rental-img w-100" loading="lazy"
                            decoding="async" width="350" height="220" style="object-fit:cover; border-radius:1.2rem;">
                        <div class="car-rental-title-overlay">
                            <h3 class="car-rental-title">Hotel booking</h3>
                        </div>
                        <div class="car-rental-content p-3 text-center">
                            <a href="#" class="btn btn-success car-rental-btn">Show more</a>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="car-rental-card awesome-card position-relative overflow-hidden rounded shadow-sm">
                        <!-- Consider compressing air_madagascar.jpg to WebP for better performance -->
                        <img src="{{ assets('img/images/flight_booking.webp') }}"
                            alt="Air Madagascar airplane for flight booking" class="car-rental-img w-100" loading="lazy"
                            decoding="async" width="350" height="220" style="object-fit:cover; border-radius:1.2rem;">
                        <div class="car-rental-title-overlay">
                            <h3 class="car-rental-title">Flight booking</h3>
                        </div>
                        <div class="car-rental-content p-3 text-center">
                            <a href="#" class="btn btn-success car-rental-btn">Show more</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section id="testimonials" class="container py-5" aria-labelledby="reviews-heading">
            <h2 id="reviews-heading" class="sr-only">Customer Reviews</h2>
            
        <h2 class="section-heading">
                {{ isset($contents[6]) ? ($language == 'es' ? ($contents[6]['val_es'] ?? $contents[6]['val']) : $contents[6]['val']) : 'Reviews' }}
            </h2>
            <h6 class="section-subtitle">
            {{ isset($contents[7]) ? ($language == 'es' ? ($contents[7]['val_es'] ?? $contents[7]['val']) : $contents[7]['val']) : 'The review of your customer' }}
        </h6>
            <!-- Reviews List -->
            <div id="reviewsList">
                <!-- Example Review Post -->
                <div class="review-post card shadow-sm mb-3 p-3">
                    <div class="d-flex align-items-center mb-2">
                        <!-- Consider compressing user.png to WebP for better performance -->
                        <img src="{{ assets('img/images/user.png') }}" alt="User avatar icon" width="64" height="64" class="review-avatar me-2" loading="lazy" decoding="async">
                        <div class="review-user-content">
                            <div class="fw-bold">John Doe</div>
                            <div class="review-stars">
                                <span class="star filled">&#9733;</span>
                                <span class="star filled">&#9733;</span>
                                <span class="star filled">&#9733;</span>
                                <span class="star filled">&#9733;</span>
                                <span class="star">&#9733;</span>
                            </div>
                            <div class="text-muted small">Posted on June 11, 2025</div>
                        </div>
                    </div>
                    <div class="review-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis excepturi itaque eum,
                        cupiditate similique omnis, fugit at placeat, quos quibusdam maxime iure nihil? Officia
                        necessitatibus nemo ad accusantium ea error!
                    </div>
                </div>
        </section>

        <section id="gallery" class="container py-5" aria-labelledby="gallery-heading">
            <h2 id="gallery-heading" class="sr-only">Customer Reviews</h2>
            <h2 class="section-heading">Gallery</h2>
            <h6 class="section-subtitle">Explore Madagascar's natural beauty through our stunning photos and videos</h6>

            <div id="lightgallery" class="row">
                <a href="https://img.youtube.com/vi/W0joop_UvCM/hqdefault.jpg" data-sub-html=""
                    class="gallery-card col-lg-3 col-md-3 col-sm-6 col-xs-6 mb-3 px-2">
                    <img class="gallery-thumb" src="https://img.youtube.com/vi/W0joop_UvCM/hqdefault.jpg" alt="Gallery" loading="lazy" width="320" height="180">
                    <span class="gallery-hover-icon" aria-hidden="true">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="rgba(25,135,84,0.7)" />
                            <path
                                d="M15.5 15.5L13.5 13.5M14.5 10.5C14.5 12.1569 13.1569 13.5 11.5 13.5C9.84315 13.5 8.5 12.1569 8.5 10.5C8.5 8.84315 9.84315 7.5 11.5 7.5C13.1569 7.5 14.5 8.84315 14.5 10.5Z"
                                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </a>
                <a href="https://img.youtube.com/vi/W0joop_UvCM/hqdefault.jpg" data-sub-html=""
                    class="gallery-card col-lg-3 col-md-3 col-sm-6 col-xs-6 mb-3 px-2">
                    <img class="gallery-thumb" src="https://img.youtube.com/vi/W0joop_UvCM/hqdefault.jpg" alt="Gallery" loading="lazy" width="320" height="180">
                    <span class="gallery-hover-icon" aria-hidden="true">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="rgba(25,135,84,0.7)" />
                            <path
                                d="M15.5 15.5L13.5 13.5M14.5 10.5C14.5 12.1569 13.1569 13.5 11.5 13.5C9.84315 13.5 8.5 12.1569 8.5 10.5C8.5 8.84315 9.84315 7.5 11.5 7.5C13.1569 7.5 14.5 8.84315 14.5 10.5Z"
                                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </a>
            </div>
        </section>
    @include(partials.footer)
