@import('app/utils/helpers/helper.php')
@include(client.partials.head)
<link rel="stylesheet" href="{{ assets('css/landing.css') }}">
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
    <div class="card-styled">
        <div class="card-styled-body px-4 py-4">
            <h1 class="font-inter-bold text-primary text-center">{{ $language == "es" ? $page->title_es : $page->title }}</h1>
            {{ $language == "es" ? $page->content_es : $page->content }}
        </div>
    </div>
</section>
@include(client.partials.footer)