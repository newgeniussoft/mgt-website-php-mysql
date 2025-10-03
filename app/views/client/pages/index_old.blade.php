@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">
    {{ $language == "es" ? "Turismo responsable en Madagascar: Viaja con conciencia" : "Madagascar Tours: Explore Nature's Unique Wonders" }}
</h1>
<h2 class="sr-only">
    {{ $language == "es" ? "Turismo responsable en Madagascar: una guía esencial" : "Explore Unforgettable Madagascar Tours Today!" }}
</h2>
<section id="home" class="content-section">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-12">
                <div id="iview-overlay">
                    <div class="bottom_inside_divider"></div>
                </div>
                <h3 class="text-center text-white display-2 font-inter-bold title-slogan">
                    {{ $language == "es" ? $page->title_es : $page->title }}
                </h3>
                <div id="heroCarousel" class="carousel slide" data-ride="carousel" aria-label="Main Highlights Carousel">
                    <div class="carousel-inner">
                        <?php
                            $pos = 0;
                            foreach($slides as $slide):
                        ?>
                        <div class="carousel-item {{ $pos == 0 ? 'active' : ''}}">
                            <img src="{{ assets($slide->image) }}"
                                srcset="{{ assets($slide->image) }} 480w, {{ assets($slide->image) }} 800w, {{ assets($slide->image) }} 1200w, {{ assets($slide->image) }} 1920w"
                                sizes="(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px"
                                class="carousel-img w-100" alt="Madagascar rainforest landscape" width="1920"
                                height="700" type="image/webp" decoding="async" fetchpriority="high">
                        </div>
                        <?php 
                            $pos += 1;
                            endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
    <div class="card-styled">
        <div class="card-styled-body">
            <img src="{{ assets($info->image) }}" class="float-image desktop-img" alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
            <h3 class="text-center font-inter-extra-bold text-primary">
                {{ $language == "es" ? $page->title_h1_es : $page->title_h1 }}
            </h3>
            <img src="{{ assets($info->image) }}" class="float-image mobile-img"
                alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
            {{ $language == "es" ? $page->content_es : $page->content }}
            <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
        </div>
    </div>
</section>
<!-- Our Tours Section with Rich Content -->
<section id="our_tours" class="py-5 content-section">
    <div class="container " data-aos="fade-up">
        <h2 class="section-heading " data-aos="fade-in">
            {{ isset($contents[0]) ? ($language == 'es' ? ($contents[0]->val_es ?? $contents[0]->val) : $contents[0]->val) : 'Our Tours' }}
        </h2>
        <h6 class="section-subtitle">
            {{ isset($contents[1]) ? ($language == 'es' ? ($contents[1]->val_es ?? $contents[1]->val) : $contents[1]->val) : 'Our Tours' }}
        </h6>
        <div class="row mt-6">

            <?php foreach($tours_home as $t): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4" data-aos="fade-up">
                <article class="card__styled card--1">
                    <div class="card__info-hover">
                    </div>
                    <div class="card__img" style="background-image: url('{{ assets($t->image) }}');">
                    </div>
                    <a href="{{ route('tours/'.$t->path) }}" class="card_link">
                        <div class="card__img--hover" style="background-image: url('{{ assets($t->image) }}');">
                        </div>
                    </a>
                    <div class="card__info">
                        <span class="card__category"></span>
                        <h3 class="card__title">{{ $language == "es" ? $t->name_es : $t->name }}</h3>
                        <p class="card__by"> {{ $language == "es" ? $t->text_for_customer_es : $t->text_for_customer }}
                            <br>

                        </p>
                        <a href="{{ route('tours/'.$t->path) }}" class="card__author" title="author">
                            {{  trans('btn.show-more') }}
                        </a>
                    </div>
                </article>
            </div>
            <?php endforeach ?>
        </div>
    </div>
    <center>
        <a class="btn__styled mt-6" data-aos="fade-up" href="{{ route('tours') }}" role="button"
            aria-label="Show more tours">
            <span class="text">{{ trans('btn.show-more') }}</span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.66669 11.3334L11.3334 4.66669" stroke="white" stroke-width="1.33333" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M4.66669 4.66669H11.3334V11.3334" stroke="white" stroke-width="1.33333" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </a>
    </center>
</section>

<section id="video" class="container">
    <h2 class="section-heading " data-aos="fade-up">
        {{ isset($contents[2]) ? ($language == 'es' ? ($contents[2]->val_es ?? $contents[2]->val) : $contents[2]->val) : 'Videos' }}
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ isset($contents[3]) ? ($language == 'es' ? ($contents[3]->val_es ?? $contents[3]->val) : $contents[3]->val) : 'Videos' }}
    </h6>
                            
    <div class="row" style="margin-top: 50px;">
        <?php foreach($videos as $video): ?>
        <div class="col-lg-6" data-aos="fade-up">
            <h5 class="card-title section-heading text-center fontInterRegularExtraBold"
                style="font-size: 18pt; text-transform: capitalize;">
                {{ $language == "es" ? $video->title_es : $video->title }}
            </h5>
            <p class="text-center fontRegular">
                {{ $language == "es" ? $video->subtitle_es : $video->subtitle }}

            </p>
            <a href="{{ $video->link }}" class="text-center" target="_blank" rel="noopener noreferrer">
                <img src="{{ assets('img/logos/play-youtube.png') }}" alt="youtube" class="youtube-play">
                <img src="https://img.youtube.com/vi/{{ idYoutubeVideo($video->link) }}/hqdefault.jpg"
                    style="width: 100%" alt="{{ $video->title_es }}" class="img-thumbnail" width="320" height="180">
            </a>
        </div>

        <?php endforeach ?>
    </div>
