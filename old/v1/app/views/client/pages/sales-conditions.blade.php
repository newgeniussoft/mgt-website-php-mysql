@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<h1 class="sr-only">{{ $language == "es" ? "Condiciones de venta Madagascar - Madagascar Green Tours" : "Madagascar Trip Policies and Sales Conditions" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Condiciones de venta Madagascar para tus viajes" : "Madagascar Trip Policies: Essential Information for Travelers" }}</h2>
<section class="content-section">
    <div class="container mt-2">
        <h1 class="font-inter-bold">{{ $page->title }}</h1>
        <p class="font-inter-regular">{{ $language == "es" ? $page->content_es : $page->content }}</p>
</div>
</section>

@include(client.partials.footer)

