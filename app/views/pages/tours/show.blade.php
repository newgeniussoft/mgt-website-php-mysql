@import('app/utils/helpers/helper.php')
@include(partials.head)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
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

                        </div>
                        <h5>{{ trans('title.tour.itinerary') }}</h5>
                        <p>{{ $tour->itinerary }}</p>
                        <table class="table">
                            <tr>
                                <th>{{ trans('title.tour.price.include') }}</th>
                                <th>{{ trans('title.tour.price.exclude') }}</th>
                            </tr>
                            <tr>
                                <td>Airport transfers</td>
                                <td>Food and drinks</td>
                            </tr>
                            <tr>
                                <td>Car, including fuel</td>
                                <td>Local guide fees</td>
                            </tr>
                            <tr>
                                <td>English Speakig Driver/guide</td>
                                <td>Tips</td>
                            </tr>
                            <tr>
                                <td>Hotel for 18 nights</td>
                                <td>Hotel before the tour starts and after the tour has ended</td>
                            </tr>
                        </table>
                        
                     <p class="text-center mb-0">{{ trans('add-details') }}</p>
                      <center>
                        <div style="height: 3px; background: #4a5568; width: 140px; margin-top:10px; margin-bottom: 12px"></div>

                      </center>
                    <h6 class="text-center text-uppercase text-primary font-inter-bold"
                    style="margin-bottom: 24px">{{ trans('price-details') }}</h6>
                    
            <center>

