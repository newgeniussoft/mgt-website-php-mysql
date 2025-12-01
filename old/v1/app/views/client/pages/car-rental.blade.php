@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section id="about" class="content-section">
    <div class="container mt-2">
        <h1 class="sr-only">{{ $language == "es" ? "Alquiler de coches en Madagascar - ¡Explora hoy!" : "Madagascar Car Rental: Explore with Ease" }}</h1>
        <h2 class="sr-only">{{ $language == "es" ? "Alquiler de coches en Madagascar para tu aventura" : "Explore Affordable Madagascar Car Rental Options" }}</h2>

    <div class="card-styled">
        <div class="card-styled-body">
            
        <img src="{{ assets($page->meta_image) }}" style="width: 450px" class="float-image" alt="Car">
                <h1 class="font-inter-bold text-primary">{{ $language == "es" ? $page->title_es : $page->title }}</h1>
               {{ $language == "es" ? $page->content_es : $page->content }}

               <div id="grid" class="grid-container lightgallery">

<?php foreach($galleries as $gallery): ?>
<a href="{{ assets($gallery->image) }}" class="griditem" style="background-image: url('{{ assets($gallery->image) }}');">
<img class="gallery-thumb" src="{{ assets($gallery->image) }}" style="display: none;" alt="Gallery"
loading="lazy" width="320" height="180">
</a>
<?php endforeach; ?>


</div>
        </div>
</div>

    </div>
</section>

@include(client.partials.footer)

