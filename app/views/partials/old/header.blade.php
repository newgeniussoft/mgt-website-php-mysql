@import('app/utils/helpers/helper.php')
<div class="header-mobile-before">
    <a href="{{ $_ENV['APP_URL'] }}" title="Madagascar Green Tours - Tour Operator Madagascar" rel="home">
        <img src="{{ assets('img/logo/logo_new_updated.png') }}" alt="Madagascar Green Tours - Tour Operator Madagascar" />
    </a>
</div>
<header id="header" class="main-header header-2 header-sticky header-mobile-sticky header-mobile-2 menu-drop-dropdown">
    <div class="container header-mobile-wrapper">
        <div class="header-mobile-inner header-mobile-2">
            <div class="toggle-icon-wrapper" data-ref="main-menu" data-drop-type="dropdown">
                <div class="toggle-icon"><span></span></div> <span>Menu</span>
            </div>
            <div class="header-customize"></div>
        </div>
    </div>
    <div class="container header-desktop-wrapper">
        <div class="header-left">
            <div class="header-logo">
                <a href="{{ $_ENV['APP_URL'] }}" title="Madagascar Green Tours - Tour Operator Madagascar" rel="home">
                    <img src="{{ assets('img/logo/logo_new_updated.png') }}" alt="Madagascar Green Tours - Tour Operator Madagascar" />
                </a>
            </div>
        </div>
        <div class="header-right">
            <div id="primary-menu" class="menu-wrapper">
                <ul id="main-menu" class="main-menu menu-nav menu-drop-dropdown x-nav-menu x-nav-menu_main-menu x-animate-sign-flip" data-breakpoint="991" >
                    <li class="menu-fly-search">
                        <form  method="get" action="https://madagascar-green-tours.com/">
                            <input type="text" name="s" placeholder="Search...">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </li>
                    <li id="menu-item-2456" class="menu-item {{ namePage() == '' ? 'current-menu-item  current_page_item' : ''}} x-menu-item x-sub-menu-standard">
                        <a href="{{ $_ENV['APP_URL'] }}" class="x-menu-a-text">
                        <i class="fa fa-home"></i> <span class="x-menu-text">{{ trans('menu.home') }}</span>
                        </a>
                    </li>
                    
                    @navlink(About,about)
                    @navlink(Contact,contact)
                    <li class="menu-item en lang x-menu-item x-sub-menu-standard">
                        <a href="{{ switchTo('en') }}" class="x-menu-a-text">
                            <span class="x-menu-text"><img class="wpml-ls-flag" src="{{ assets('img/flags/uk.png') }}" alt="English"/></span>
                        </a>
                    </li>
                    <li class="menu-item sp lang x-menu-item x-sub-menu-standard">
                        <a href="{{ switchTo('es') }}" class="x-menu-a-text">
                            <span class="x-menu-text">
                                <img class="wpml-ls-flag" src="{{ assets('img/flags/spain.png') }}" alt="Spanish"/>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>