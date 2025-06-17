@import('app/utils/helpers/helper.php')
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('') }}">
                <picture>
                    <source media="(max-width: 768px)" srcset="{{ assets('img/logos/logo_120.webp') }}" type="image/webp"
                        width="120" height="42">
                    <source media="(min-width: 769px)" srcset="{{ assets('img/logos/logo_220.webp') }}" type="image/webp"
                        width="220" height="77">
                    <img src="{{ assets('img/logos/logo_180.webp') }}" alt="MGT Travel Logo" class="navbar-logo" width="180"
                        height="63" fetchpriority="high">
                </picture>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav header-styled">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('') }}">{{ trans('menu.home') }} <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('tours') }}" id="toursDropdown" role="button"
                             aria-haspopup="true" aria-expanded="true">
                            {{ trans('menu.our-tours') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="toursDropdown">
                            <?php foreach($tours as $tour):?>
                            <a class="dropdown-item" href="{{ route('tours/'.$tour->path) }}">{{ $tour->name }}</a>
                            <?php endforeach?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="toursDropdown" role="button"
                             aria-haspopup="true" data-toggle="dropdown"  aria-expanded="true">
                            Service
                        </a>
                        <div class="dropdown-menu" aria-labelledby="toursDropdown">
                            <a class="dropdown-item" href="{{ route('car-rental') }}">Car rental</a>
                            <a class="dropdown-item" href="{{ route('hotel-booking') }}">Hotel booking</a>
                            <a class="dropdown-item" href="{{ route('flight-booking') }}">Flight booking</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reviews') }}">{{ trans('menu.reviews') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">{{ trans('menu.contact-us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flag" href="{{ switchTo('en') }}"><img src="{{ assets('img/logos/uk.png') }}"
                                alt="United Kingdom flag" width="22" height="22" /></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flag" href="{{ switchTo('es') }}"><img src="{{ assets('img/logos/spain.png') }}" alt="Spain flag"
                                width="22" height="22" /></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>