<a class="btn__styled mt-2" style="margin-top: 10px " href="#" role="button" aria-label="Show more tours">
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
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 1                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">TRANSFER AIRPORT</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p><span style="font-family: " times="" new="" roman";"="" roman";="" font-size:="" medium;"="">To your hotel in the centre of Antananarivo. Overnight in hotel</span><br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 2                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANTANANARIVO - ANTSIRABE</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>In the morning you drive to Antsirabe, the city of the pousse pousse, located at 1500 meter altitude in the central highlands. You pass rice paddies and drive through the mountains. Along the way, you can visit a craft market and see the tombs of the Merina. Antsirabe means ‘place of much salt’. It’s a nice city with colonial houses and a cathedral. It’s possible to visit lake Andraikaba, which is located 7 km outside the city. You can walk around the lake or visit the gemstone market next to the lake. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 3                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANTSIRABE - MIANDRIVAZO</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p><span style="font-family: " times="" new="" roman";="" font-size:="" medium;"="">Today you drive to Miandrivazo through a volcanic landscape, characterized by rocky hills and grasslands. You pass several small villages. Because there are no restaurants on the way, there is a picnic lunch. Overnight in hotel.</span><br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 4                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BOAT TRIP ON THE TSIRIBIHINA RIVER, FIRST DAY ON THE RIVER</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>During this boat trip you can easily enjoy the scenery and the landscapes. You see baobabs, kalanchoes and pachypodium. It’s the habitat of chameleons, lemurs, bats and many species of birds, including white herons and bee-eaters. Lunch will be prepared on the riverside. After lunch the boat trip continues. In the evening there is a dinner on the riverside. Camping.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 5                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BOAT TRIP ON THE TSIRIBIHINA RIVER, SECOND DAY ON THE RIVER</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you visit the Gorge de Bemaraha to see lemurs and swim at the waterfall. After lunch the boat trip goes on to a traditional Sakalava village. After visiting this village you go to the riverside where you spend the night.&nbsp; Camping.&nbsp;<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 6                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BOAT TRIP ON THE TSIRIBIHINA RIVER, THIRD DAY ON THE RIVER</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>When you pass the second gorge you can see bats. If you are lucky you can also see crocodiles. The boat trip ends near a small village, where you have lunch. After lunch you go to the village, where the 4WD is waiting for you to bring you to Belo sur Tsiribihina. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 7                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BELO SUR TSIRIBIHINA - BEKOPAKA, TSINGY DE BEMARAHA NATIONAL PARK (PETIT TSINGY)</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you drive to Tsingy de Bemaraha, where you have lunch. Tsingy de Bemaraha is a Unesco World Heritage Site and is one of the biggest protected areas in Madagascar. In the afternoon you visit the Petit Tsingy with a local guide.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 8                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">TSINGY DE BEMARAHA NATIONAL PARK (GRAND TSINGY)</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>You leave early for a one hour drive to the Grand Tsingy and a four to five hour hike in the park. You see lemurs and dozens of birds, orchids, aloes, pachypodium and baobabs. The endemic and medicinal plants make the flora of this park unique. The tsingy are eroded karst formations. There are caves and you can climb to the highest top of the Grand Tsingy. After visiting the park you drive back to the hotel for lunch. You can relax in the afternoon.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 9                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">TSINGY DE BEMARAHA NATIONAL PARK - AVENUE DES BAOBABS - MORONDAVA</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you drive to Morondava. For lunch you stop in Belo sur Tsiribihina. On the way you visit the 800 years old sacred baobab and the ´baobabs in love´. You visit the Avenue des Baobabs at sunset. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 10                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">MORONDAVA - BELO SUR MER</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you drive to Belo sur Mer, the first fishermen’s village between Morondava and Tulear. Belo sur Mer is built on a sandbar, wonderfully exposed at sunset. There is a beautiful beach. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 11                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BELO SUR MER - BEVOAY VIA MANJA.</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you drive to the small farming village of Manja (tobacco).Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 12                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BEVOAY - ANDAVADOAKA</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Early in the morning we cross Mangoky River by ferry. We pursue the sand trail along the sea and arrive in Andavadoaka village, where the seaside is completely decorated with coral reef.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 13                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANDAVADOAKA - SALARY</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 14                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">SALARY - IFATY</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p><span times="" new="" roman";="" font-size:="" medium;"="">We drive through a spiny forest punctuated by baobabs for the last kilometers which will lead us straight to Ifaty beach, one of the best seaside sites in Madagascar.&nbsp;</span><span times="" new="" roman";="" font-size:="" medium;"="">Overnight in hotel.&nbsp;</span><br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 15                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">IFATY</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you spend the day in Ifaty, just relax on the beach, walk in the area or book a snorkeling or diving trip.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 16                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">IFATY - RANOHIRA (NEAR ISALO NATIONAL PARK)</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>After breakfast you drive to Isalo National Park. On the way, you pass the region of cotton. You see the traditional tombs of the Mahafaly tribe and the painted tombs of the Bara tribe and the Antandroy tribe. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 17                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ISALO NATIONAL PARK</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p><span times="" new="" roman";="" font-size:="" medium;"="">You visit the park with a local guide. Isalo is sacred to the</span><br times="" new="" roman";="" font-size:="" medium;"=""><span times="" new="" roman";="" font-size:="" medium;"="">Bara tribe. The Bara use the caves as cemeteries. Isalo is dominated by narrow ravines and by wind and water sculptured rocks. First you hike to the natural swimming pool and after a swim you hike to the Namaza circuit. You can also choose to do only one if these hikes. Overnight in hotel.</span><br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 18                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ISALO NATIONAL PARK - ANJA PARK - AMBALAVAO - FIANARANTSOA</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Today you drive to Fianarantsoa. You visit the community-run Anja Park , where you can see some traditional Betsileo tombs, caves and ring tailed lemurs. After visiting the park you continue to Ambalavao, a pretty town in the highlands. Most of the houses there have a typical wooden balcony. You can visit a silk factory and the local paper factory. On Wednesdays the zebumarket is held in Ambalavao. After lunch you continue to Fianarantsoa. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 19                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">TRAIN TO MANAKARA</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>Early in the morning the driver brings you to the train station, where the train leaves at 7.00 am.It’s a full day travel to Manakara, this spectacular journey takes about ten hours. The views from the train are great. The train stops in the villages, where the people sell fruit, snacks and drinks. The driver waits for you at the train station in Manakara to bring you to your hotel.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 20                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">BOAT EXCURSION PANGALANES CHANNEL AND DRIVE MANAKARA -RANOMAFANA</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>In the morning we offer you a boat trip on the Pangalanes channel. After the boat trip you drive to Ranomafana. Ranomafana means ‘hot water’. During the colonial era this friendly village was a thermal bath centre. Today people go to Ranomafana to visit the national park, the place where the rare big bamboo lemur lives.&nbsp; Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 21                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">RANOMAFANA NATIONAL PARK.</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>About 34 years ago, the golden bamboo lemur was discovered in the forest near the village of Ranomafana. Subsequently, the national park was established to protect this rare lemur. After breakfast you drive to the entrance of the park where you meet your local guide. You can choose from several walks ranging from a few hours to half a day. In the rainforest you can see the Edward’ss sifaka, the golden bamboo lemur and reptiles. There are also about 96 bird species, some endemic. In the afternoon you can visit the village or go to the thermal bath and swim in the hot swimming pool. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 22                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">RANOMAFANA NATIONAL PARK - AMBOSITRA - ANTSIRABE</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>On the way to Antsirabe you pass the typical highland scenery of rice paddies. You stop in Ambositra for lunch. Ambositra is the centre of Madagascar’s woodcarving industry.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 23                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANTSIRABE - ANDASIBE NATIONAL</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>It is possible to do a nocturnal visit with a local guide near Andasibe NP to see mouse lemurs, frogs and chameleons.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 24                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANDASIBE NATIONAL PARK</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>In the morning you visit Andasibe NP with a local guide. Andasibe NP is home of the indri indri, the biggest lemur. You can hear its specific sound from far. Besides the indri indri it’s quite easy to see other animals, like other kinds of lemurs, frogs, lizards, chameleons and lots of birds. In the park you can choose for a lang walk (about 5 hours) or a shorter (about 2 hours). None of the walks is very heavy, but sometimes you have to climb through the trees to see the lemurs from a short distance. After lunch you drive to the Vakona Forest Lodge to visit the island of lemurs and a crocodiles farrm.Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                          <li class="timeline-item | extra-space">
                <span class="timeline-item-icon | filled-icon fontMedium">
                  Day 25                </span>
                <div class="timeline-item-wrapper">
                  <div class="timeline-item-description">
                    <span><h4 class="fontRegular">ANDASIBE NATIONAL PARK - ANTANANARIVO</h4> 
                      </span>
                  </div>
                  <div class="comment fontRegular">
                    <p>On this last day of the tour you drive back to Antananarivo. On the way you visit a reptiles farm, where you see many different chameleons. In Antananarivo you visit the Rova, situated on the highest hill in the city. The Rova is the spiritual centre of the Merina. From the hill you have a great view over the city and lake Anosy. After visiting the rova the driver brings you to the hotel, where the tour ends. Overnight in hotel.<br></p>                  </div>
              </div></li>
              <hr>
                      </ol>
            </section>
            <section  class="content-tab" id="content3">
             zeaze azaaze 
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