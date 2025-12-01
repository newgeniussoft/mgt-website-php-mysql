@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<div>
                <img src="{{ assets($page->meta_image) }}" style="width: 100%" alt="Tours image">

</div>
<h1 class="sr-only">{{ $language == "es" ? "Reservas de hoteles en Madagascar fáciles y rápidas" : "Hotel Booking Madagascar: Find Your Perfect Stay" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Reservas de hoteles en Madagascar al mejor precio" : "Discover Easy Hotel Booking in Madagascar" }}</h2>

<section id="about" class="content-section">
    <div class="container mt-2">
    <div class="card-styled px-6 py-6">
        <div class="card-styled-body px-4">
            
        <h1 class="font-inter-bold text-center text-primary">{{ $language == "es" ? $page->title_es : $page->title }}</h1>
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12">
                <p class="font-inter-regular">{{ $language == "es" ? $page->content_es : $page->content }}</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 pt-6">
                
        <div class="row mt-6">
        @foreach($galleries as $gallery)
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                <img src="{{ assets($gallery->image) }}" style="width: 100%; height: auto" alt="Car">
            </div>
        @endforeach
        </div>
            </div>
        </div>
        </div>
</div>

    </div>
</section>

@include(client.partials.footer)

