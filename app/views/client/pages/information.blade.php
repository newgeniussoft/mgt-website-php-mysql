@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">{{ $language == "es" ? "Excursiones sostenibles en Madagascar para ti" : "Madagascar Eco Tours: Explore Nature Sustainably" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Excursiones sostenibles en Madagascar para todos" : "Discover Madagascar Eco Tours for Sustainable Travel" }}</h2>
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">


<div id="about" class="container">
    <div class="card-styled px-6">
        <div class="card-styled-body">
        <div class="two alt-two mt-4">     <h3 class="title font-inter-bold text-primary text-center">{{ trans('menu.info') }}
            </h3>
            <p class="font-inter-regular text-center">{{ trans('info.title-1') }}</p>
          
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12">
              <div style=" padding: 14px">
              <div class="row">
                <div class="col-lg-12 fontRegular">  <img src="{{ assets('img/images/profile.jpg') }}" class="float-image desktop-img" style="width: 200px" alt="Baba">

                   <p class="text-justify" style="margin-top: 0px">{{ trans('info.p-1') }}</p>
                  <p class="text-justify">{{ trans('info.p-2-1') }}<span style="color: #5bc6fe">N°027-13/MINTOUR/SG/DGDT/DAIT/SAT</span>. 
                    {{ trans('info.p-2-2') }}</p>
                    
                <h4 class="title-4">{{ trans('info.title-2') }}</h4>
                <p class="fontRegular">
                  {{ trans('info.p-3') }}
                </p>
                </div>
              </div>
              <div>
                <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-3">
                            <img src="{{ assets('img/images/01.jpg') }}" alt="Excursiones sostenibles en Madagascar para viajeros responsables" style="width:100%"/>
                        </div>
                        <div class="col-lg-3">
                            <img src="{{ assets('img/images/02.jpg') }}" alt="excursiones sostenibles en Madagascar en un paisaje natural" style="width:100%"/>
                        </div>
                        <div class="col-lg-3">
                            <img src="{{ assets('img/images/04.jpg') }}" alt="Excursiones sostenibles en Madagascar en un paisaje natural." style="width:100%"/>
                        </div>
                        <div class="col-lg-3">
                            <img src="{{ assets('img/images/05.jpg') }}" alt="Excursiones sostenibles en Madagascar con paisajes únicos." style="width:100%"/>
                        </div>
                    </div> 
                  </div>
                </div>
              </div>
          </div>
        </div> 
      </div>
      <p>{{ trans('info.p-4') }}</p>
      <h4 class="fontMedium">ATM</h4>
      <p>{{ trans('info.p-5') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-3') }}</h4>
      <iframe loading="lazy" src="https://xe.com" name="guestbook" scrolling="no" width="100%" height="750" frameborder="no"></iframe>
      <h4 class="fontMedium">{{ trans('info.title-4') }}</h4>
      <p>{{ trans('info.p-6') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-5') }}</h4>
      <p class="fontRegular">{{ trans('info.p-7') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-6') }}</h4>
      <p class="fontRegular">{{ trans('info.p-8') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-7') }}</h4>
      <p class="fontRegular">{{ trans('info.p-9') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-8') }}</h4>
      <p class="fontRegular">{{ trans('info.p-10') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-9') }}</h4>
      <p class="fontRegular">{{ trans('info.p-11') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-10') }}</h4>
      <p class="fontRegular">{{ trans('info.p-12') }}</p>
      <h4 class="fontMedium">{{ trans('info.title-11') }}</h4>
      <p class="fontRegular">{{ trans('info.p-13') }}</p>
      <h4 class="fontMedium">Visa</h4>
      <p>{{ trans('info.p-14') }}</p>
      <p class="fontRegular"></p>
      <h4 class="fontMedium" style="margin-bottom: 0">{{ trans('info.title-12') }}</h4>
      <p style="text-align: justify;" class="fontRegular">{{ trans('info.p-15') }}</p>
  </div>
</div>
</div>

@include(client.partials.footer)

