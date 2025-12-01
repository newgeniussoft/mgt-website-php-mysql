@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">

<h1 class="sr-only">{{ $language == "es" ? "Reservas de vuelos a Madagascar fáciles y rápidas" : "Madagascar Flight Booking Made Easy" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Reservas de vuelos a Madagascar al mejor precio" : "Madagascar Flight Booking Made Easy and Affordable" }}</h2>
<section id="about" class="content-section">
    <div class="container mt-2">

    <div class="card-styled">
        <div class="card-styled-body px-4 py-2">
            
        <img src="{{ assets($page->meta_image) }}" class="mobile-img" style="width: 100%" alt="Car">
        <img src="{{ assets($page->meta_image) }}" class="desktop-img" style="float: right; margin-left: 8px; width: 450px" alt="Car">
            
            <h1 class="font-inter-bold text-primary">{{ $page->title }}</h1>
            <p class="font-inter-regular">{{ $language == "es" ? $page->content_es : $page->content }}</p>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="row">
        @foreach($galleries as $gallery)
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <img src="{{ assets($gallery->image) }}" style="width: 100%; height: auto" alt="Car">
            </div>
        @endforeach
        </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                
            </div>
        </div>
        </div>
</div>

    </div>
</section>

@include(client.partials.footer)

