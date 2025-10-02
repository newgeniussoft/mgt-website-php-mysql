@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<h1 class="sr-only">{{ $language == "es" ? "Opiniones sobre tours en Madagascar" : "Madagascar Travel Reviews: Explore Real Experiences"}}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Opiniones sobre tours en Madagascar de nuestros viajeros" : "Explore Authentic Madagascar Travel Reviews"}}</h2>

<section class="content-section">
            <div class="container">
                
<section id="testimonials" class="container py-5" aria-labelledby="reviews-heading">
    <h2 id="reviews-heading" class="sr-only">Customer Reviews</h2>

    <h2 class="section-heading">
        {{ $language == "es" ? $page->menu_title_es :  $page->menu_title }}
    </h2>
    <h6 class="section-subtitle">
        {{ $language == 'es' ? $page->title_es : $page->title }}
    </h6>
    <div class="text-center">
            <button class="btn btn-success car-rental-btn modal-button mb-2"><i class="fa fa-plus"></i> {{ trans('add-review') }}</button>
        </div>
        
    <!-- Reviews List -->
    <div id="reviewsList">
        <!-- Example Review Post -->
        <?php foreach($reviews as $review): ?>
        <div class="review-post card shadow-sm mb-3 p-3">
            <div class="d-flex align-items-center mb-2">
                <!-- Consider compressing user.png to WebP for better performance -->
                <img src="{{ assets('img/images/user.png') }}" alt="User avatar icon" width="64" height="64"
                    class="review-avatar me-2" loading="lazy" decoding="async">
                <div class="review-user-content">
                    <div class="fw-bold">{{ $review->name_user }}</div>
                    <div class="review-stars">
                        <?php for ($i = 0; $i <5; $i++): ?>
                            <?php if($i < $review->rating): ?>
                        <span class="star filled">&#9733;</span>
                        <?php else: ?>
                            <span class="star">&#9733;</span>
                            <?php endif; ?>
                        
                        
                        <?php endfor; ?>
                    </div>
                    <div class="text-muted small">Posted on {{ $review->daty }}</div>
                </div>
            </div>
            <div class="review-text text-justify">
            {{ $review->message }}
            </div>
        </div>
        <?php endforeach; ?>


</section>
            </div>

        </section>
        <div class="modal">
        <div class="modal-container">
          <div class="modal-left pt-2 px-3 py-0">
            <h2 class="modal-title mx-0 mb-0">{{ trans('submit-review') }}</h2>
            <p class="modal-desc mb-2" style="font-size: 11pt">
              {{ trans('review-sub') }}
            </p>
            <form id="contact-form" action="{{ $_ENV['APP_URL'].'/'.'app/utils/create_review.php' }}" method="POST">
                <p class="mb-0 ">
                <label class="">{{ trans('rate-us') }}</label><br>
                <span class="star-rating" style="border: solid 1px #d4d4d4; padding: 8px; border-radius: 3px">
                  <label for="rate-1" style="--i:1"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-1" value="1"checked>
                  <label for="rate-2" style="--i:2"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-2" value="2">
                  <label for="rate-3" style="--i:3"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-3" value="3">
                  <label for="rate-4" style="--i:4"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-4" value="4">
                  <label for="rate-5" style="--i:5"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-5" value="5">
                </span>
              </p>
              <input id="rate" type="hidden">
                    <div class="form-group mb-2">
                      <label class="mb-0" for="name">{{ trans('full-name') }}</label>
                      <input type="text" name="name" required class="form-control" id="name" placeholder="{{ trans('your').' '.trans('full-name') }}">
                    </div>
                    <div class="form-group mb-2">
                      <label class="mb-0" for="email">{{ trans('email') }}</label>
                      <input type="email" name="email" required class="form-control" id="email" placeholder="{{ trans('your').' '.trans('email') }}">
                    </div>
                    <div class="form-group mb-0">
                      <label for="message">{{ trans('your-review') }}</label>
                      <textarea class="form-control" name="message" rows="2" id="message"></textarea>
                    </div>
  <input type="hidden" name="submit" value="3nF03n0feDPLEndQz">
                    <div class="text-center mt-1">
                      <button type="submit" name="submit-message" class="btn btn-primary car-rental-btn"><span>{{ trans('submit') }}</span></button>
                    </div>
                </form>
                
            <!--form action="{{ route('reviews') }}" id="form-review" method="POST">
              <?php   
              session_start();
