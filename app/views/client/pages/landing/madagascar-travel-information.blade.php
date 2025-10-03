@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<section class="content-section">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-12">
        <div id="iview-overlay">
          <div class="bottom_inside_divider"></div>
        </div>
                <h3 class="text-center text-white display-2 font-inter-bold title-slogan">
                    {{ $language == "es" ? $page->title_es : $page->title }}
                </h3>
        <img src="{{ assets('img/images/plage.jpg') }}" style="width: 100%" alt="Tours image">
      </div>
    </div>
  </div>
</section>
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
    <div class="card-styled">
        <div class="card-styled-body">
            <img src="{{ assets('img/images/hotel-beach.jpg') }}" class="float-image desktop-img"
                alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
            <h3 class="text-center font-inter-extra-bold text-primary">
                {{ $language == "es" ? $page->title_h2_es : $page->title_h2 }}
            </h3>
            <img src="{{ assets('img/images/hotel-beach.jpg') }}" class="float-image mobile-img"
                alt="Ring-tailed lemur in Madagascar rainforest" decoding="async" fetchpriority="high">
            {{ $language == "es" ? $page->content_es : $page->content }}
            <img src="{{ assets('img/images/shape-6.webp')}}" alt="shape-6"
                style="position: absolute; bottom: 0; right: 0;">
        </div>
    </div>
</section>
<section>
<div class="top_inside_divider"></div>
    <img src="{{ assets('img/images/hotel_ranomafana.jpg') }}" style="width: 100%; margin-top: -32px" alt="shape-6">
    
</section>
<section class="container mb-4" style="margin-top: -90px">
    <div class="card-styled">
        <div class="card-styled-body px-4">
            <h3 class="text-center font-inter-extra-bold text-primary">
            Camping in Madagascar - A Unique Adventure Close to Nature
            </h3>
            <p>Because we specialize in tours “off the beaten path,” some itineraries include camping experiences. This is the best way to immerse yourself in Madagascar’s pristine nature, far from crowded tourist areas. We provide modern lightweight tents with excellent waterproof protection (up to 4000 mm water column), ensuring comfort even during tropical rains.
                Guests usually sleep in single or double tents, with extra space for luggage and equipment. While camping, our professional cooks prepare three fresh meals daily, with special attention to vegetarian diets and allergy-friendly options. Many travelers say the food served on our camping tours is among the best they enjoy during their entire trip. It’s a truly memorable way to experience Madagascar’s landscapes and wilderness.
            </p>
        </div>
    </div>
</section>
<section class="container content-section bg-light">
  <div class="card-styled">
    <div class="card-styled-body">
      <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
          <img src="{{ assets('img/uploads/images/img_6861a311506581.80191269.webp') }}" alt="car" style="width:100%; border-radius: 10px">
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12">
          <h4 class="text-primary text-left font-inter-extra-bold fs-16">
          Vehicles - Safe & Reliable Transport Across Madagascar</h4>
          <p class="text text-justify">
        Traveling across Madagascar requires reliable transport, as distances are long and road conditions can be challenging. Our vehicles for Madagascar tours include 4x4 off-road cars, minibuses, and larger buses, depending on group size. Each vehicle meets strict safety standards, is fully insured, and is driven by experienced Malagasy drivers.
    </p><p>Our drivers are experts in navigating Madagascar's toughest terrains, from rugged highlands to sandy coastal roads. With their knowledge of the country and its landscapes, they guarantee that you will reach your destination safely and comfortably, while also enjoying scenic views along the way.
  
        </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container content-section bg-light mb-4">
  <div class="card-styled">
    <div class="card-styled-body">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h4 class="text-primary text-left font-inter-extra-bold fs-16">
          Travel Security - Your Safety Comes First</h4>
          <p class="text text-justify">When booking a trip, safety and financial protection are essential. Every package tour we offer includes a travel security note, required by European and Swiss law. This guarantees that your prepaid travel expenses are insured in the unlikely event of insolvency by the tour operator.
</p><p>If you encounter travel offers without such a security guarantee, proceed with caution. Reliable tour companies always protect their guests with full insurance coverage—not only for finances, but also for vehicles and travelers. With Madagascar Green Tours, you can book with confidence, knowing your trip is fully protected.

        </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container content-section bg-light mb-4">
  <div class="card-styled">
    <div class="card-styled-body px-4 py-4">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h3 class="text-primary text-left font-inter-extra-bold fs-16">
          Our Travel Kit - Practical Information for Your Journey</h3>
          <p class="text">
          To ensure our guests are well prepared, each booking includes a custom Madagascar travel kit. This guide contains essential information about the island, its people, and its culture. You'll also find:
            </p>
            <ul class="font-inter-regular">
                <li>A practical packing list with recommended gear</li>
                <li>Photography tips to capture Madagascar's unique wildlife and landscapes</li>
                <li>A checklist for a basic first-aid kit</li>
                <li>Useful Malagasy phrases to help you interact with locals</li>
            </ul>
            <p>In short, our travel kit is designed to give you everything you need for a smooth, enjoyable, and unforgettable journey in Madagascar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container content-section bg-light">
  <div class="card-styled">
    <div class="card-styled-body px-4 py-4">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <h3 class="text-primary text-left font-inter-extra-bold fs-16">
          Conclusion</h3>
          <p class="text">
          From carefully selected Madagascar hotels and lodges, to adventurous camping tours, reliable vehicles, and full safety guarantees, Madagascar Green Tours ensures every detail of your trip is thoughtfully planned. With over two decades of experience as a trusted Madagascar tour operator, we provide travelers with authentic experiences, professional guidance, and lasting memories.</p>
         </div>
      </div>
    </div>
  </div>
</section>
@include(client.partials.footer)