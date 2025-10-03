@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
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
                <div id="heroCarousel" class="carousel slide" data-ride="carousel"
                    aria-label="Main Highlights Carousel">
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
            <img src="{{ assets($info->image) }}" class="float-image desktop-img"
                alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
            <h3 class="text-center font-inter-extra-bold text-primary">
                {{ $language == "es" ? $page->title_h1_es : $page->title_h1 }}
            </h3>
            <img src="{{ assets($info->image) }}" class="float-image mobile-img"
                alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
            {{ $language == "es" ? $page->content_es : $page->content }}
            <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6"
                style="position: absolute; bottom: 0; right: 0;">
        </div>
    </div>
</section>
<!-- Our Tours Section with Rich Content -->
<section id="our_tours" class="py-5 content-section">
    <div class="container " data-aos="fade-up">
        <h2 class="text-center text-primary font-inter-extra-bold fs-24 mb-4" data-aos="fade-in">
            {{ isset($contents[0]) ? ($language == 'es' ? ($contents[0]->val_es ?? $contents[0]->val) : $contents[0]->val) : 'Our Tours' }}
        </h2>
        <div class="movie_card mt-1 mb-0" id="tomb" data-aos="fade-up">
            <div class="info_section">
                <div class="movie_header">
                    <h4 class="text-left font-inter-bold">
                        Madagascar birding tours - A birdwatcher's paradise
                    </h4>
                </div>
                <div class="movie_desc">
                    <p>
                        With over 270 bird species, many of them endemic, our Madagascar birding tours offer
                        unforgettable discoveries from north to south, guided by passionate experts.
                    </p>
                    <div class="movie_social text-center">
                        <a href="{{ route('madagascar') }}" class="btn btn-success car-rental-btn">{{
                            trans('btn.show-more') }}</a>
                    </div>
                </div>
            </div>
            <div class="blur_back bird_back"></div>
        </div>
        <div class="movie_card mt-2" id="tomb" data-aos="fade-up">
            <div class="info_section">
                <div class="movie_header">
                    <h4 class="text-left font-inter-bold">
                        Madagascar Adventures - Spectacular Landscapes and Cultural Diversity
                    </h4>
                </div>
                <div class="movie_desc">
                    <p>Choose from our most iconic routes:</p>
                    <ul class="font-inter-regular">
                        <li><a href="{{ route('tours/adventure_tour') }}" class="text-primary">Adventure Tours</a> on
                            the Tsiribihina River and the Tsingy of Bemaraha</li>
                        <li><a href="{{ route('tours/wonderful_classic_tour') }}" class="text-primary">Wonderful
                                Classic</a> Tour through Andasibe, Ranomafana, Isalo, and the RN7</li>
                        <li><a href="{{ route('tours/combination_tour') }}" class="text-primary">Combination tours</a>
                            including the RN7, Nosy Be Wonders, Masoala Adventure, and many other fully customized
                            circuits tailored to your travel goals</li>
                    </ul>
                </div>
            </div>
            <div class="blur_back adventure_back"></div>
        </div>

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
                            {{ trans('btn.show-more') }}
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

