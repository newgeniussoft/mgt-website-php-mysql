@import('app/utils/helpers/helper.php')
@include(client.partials.head)  
@include(client.partials.header)
<img src="{{ assets($tour->image_cover) }}" style="width: 100%" alt="Tours image">

<section  class="container content-section mt-4" aria-labelledby="about-heading">
    <h1 class="sr-only">{{ $language == "es" ? $tour->title_es : $tour->title }}</h2>
    <h2 class="sr-only">{{ $language == "es" ? $tour->subtitle_es : $tour->subtitle }}</h2>
    <div class="card-styled card-tour">
        <div class="card-styled-body">
            <h2 class="section-heading">{{ $language == "es" ? $tour->name_es : $tour->name }}</h2>
            <h6 class="section-subtitle">{{ $language == "es" ? $tour->subtitle_es : $tour->subtitle }}</h6> 
            <input class="input" id="tab1" type="radio" name="tabs" checked>
            <label class="label" for="tab1">{{ trans('title.tour.desc') }}</label>
            <input class="input" id="tab2" type="radio" name="tabs">
            <label class="label" for="tab2">{{ trans('title.tour.details') }}</label> 
            <input class="input" id="tab3" type="radio" name="tabs">
            <label class="label" for="tab3">{{ trans('title.tour.photos') }}</label>
            <section  class="content-tab" id="content1">
                <p>{{ $language == "es" ? $tour->description_es : $tour->description }}</p>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12">

                      
    <div id="chartdiv" style="width:100%; height: 600px;background-color: #DCEED3; border-radius: 15px"></div>
                        <!--img src="{{ assets($tour->map) }}" style="width:100%" alt="Map Madagascar {{ assets($tour->name) }}">-->
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <h5>{{ trans('title.tour.hightlights') }}</h5>
                        <div>
                          <!-- here --> 
                           <ul>
                            <?php foreach($tour->highlights as $h): ?>
                            <li>{{ $language == "es" ? $h->texte_es : $h->texte }}</li>
                            <?php  endforeach; ?>
                           </ul>
                              

                        </div>
                        <h5>{{ trans('title.tour.itinerary') }}</h5>
                        <p>{{ $tour->itinerary }}</p>
                        <?php
                          $length = 0;
                          if (count($tour->price_includes) > count($tour->price_excludes)) {
                            $length = count($tour->price_includes);
                          } else {
                            $length = count($tour->price_excludes);
                          }
                        ?>


                        
                        <div class="layout_tab">
  <input name="nav" type="radio" class="nav home-radio" id="home" checked="checked" />
  <div class="page home-page">
    <div class="page-contents">
      <ul>
                            <?php for($i = 0; $i < $length; $i++): ?>
                            <li>{{ isset($tour->price_includes[$i]) ? ($language == 'es' ? ($tour->price_includes[$i]->texte_es ?? $tour->price_includes[$i]->texte) : $tour->price_includes[$i]->texte) : '-' }}</li>
                            
                            <?php endfor; ?>
                        </ul>
    </div>
  </div>
  <label class="nav" for="home">
    <span>
      
{{ trans('title.tour.price.include') }}
    </span>
  </label>

  <input name="nav" type="radio" class="about-radio" id="about" />
  <div class="page about-page">
    <div class="page-contents">
   <ul>
                            <?php for($i = 0; $i < $length; $i++): ?>
                              <?php if (isset($tour->price_excludes[$i])): ?>
                            <li>{{ ($language == 'es' ? ($tour->price_excludes[$i]->texte_es ?? $tour->price_excludes[$i]->texte) : $tour->price_excludes[$i]->texte) }}</li>
                            <?php endif; ?>
                            
                            <?php endfor; ?>
                            </ul>
    </div>
  </div>
  <label class="nav" for="about">
    <span>
{{ trans('title.tour.price.exclude') }}
      </span>
    </label>
</div>
                       
                        
                     <p class="text-center mb-0">{{ trans('add-details') }}</p>
                      <center>
                        <div style="height: 3px; background: #4a5568; width: 140px; margin-top:10px; margin-bottom: 12px"></div>

                      </center>
                    <h6 class="text-center text-uppercase text-primary font-inter-bold"
                    style="margin-bottom: 24px">{{ trans('price-details') }}</h6>
                    
            <center>

<a class="btn__styled mt-2" style="margin-top: 10px " href="{{ route('sales-conditions') }}" role="button" aria-label="Show more tours">
    <span class="text">{{ trans('menu.sales-cond') }}</span>
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4.66669 11.3334L11.3334 4.66669" stroke="white" stroke-width="1.33333"
            stroke-linecap="round" stroke-linejoin="round" />
        <path d="M4.66669 4.66669H11.3334V11.3334" stroke="white" stroke-width="1.33333"
            stroke-linecap="round" stroke-linejoin="round" />
    </svg>
</a>
</center>
                    </div>
                    
                </div>
            </section>
            <section  class="content-tab" id="content2">
            <ol class="timeline">

            <?php foreach($tour->details as $detail): ?>
              <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                 {{trans('day')." ". $detail->day }} </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">{{ $language == "es" ? $detail->title_es : $detail->title }}</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>{{ $language == "es" ? $detail->details_es : $detail->details }}</p>
                  </div>
              </div>
            </li>
              <hr>
              <?php endforeach; ?>


                          
                      </ol>
            </section>
            <section  class="content-tab" id="content3">

            <div id="grid" class="grid-container lightgallery">

    <?php foreach($tour->photos as $gallery): ?>
<a href="{{ assets($gallery->image) }}" class="griditem" style="background-image: url('{{ assets($gallery->image) }}');">
<img class="gallery-thumb" src="{{ assets($gallery->image) }}" style="display: none;" alt="Gallery"
loading="lazy" width="320" height="180">
</a>
<?php endforeach; ?>


</div>
           
            </section>
        </div>
    </div>
</section>

@include(client.partials.footer)
<script>
  




var tabLinks = document.querySelectorAll(".tablinks");
var tabContent = document.querySelectorAll(".tabcontent");


tabLinks.forEach(function(el) {
   el.addEventListener("click", openTabs);
});


function openTabs(el) {
   var btnTarget = el.currentTarget;
   var country = btnTarget.dataset.country;

   tabContent.forEach(function(el) {
      el.classList.remove("active");
   });

   tabLinks.forEach(function(el) {
      el.classList.remove("active");
   });

   document.querySelector("#" + country).classList.add("active");
   
   btnTarget.classList.add("active");
}
</script>