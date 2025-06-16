@import('app/utils/helpers/helper.php')
@include(partials.head)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
    <h2 id="about-heading" class="sr-only">About Madagascar Green Tours</h2>
    <div class="card-styled">
        <div class="card-styled-body">
            <h2 class="section-heading">{{ $tour->name }}</h2>
            <h6 class="section-subtitle">{{ $tour->subtitle }}</h6>
            <center><div class="line-title"></div></center>
            
            <img src="{{ assets($tour->image) }}"
                        alt="Ring-tailed lemur in Madagascar rainforest" decoding="async">
        </div>
    </div>
</section>

@include(partials.footer)