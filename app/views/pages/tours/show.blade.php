@import('app/utils/helpers/helper.php')
@include(partials.head)
<img src="{{ assets($tour->image_cover) }}" style="width: 100%" alt="Tours image">
<section id="about" class="container content-section bg-light" aria-labelledby="about-heading">
    <h2 id="about-heading" class="sr-only">About Madagascar Green Tours</h2>
    <div class="card-styled card-tour">
        <div class="card-styled-body">
            <h2 class="section-heading">{{ $tour->name }}</h2>
            <h6 class="section-subtitle">{{ $tour->subtitle }}</h6> 
            <input class="input" id="tab1" type="radio" name="tabs" checked>
            <label class="label" for="tab1">{{ trans('title.tour.desc') }}</label>
            <input class="input" id="tab2" type="radio" name="tabs">
            <label class="label" for="tab2">{{ trans('title.tour.details') }}</label> 
            <input class="input" id="tab3" type="radio" name="tabs">
            <label class="label" for="tab3">{{ trans('title.tour.photos') }}</label>
            <section  class="content-tab" id="content1">
                <p>{{ $tour->description }}</p>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <img src="{{ assets($tour->map) }}" style="width:100%" alt="Map Madagascar {{ assets($tour->name) }}">
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
                        
                        <ul>
                            <?php foreach($tour->price_includes as $pi): ?>
                            <li>{{ $language == "es" ? $pi->texte_es : $pi->texte }}</li>
                            <?php  endforeach; ?>
                           </ul>
                        <?php
                          $length = 0;
                          if (count($tour->price_includes) > count($tour->price_excludes)) {
                            $length = count($tour->price_includes);
                          } else {
                            $length = count($tour->price_excludes);
                          }
                        ?>
                        <table class="table">
                            <tr>
                                <th>{{ trans('title.tour.price.include') }}</th>
                                <th>{{ trans('title.tour.price.exclude') }}</th>
                            </tr>
                            <?php for($i = 0; $i < $length; $i++): ?>
                            <tr>
                              <td>{{ isset($tour->price_includes[$i]) ? ($language == 'es' ? ($tour->price_includes[$i]->texte_es ?? $tour->price_includes[$i]->texte) : $tour->price_includes[$i]->texte) : '-' }}</td>
                              <td>{{ isset($tour->price_excludes[$i]) ? ($language == 'es' ? ($tour->price_excludes[$i]->texte_es ?? $tour->price_excludes[$i]->texte) : $tour->price_excludes[$i]->texte) : '-' }}</td>
                            </tr>
                            
                            <?php endfor; ?>
                        </table>
                        
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
                  Day 1                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">{{ $language == "es" ? $detail->title : $detail->title_es }}</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>{{ $language == "es" ? $detail->details : $detail->details_es }}</p>
                  </div>
              </div>
            </li>
              <hr>
              <?php endforeach; ?>


                          
                      </ol>
            </section>
            <section  class="content-tab" id="content3">
              
    <div id="lightgallery" class="row">
        <?php foreach($tour->photos as $gallery): ?>
        <a href="{{ assets($gallery->image) }}" data-sub-html=""
            class="gallery-card col-lg-3 col-md-3 col-sm-6 col-xs-6 mb-3 px-2">
            <img class="gallery-thumb" src="{{ assets($gallery->image ) }}" alt="Gallery"
                loading="lazy" width="320" height="180">
            <span class="gallery-hover-icon" aria-hidden="true">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="12" fill="rgba(25,135,84,0.7)" />
                    <path
                        d="M15.5 15.5L13.5 13.5M14.5 10.5C14.5 12.1569 13.1569 13.5 11.5 13.5C9.84315 13.5 8.5 12.1569 8.5 10.5C8.5 8.84315 9.84315 7.5 11.5 7.5C13.1569 7.5 14.5 8.84315 14.5 10.5Z"
                        stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </a>
        <?php endforeach; ?>
        
    </div>
            </section>
        </div>
    </div>
</section>

@include(partials.footer)
<script>
    // tabs

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