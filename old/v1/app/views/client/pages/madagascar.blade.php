@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">{{ $language == "es" ?  "Excursiones en Madagascar: Aventura y Naturaleza" : "Madagascar Adventure Trips: Explore Unique Landscapes" }}</h1>
<h2 class="sr-only">{{ $language == "es" ?  "Descubre las mejores excursiones en Madagascar" : "Discover Exciting Madagascar Adventure Trips" }}</h2>
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">

<section class="container">
    <div class="row mt-5">
        <div class="col-lg-2 col-md-2"></div>
        <div class="col-lg-8 col-md-8">
            <h4 class="title-4">{{ trans('about') }} MADAGASCAR</h4>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <img src="{{ assets('img/images/Madagascar.jpg') }}" alt="Madagascar map" style="width: 100%" />
                </div>
                <div class="col-lg-8 col-md-8">
                    <p class="text-justify fontRegular paragrapheInfo">
                        {{ trans('mada.about') }}
                    </p>
                </div>
            </div>
            <h4 class="title-4">{{ trans('mada.food.t') }}</h4>
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <p class="text-justify fontRegular paragrapheInfo">
                        {{ trans('mada.food.p') }} 
                    </p>
                </div>
                <div class="col-lg-4 col-md-4">
                    <img src="{{ assets('img/images/food-malagasy.jpg') }}" alt="Madagascar adventure trips featuring traditional Malagasy food" style="width: 100%" />
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-4 col-md-4">
                    <img src="{{ assets('img/images/Madagascar-Weather.jpg') }}" alt="Madagascar adventure trips showcasing the island's weather." style="width: 100%" />
                </div>
                <div class="col-lg-8 col-md-8">
            <h4 class="title-4">{{ trans('mada.weather.t') }}</h4>
                    <p class="text-justify fontRegular paragrapheInfo">
                        {{ trans('mada.weather.p') }}
                    </p>
                </div>
            </div>
            <h4 class="title-4">{{ trans('mada.lang.t') }}</h4>
            <div class="row mb-3">
                <div class="col-lg-8 col-md-8">
                    <p class="text-justify fontRegular paragrapheInfo">
                        {{ trans('mada.lang.p') }}
                    </p>
                </div>
                <div class="col-lg-4 col-md-4">
                    <img src="{{ assets('img/images/Malagasy-Languages.jpg') }}" alt="Explore Madagascar adventure trips and its diverse languages." style="width: 100%" />
                </div>
            </div>
            
            
        </div>
    </div>
</section>

@include(client.partials.footer)