</section>
<section id="services" class="container">

    <h2 class="section-heading" data-aos="fade-up">
        {{ isset($contents[4]) ? ($language == 'es' ? ($contents[4]->val_es ?? $contents[4]->val) : $contents[4]->val) :'Services' }}
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ isset($contents[5]) ? ($language == 'es' ? ($contents[5]->val_es ?? $contents[5]->val) : $contents[5]->val) : 'Your best service' }}
    </h6>
    <div class="row">
        <?php foreach($services as $service): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up">
            <div class="car-rental-card awesome-card position-relative overflow-hidden rounded shadow-sm">
                <img src="{{ thumbnail(assets($service->image)) }}" alt="{{ $service->title }}"
                    class="car-rental-img w-100" loading="lazy" width="350" height="220"
                    style="object-fit:cover; border-radius:1.2rem;">
                <div class="car-rental-title-overlay">
                    <h3 class="car-rental-title">{{ $language == "es" ? $service->title_es : $service->title }}</h3>
                </div>
                <div class="car-rental-content p-3 text-center">
                    <a href="{{ strtolower(str_replace(' ','-',$service->title))  }}"
                        class="btn btn-success car-rental-btn">{{ trans('btn.show-more') }}</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="madagascar" class="container">
    <h2 class="section-heading" data-aos="fade-up">
        Madagascar
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ trans('mada.home.subtitle') }}
    </h6>
    <div class="movie_card" id="tomb" data-aos="fade-up">
        <div class="info_section">
            <div class="movie_header">
                <h5 class="text-left fontInterRegularExtraBold">{{ trans('mada.home.title') }}</h5>
            </div>
            <div class="movie_desc">
                <p class="text text-justify">
                    {{ trans('mada.home.texte') }}
                </p>
                <div class="movie_social text-center">
                    <a href="{{ route('madagascar') }}" class="btn btn-success car-rental-btn">{{ trans('btn.show-more') }}</a>
                </div>
            </div>
        </div>
        <div class="blur_back tomb_back"></div>
    </div>

</section>
<section id="testimonials" class="container" aria-labelledby="reviews-heading">
    <h2 id="reviews-heading" class="sr-only">Customer Reviews</h2>

    <h2 class="section-heading" data-aos="fade-up">
        {{ isset($contents[6]) ? ($language == 'es' ? ($contents[6]->val_es ?? $contents[6]->val) : $contents[6]->val) :'Reviews' }}
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ isset($contents[7]) ? ($language == 'es' ? ($contents[7]->val_es ?? $contents[7]->val) : $contents[7]->val) : 'The review of your customer' }}
    </h6>
    <!-- Reviews List -->
    <div id="reviewsList">
        <!-- Example Review Post -->
        <?php foreach($reviews as $review): ?>
        <div class="review-post card shadow-sm mb-3 p-3" data-aos="fade-up">
            <div class="d-flex align-items-center mb-2">
                <!-- Consider compressing user.png to WebP for better performance -->
                <img src="{{ assets('img/images/user.png') }}" alt="User avatar icon" width="64" height="64"
                    class="review-avatar me-2" loading="lazy" decoding="async">
                <div class="review-user-content">
                    <div class="fw-bold">{{ $review->name_user }}</div>
                    <div class="review-stars">
                        <?php for ($i = 0; $i <5; $i++): ?>
                        <?php if($i < $review->rating): ?>
                        <span class="star filled">&#9733;</span>
                        <?php else: ?>
                        <span class="star">&#9733;</span>
                        <?php endif; ?>


                        <?php endfor; ?>
                    </div>
                    <div class="text-muted small">Posted on {{ $review->daty }}</div>
                </div>
            </div>
            <div class="review-text text-justify">
                {{ $review->message }}
            </div>
        </div>
        <?php endforeach; ?>
        <div class="car-rental-content p-3 text-center mt-2 " data-aos="fade-up">
            <a href="{{ route('reviews') }}" class="btn btn-success car-rental-btn">{{ trans('btn.show-more') }}</a>
        </div>
</section>

<section id="gallery" class="container" aria-labelledby="gallery-heading">
    <h2 id="gallery-heading" class="sr-only">Gallery</h2>
    <h2 class="section-heading" data-aos="fade-up">
        {{ isset($contents[8]) ? ($language == 'es' ? ($contents[8]->val_es ?? $contents[8]->val) : $contents[8]->val) :'Gallery' }}
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ isset($contents[9]) ? ($language == 'es' ? ($contents[9]->val_es ?? $contents[9]->val) : $contents[9]->val) :'Pictures' }}
    </h6>
    <div id="grid" class="grid-container lightgallery" data-aos="fade-up">
        <?php foreach($galleries as $gallery): ?>
        <a href="{{ assets($gallery->image) }}" class="griditem"
            style="background-image: url('{{ thumbnail(assets($gallery->image)) }}');">
            <img class="gallery-thumb" src="{{ assets(thumbnail($gallery->image)) }}" style="display: none;"
                alt="Gallery" loading="lazy" width="320" height="180">
        </a>
        <?php endforeach; ?>
    </div>
    <div class="car-rental-content p-3 text-center mt-2" data-aos="fade-up">
        <a href="{{ route('gallery') }}" class="btn btn-success car-rental-btn">{{ trans('btn.show-more') }}</a>
    </div>
</section>
@include(client.partials.footer)