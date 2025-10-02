@import('app/utils/helpers/helper.php')
<main id="main-content">
<nav class="navbar navbar-expand-lg navbar-light " id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('') }}">
                <picture>
                    <source media="(max-width: 768px)" srcset="{{ assets($info->logo) }}" type="image/webp"
                        width="120" height="42">
                    <source media="(min-width: 769px)" srcset="{{ assets($info->logo) }}" type="image/webp"
                        width="220" height="77">
                    <img src="{{ assets($info->logo) }}" alt="Madagascar Green tours Travel Logo" class="navbar-logo" width="180"
                        height="63" fetchpriority="high">
                </picture>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav header-styled gradient-border">
                    <li class="nav-item nav-home {{ currentPage() == '' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('') }}"> <i class="fa fa-home"></i> {{ trans('menu.home') }}</a>
                    </li>
                    <li class="nav-item nav-tours dropdown {{ currentPage() == 'tours' ? 'active' : '' }}">
                        <a class="nav-link nav-our-tour dropdown-toggle" href="{{ route('tours') }}" id="toursDropdown" role="button"
                             aria-haspopup="true" aria-expanded="true">
                            {{ trans('menu.our-tours') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="toursDropdown">
                            <?php 
                            $rn7tours = [];
                            $tsiribihinaTours = [];
                            foreach($tours as $tour):
                                $isRN7 = strpos($tour->name, 'RN7') !== false;
                                if($isRN7) {
                                    $rn7tours[] = $tour;
                                }
                                $isTSiribihina = strpos($tour->name, 'TSIRIBIHINA') !== false;
                                if($isTSiribihina) {
                                    $tsiribihinaTours[] = $tour;
                                }
                            endforeach;
                            $pos = 1;
                            foreach($tours as $tour):
                                $isRN7 = strpos($tour->name, 'RN7') !== false;
                                ?>
                                <?php if($pos > 7  && $pos < 11): ?>
                                    <?php if($pos == 8): ?>
                                    <div class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="{{ route('tours/'.$tour->path) }}">{{ $language == "es" ? "Giras" : "" }} RN7 {{ $language != "es" ? "Tours": "" }}</a>
                                    <div class="dropdown-menu">
                                        <?php foreach($rn7tours as $rn7tour): ?>
                                            <a class="dropdown-item" href="{{ route('tours/'.$rn7tour->path) }}">{{ $language == 'es' ? $rn7tour->name_es : $rn7tour->name }}</a>
                                        <?php endforeach?>
                                    </div>
                                    </div>

                                    <?php endif?>
                                    
                                    <?php elseif($pos > 11 && $pos < 14): ?>
                                        <?php if($pos == 12): ?>
                                    <div class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="{{ route('tours/'.$tour->path) }}">{{ $language == "es" ? "Giras" : "" }} Tsiribihina {{ $language != "es" ? "Tours": "" }}</a>
                                    <div class="dropdown-menu">
                                        <?php foreach($tsiribihinaTours as $tsiribihinaTour): ?>
                                            <a class="dropdown-item" href="{{ route('tours/'.$tsiribihinaTour->path) }}">{{ $language == 'es' ? $tsiribihinaTour->name_es : $tsiribihinaTour->name }}</a>
                                        <?php endforeach?>
                                    </div>
                                    </div>
                                    <?php endif?>
                                <?php else: ?>
                                    <a class="dropdown-item" href="{{ route('tours/'.$tour->path) }}">{{ $language == 'es' ? $tour->name_es : $tour->name }}</a>
                                <?php endif?>
                                <?php $pos++?>
                            <?php endforeach?>
                        </div>
                    </li>

                    <li class="nav-item dropdown {{ currentPage() == 'services' ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="toursDropdown" role="button"
                             aria-haspopup="true" data-toggle="dropdown"  aria-expanded="true">
                            {{ trans('menu.services') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="toursDropdown">
                            <a class="dropdown-item" href="{{ route('car-rental') }}">{{ trans('menu.car-rental') }}</a>
                            <a class="dropdown-item" href="{{ route('hotel-booking') }}">{{ trans('menu.hotel-booking') }}</a>
                            <a class="dropdown-item" href="{{ route('flight-booking') }}">{{ trans('menu.flight-booking') }}</a>
                        </div>
                    </li>
                    <li class="nav-item {{ currentPage() == 'reviews' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('reviews') }}">{{ trans('menu.reviews') }}</a>
                    </li>
                    <li class="nav-item {{ currentPage() == 'blogs' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('blogs') }}">Blogs</a>
                    </li>
                    <li class="nav-item {{ currentPage() == 'contact' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('contact') }}">{{ trans('menu.contact-us') }}</a>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-right">
                    
                <li class="nav-item">
                        <a class="nav-link flag" href="{{ switchTo('en') }}"><img src="{{ assets('img/logos/uk_rounded.png') }}"
                                alt="United Kingdom flag" width="28" height="28" />
                            <span>English</span>
                            </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flag" href="{{ switchTo('es') }}"><img src="{{ assets('img/logos/sp_rounded.png') }}" alt="Spain flag"
                                width="28" height="28" />
                            <span>Spanish</span>
                            </a>
                    </li>
                </ul>
            </div>
        </div>
    <style>
/* Custom submenu styles */
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
    display: none;
    position: absolute;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
.dropdown-submenu > .dropdown-item.dropdown-toggle:after {
    float: right;
    margin-left: 5px;
    margin-top: 10px;
    rotate: -90deg;
}

@media (max-width: 991.98px) {
    .dropdown-submenu > .dropdown-menu {
        position: static;
        display: none;
        margin-left: 1rem;
        box-shadow: none;
        background: transparent;
    }
    .dropdown-submenu.show > .dropdown-menu {
        display: block;
    }
    .dropdown-submenu > .dropdown-item.dropdown-toggle:after {
        rotate: 0deg;
        margin-top: 0;
    }
    .dropdown-menu {
        overflow-y: scroll;
        max-height: 300px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var isMobile = window.innerWidth < 992;
    function handleSubmenuClick(e) {
        if (isMobile) {
            var submenu = this.parentElement;
            submenu.classList.toggle('show');
            var subMenuDropdown = submenu.querySelector('.dropdown-menu');
            if (submenu.classList.contains('show')) {
                subMenuDropdown.style.display = 'block';
            } else {
                subMenuDropdown.style.display = 'none';
            }
            e.preventDefault();
            e.stopPropagation();
        }
    }
    var submenuToggles = document.querySelectorAll('.dropdown-submenu > .dropdown-item.dropdown-toggle');
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', handleSubmenuClick);
    });
    // Remove submenu open state on resize
    window.addEventListener('resize', function() {
        isMobile = window.innerWidth < 992;
        if (!isMobile) {
            document.querySelectorAll('.dropdown-submenu').forEach(function(submenu) {
                submenu.classList.remove('show');
                var subMenuDropdown = submenu.querySelector('.dropdown-menu');
                if(subMenuDropdown) subMenuDropdown.style.display = '';
            });
        }
    });
});
</script>
</nav>