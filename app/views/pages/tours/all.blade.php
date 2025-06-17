@import('app/utils/helpers/helper.php')
@include(partials.head)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
            <h2 id="about-heading" class="sr-only">About Madagascar Green Tours</h2>

            <div class="card-styled">
                <div class="card-styled-body">
                <h2 class="section-heading">Our Tours</h2>
                <h6 class="section-subtitle">Here our popular tours</h6>
                <center>
                    <div class="line-title"></div>
                </center>
                <div class="row mt-6">
                @foreach($tours as $tour)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <article class="card__styled card--1" onclick="window.location='{{ route('tours/'.$tour->path) }}'">
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
                                style="background-image: url('{{ assets($tour->image) }}');">
                            </div>
                            <a href="#" class="card_link">
                                <div class="card__img--hover"
                                    style="background-image: url('{{ assets($tour->image) }}');">
                                </div>
                            </a>
                            <div class="card__info">
                                <span class="card__category"></span>
                                <h3 class="card__title">{{ $tour->name }}</h3>
                                <p class="card__by">{{ $tour->text_for_customer }}</p>
                            <center>
                <a class="btn__styled mt-2 mb-2" href="{{ route('tours/'.$tour->path) }}" role="button" aria-label="Show more tours">
                    <span class="text">Descover</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.66669 11.3334L11.3334 4.66669" stroke="white" stroke-width="1.33333"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.66669 4.66669H11.3334V11.3334" stroke="white" stroke-width="1.33333"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                            </center>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                </div>
</div>
</section>
<section id="our_tours" class="content-section">
            <div class="container">
                





            </div>

        </section>

@include(partials.footer)