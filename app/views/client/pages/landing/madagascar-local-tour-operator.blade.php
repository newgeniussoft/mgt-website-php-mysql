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
      <h3 class="text-center font-inter-extra-bold text-primary">
        {{ trans('land.travel.title.camp') }}
      </h3>
      <p>{{ trans('land.travel.content.camp.p-1') }}</p>
      <p>{{ trans('land.travel.content.camp.p-2') }}</p>
    </div>
  </div>
</section>
<section class="container">
  <div class="movie_card mb-4 mt-2" id="tomb">
    <div class="info_section">
      <div class="movie_header">
        <h3 class="text-left font-inter-extra-bold text-primary">
          Why Choose Madagascar for Your Next Adventure?
        </h3>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
          Madagascar is a land of contrasts, rich in biodiversity and cultural heritage. Over 80% of its wildlife is
          found nowhere else on Earth, making it one of the world’s most unique travel destinations. From the iconic
          baobab trees to rare chameleons, colorful birds, and fascinating reptiles, every day of your trip will be
          filled with discovery.
        </p>
        <p class="text-justify">
          Unlike other destinations crowded with mass tourism, Madagascar remains an exclusive paradise. Travelers
          looking for a peaceful escape will find a perfect balance of adventure and tranquility. This is why our
          Madagascar holiday packages are designed for those who want to immerse themselves in nature while enjoying
          authentic cultural encounters with local communities.
        </p>
      </div>
    </div>
    <div class="blur_back nosy_back"></div>
  </div>
</section>
<section class="container mt-2">
  <div class="card card-styled">
    <div class="card-body">
      <h3 class="section-heading mb-2">Expertly Guided Madagascar Tours</h3>
      <p class="text-center">Our Madagascar travel agency provides a wide range of tour options, from short excursions
        to multi-week expeditions. We offer:</p>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/lemure.png') }}" alt="lemure icon">
              </div>
              <div class="card-text">
                <h4 class="font-inter-bold text-primary">Wildlife tours</h4>
                <p class="text-center">
                  Discover lemurs, chameleons, and other species during guided visits to Madagascar's national parks
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
                <h4 class="font-inter-bold text-primary">Adventure travel</h4>
                <p class="text-center">
                  Trek through rainforests, hike along rugged trails, or explore remote villages.
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
                <h5 class="font-inter-bold text-primary">Beach and honeymoon packages </h5>
                <p class="text-center">
                  Relax on Madagascar's stunning beaches, such as Nosy Be, perfect for couples seeking a romantic
                  escape.
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
                <h5 class="font-inter-bold text-primary">Cultural tours</h5>
                <p class="text-center">
                  Meet local artisans, experience Malagasy traditions, and taste authentic cuisine.
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
                Personalized Travel Services
              </h3>
              <p class="text-justify">
                Unlike large operators, we create tailor-made Madagascar tours that adapt to your preferences. Whether
                you want a luxury holiday with premium accommodation or a true adventure off the beaten path, we will
                design a travel plan that suits your budget and expectations.
              </p>
              <p class="text-justify">If you have specific destinations in mind or would like to adjust the length of
                your trip, we are flexible.
                Our priority is to craft a personalized itinerary that makes your visit truly unforgettable.
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
                Sustainable and Authentic Experiences
              </h3>
              <p class="text-justify mb-1">
                We believe that responsible travel is the best way to explore Madagascar.
                Our tours emphasize local interaction, sustainable tourism, and respect
                for the environment. As you travel across the island, you will not only
                witness Madagascar's natural beauty but also connect with its warm and hospitable people.
              </p>
              <p class="text-justify">
                Many of our guests say the highlight of their journey is not only the landscapes
                and wildlife but also the genuine smiles and cultural exchanges with local communities.
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
        Plan Your Madagascar Holiday Today
      </h3>
      <p>If you are searching for a reliable Madagascar tour operator who combines professionalism,
        passion, and local expertise, you have come to the right place.
        Our mission is to provide you with the most authentic and enriching experience possible.</p>
      <p>From Madagascar wildlife tours to romantic beach holidays, from family adventures to custom-made
        itineraries, we promise to deliver a trip that exceeds your expectations.</p>
      <p>Come and explore this tropical paradise, where adventure meets serenity,
        and every day brings a new discovery. Contact us today to start planning your dream
        journey with a trusted Madagascar travel agency dedicated to making your vacation
        truly unforgettable.</p>
      <p>For any questions or information, do not hesitate to
        <a href="{{ route('contact') }}" class="text-primary">contact the Madagascar Green Tours team.</a>
      </p>
    </div>
  </div>
</section>
@include(client.partials.footer)