<section class="container">
    <div class="card-styled">
        <h2 class="text-primary font-inter-extra-bold text-center">Our commitments</h2>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card-w-icon">
                    <div class="card-icon">
                        <div class="icon">
                            <img src="{{ assets('img/logos/travel-agency.png') }}" alt="Travel icon">
                        </div>
                        <div class="card-text">
                            <p class="text-center">
                                A recognized and experienced travel agency Madagascar known for its service quality
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card-w-icon">
                    <div class="card-icon pb-0">
                        <div class="icon">
                            <img src="{{ assets('img/logos/tour-guide.png') }}" alt="Guide icon">
                        </div>
                        <div class="card-text">
                            <p class="text-center mt-0 mb-0">
                                Professional drivers and guides also accompany every excursion — fluent in English and
                                Spanish, and specialized in Malagasy flora, fauna, and culture
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card-w-icon">
                    <div class="card-icon">
                        <div class="icon">
                            <img src="{{ assets('img/logos/group.png') }}" alt="Users icon">
                        </div>
                        <div class="card-text">
                            <p class="text-center">
                                An eco-friendly approach promoting local guides, sustainable accommodations, and support
                                for local communities
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mt-4 mb-3">
    <div class="card-styled">
        <div class="card-body">
            <h3 class="text-center text-primary font-inter-extra-bold mb-4">
                Why trust us for your Madagascar travel packages?
            </h3>
            <table class="table font-inter-regular">
                <thead>
                    <tr class="text-primary">
                        <th>Benefit</th>
                        <th>Detais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Local expertise</td>
                        <td>25 years of experience in Malagasy ecotourism</td>
                    </tr>
                    <tr>
                        <td>Flexibility</td>
                        <td>Custom-made tours based on your interests</td>
                    </tr>
                    <tr>
                        <td>Professional Assistance</td>
                        <td>Professional drivers and guides also accompany each travel step</td>
                    </tr>
                    <tr>
                        <td>Wide Range of Tours</td>
                        <td>Madagascar tours, Madagascar birding tours, Madagascar adventures</td>
                    </tr>
                    <tr>
                        <td>Sustainable Commitment</td>
                        <td>Support for communities and biodiversity conservation</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section id="testimonials bg-gradient" class="container mb-4" aria-labelledby="reviews-heading">
    <h2 id="reviews-heading" class="sr-only">Customer Reviews</h2>

    <h2 class="section-heading" data-aos="fade-up">
        {{ isset($contents[6]) ? ($language == 'es' ? ($contents[6]->val_es ?? $contents[6]->val) : $contents[6]->val) :'Reviews' }}
    </h2>
    <h6 class="section-subtitle" data-aos="fade-up">
        {{ isset($contents[7]) ? ($language == 'es' ? ($contents[7]->val_es ?? $contents[7]->val) : $contents[7]->val) : 'The review of your customer' }}
    </h6>

    <div class="testimonials">

        <?php foreach($reviews as $review): ?>
        <figure class="snip1157">
            <blockquote>
                <p class="content">
                    {{ $review->message }}
                </p>
                <div class="arrow"></div>
            </blockquote>
            <img src="{{ assets('img/images/user.png') }}" class="sq-sample3" alt="sq-sample3" />
            <div class="author">
                <h5>{{ $review->name_user }} <span>
                        <p>
                            <?php for ($i = 0; $i <5; $i++): ?>
                            <?php if($i < $review->rating): ?>
                            <img src="{{ assets('img/logos/star_filled.png') }}" style="width:20px; height:20px;"
                                class="star filled">
                            <?php else: ?>
                            <img src="{{ assets('img/logos/star_outline.png') }}" style="width:20px; height:20px;"
                                class="star outline">
                            <?php endif; ?>


                            <?php endfor; ?>
                        </p>
                    </span></h5>
            </div>
        </figure>
        <?php endforeach; ?>
    </div>
</section>
<section id="video" class="container mb-4">
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
<section id="gallery" class="container mb-4" aria-labelledby="gallery-heading">
    <div class="card-styled">
        <div class="card-body">
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
            <div class="car-rental-content p-3 mt-2 text-center">
                <a href="{{ route('gallery') }}" class="btn btn-success car-rental-btn">{{ trans('btn.show-more') }}</a>
            </div>
        </div>
    </div>

</section>
<section class="container">

    <div class="card-w-img" data-aos="fade-up">
        <div class="card-w-img-head">
            <img src="assets/img/images/baobab_pic.jpg" class="card-img" alt="Baobab">
        </div>
        <div class="card-w-img-body px-4">
            <h2 class="text-primary font-inter-extra-bold mb-0">Contact us</h2>
            <p class="text-justify">
                Ready for your next adventure? As your dedicated tour operator in Madagascar,
                we will assist you from arrival to departure.
                Reach out via email or WhatsApp — we're here to design
                the ideal Madagascar travel package just for you.
            </p>
            <p class="text-justify">
                With Madagascar Green Tours, explore the wonders of the Red Island through responsible and personalized
                Madagascar Tours. Contact our travel agency Madagascar today and experience a once-in-a-lifetime
                journey, led by professional drivers and guides also accompany every moment of your adventure.
            </p>
            <p class="mb-0">Contact us: <a href="mailto:info@madagascar-green-tours.com"
                    class="text-primary">info@madagascar-green-tours.com</a> or <a href="tel:+261347107100"
                    class="text-primary">+261 34 71 071 00</a></p>
            <p class="mb-1">You can also reach us on:</p>
            <?php foreach($socialMedia as $sMedia): ?>
            <a href="{{ $sMedia->link }}" aria-label="{{ $sMedia->name }}" rel="noopener" target="_blank"><img
                    src="{{ assets($sMedia->image) }}" alt="{{ $sMedia->name }}" width="24" height="24"></a>
            <?php endforeach; ?>
        </div>
</section>

@include(client.partials.footer)