$_SESSION['token'] = bin2hex(random_bytes(32));
echo '<input type="hidden" name="token" value="'.$_SESSION['token'].'">'; 
?>
              <p class="mb-0 ">
                <label class="">{{ trans('rate-us') }}</label><br>
                <span class="star-rating" style="border: solid 1px #d4d4d4; padding: 8px; border-radius: 3px">
                  <label for="rate-1" style="--i:1"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-1" value="1"checked>
                  <label for="rate-2" style="--i:2"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-2" value="2">
                  <label for="rate-3" style="--i:3"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-3" value="3">
                  <label for="rate-4" style="--i:4"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-4" value="4">
                  <label for="rate-5" style="--i:5"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rating" id="rate-5" value="5">
                </span>
              </p>
              <input id="rate" type="hidden">
              <div class="form-group mb-2">
                <label class="mb-0" for="name">{{ trans('full-name') }}</label>
                <input type="text" name="name" required class="form-control" id="name" placeholder="{{ trans('your').' '.trans('full-name') }}">
              </div>
              <div class="form-group mb-2">
                <label class="mb-0" for="email">{{ trans('email') }}</label>
                <input type="email" name="email" required class="form-control" id="email" placeholder="{{ trans('your').' '.trans('email') }}">
              </div>
              <div class="form-group mb-4">
                <label for="message">{{ trans('your-review') }}</label>
                <textarea class="form-control" name="message" rows="2" id="message"></textarea>
              </div>
              <div class="text-center mt-2">
                <button type="submit" name="submit-message" class="btn btn-success car-rental-btn"><span>{{ trans('submit') }}</span></button>
              </div>
            </form-->
          </div>
          <div class="modal-right">
            <img src="{{ assets('img/images/lemur.webp') }}" alt="Lemur">
          </div>
          <button class="icon-button close-button">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                <path d="M 25 3 C 12.86158 3 3 12.86158 3 25 C 3 37.13842 12.86158 47 25 47 C 37.13842 47 47 37.13842 47 25 C 47 12.86158 37.13842 3 25 3 z M 25 5 C 36.05754 5 45 13.94246 45 25 C 45 36.05754 36.05754 45 25 45 C 13.94246 45 5 36.05754 5 25 C 5 13.94246 13.94246 5 25 5 z M 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.980469 15.990234 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 z"></path>
              </svg>
          </button>

        </div>
      </div>
      <?php
        $pages = ceil($count/3);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
      ?>
      {{  pagination($page, $pages) }} 
    </div>
@include(client.partials.footer)

<script>

  document.addEventListener('DOMContentLoaded', function () {
      const body = document.querySelector("body");
  const modal = document.querySelector(".modal");
  const modalButton = document.querySelector(".modal-button");
  const closeButton = document.querySelector(".close-button");
  const scrollDown = document.querySelector(".scroll-down");
  let isOpened = false;

  const openModal = () => {
    modal.classList.add("is-open");
    $('#header').addClass('z-index-0');
    body.style.overflow = "hidden";
  };

  const closeModal = () => {
    modal.classList.remove("is-open");
    $('#header').removeClass('z-index-0');
    body.style.overflow = "initial";
  };

  window.addEventListener("scroll", () => {
    if (window.scrollY > window.innerHeight / 3 && !isOpened) {
      isOpened = true;
      scrollDown.style.display = "none";
      openModal();
    }
  });

  modalButton.addEventListener("click", openModal);
  closeButton.addEventListener("click", closeModal);

  document.onkeydown = evt => {
    evt = evt || window.event;
    evt.keyCode === 27 ? closeModal() : false;
  };

  for (var i = 1; i < 6; i++) {
    $('#rate-'+i).on('change', function(value) {
      $("#rate").val(value.target.value);
    });
  }

  });
  function launch_toast() {
    var x = document.getElementById("toast")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
  }
</script>
<?php if(isset($_GET['deleted'])): ?>
<script>
alert('Review deleted');
    
</script>
<?php endif; ?>
<?php if(isset($_GET['sent']) || isset($_GET['added'])): ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  
    Swal.fire({
      icon: 'success',
      title: 'Success...',
      text: "{{ isset($_GET['sent']) ? 'Good job, your review has been added successfully and wait for validation of administrator.' : 'Review added successfully !!' }}",
    });
</script>
<?php endif; ?>