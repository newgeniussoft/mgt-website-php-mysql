@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<section class="container" style="margin-top: 100px">
    <div class="card-styled">
        <div class="card-body">   <img src="{{ assets('img/images/nosy-be.jpg') }}" class="float-image desktop-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
        
            <h1 class="text-center text-primary font-inter-extra-bold fs-28">
            {{ $language == "es" ? $page->title_es : $page->title }}</h1>
            
      <img src="{{ assets('img/images/nosy-be.jpg') }}" class="float-image mobile-img"
        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
      {{ $language == "es" ? $page->content_es : $page->content }}
      <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6" style="position: absolute; bottom: 0; right: 0;">
        <h3 class="text-primary font-inter-extra-bold fs-28">Why choose Nosy Be tours?</h3>
        <p>When you book a Nosy Be Tour, you are choosing an experience that combines breathtaking landscapes, adventure, and authentic encounters. Our local guides ensure that every excursion is meaningful, safe, and enriching.</p>
        <p>Here's why Nosy Be Tours are a must-do during your stay in Madagascar:</p>
        <ul class="font-inter-regular">
            <li><b>Island Hopping:</b> Visit Nosy Komba, Nosy Tanikely, and the world-famous Nosy Iranja.</li>
            <li><b>Marine Adventures:</b> Snorkel or dive in crystal-clear waters, home to sea turtles, dolphins, and colorful coral reefs.</li>
            <li><b>Cultural Discovery:</b> Explore local markets, villages, and ylang-ylang plantations for a true Malagasy experience.</li>
            <li><b>Scenic Views:</b> Climb Mont Passot to enjoy stunning sunsets over volcanic lakes</li>
            <li><b>Wildlife Encounters:</b> Meet lemurs, chameleons, and exotic birds in their natural habitat.</li>
        </ul>
    </div>
    </div>
</section>

<section class="container">
<div class="movie_card mb-4 mt-2" id="tomb" style="background-image: url('{{ assets('img/images/baobab.jpg') }}')">
    <div class="info_section">
      <div class="movie_header">
        <h3 class="text-left font-inter-extra-bold text-primary">
        Nosy Be tours: more than just beaches</h3>
      </div>
      <div class="movie_desc">
        <p class="text text-justify">
        While Nosy Be is world-renowned for its white sandy beaches, our tours 
        go far beyond relaxation. Each excursion offers a balance between discovery 
        and leisure. Imagine starting your day with a guided walk through lush rainforests, 
        followed by a traditional Malagasy meal, and ending it with a sunset cruise across the 
        Indian Ocean.
        </p>
        <p class="text-justify">
            With Nosy Be Tours, every moment becomes a lasting memory.
             Whether you are an adventure seeker, a couple on a honeymoon, 
             or a family looking for fun activities, our itineraries are tailored 
             to your expectations.</p>
             
      </div>
    </div>
    <div class="blur_back nosy_back"></div>
  </div>
</section>



<section class="container mt-2">
  <div class="card card-styled">
    <div class="card-body">
      <h3 class="section-heading mb-2">Book your Nosy Be tour with Madagascar Green Tours</h3>
      <p class="text-center">
        Choosing Madagascar Green Tours means traveling with a 
        trusted local operator who values authenticity, sustainability, 
        and your comfort. We take care of every detail so you can focus on 
        enjoying the magic of Nosy Be.</p>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-12">
          <div class="card-w-icon">
            <div class="card-icon">
              <div class="icon">
                <img src="{{ assets('img/logos/tour-guide.png') }}" alt="guide icon">
              </div>
              <div class="card-text">
                <p class="text-center">Professional and experienced local guides.</p>
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
                <p class="text-center">Flexible itineraries tailored to your needs.</p>
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
                <p class="text-center">Eco-friendly and community-based tourism approach.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="mt-2 text-center">👉 Start your journey today: <a href="{{ route('tours/nosy_be_wonders_tour') }}" class="text-primary">Nosy Be Wonders Tour.</a></p>
      <p class="text-justify">
      Nosy Be is more than just a tropical getaway - it is a world of discoveries waiting for you. From its vibrant marine life to its cultural heritage, every tour reveals something unique.
With Nosy Be Tours by Madagascar Green Tours, you will experience the perfect balance between relaxation and adventure, guided by passionate experts who know the island inside out.
Book your Nosy Be tour now and let the island of perfumes capture your heart.
For more informations, plesace contact-us 
</p>
    </div>
  </div>
</section>



@include(client.partials.footer)