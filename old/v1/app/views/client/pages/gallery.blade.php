@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">Madagascar Green Tours - Gallery</h1>
<h2 class="sr-only">Tour Photo Gallery</h2>
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<h1 class="sr-only">{{ $language == "es" ? "Galería de tours en Madagascar: Explora nuestra colección" : "Madagascar Nature Photography Gallery" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Explora nuestra galería de tours en Madagascar." : "Explore Stunning Madagascar Nature Photography" }}</h2>
<section class="container mt-2">
    <h2 class="section-heading">{{ $language == "es" ? $page->menu_title_es : $page->menu_title }}</h2>
    <h6 class="section-subtitle">{{ $language == "es" ? $page->title_es : $page->title }}</h6>
    
<div id="grid" class="grid-container lightgallery">

<?php foreach($galleries as $gallery): ?>
<a href="{{ assets($gallery->image) }}" class="griditem" style="background-image: url('{{ thumbnail(assets($gallery->image)) }}');">
<img class="gallery-thumb" src="{{ assets(thumbnail($gallery->image)) }}" style="display: none;" alt="Gallery"
loading="lazy" width="320" height="180">
</a>
<?php endforeach; ?>


</div>
</section>

@include(client.partials.footer)

