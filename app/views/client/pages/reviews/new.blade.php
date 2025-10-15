<?php
session_start();

// Create security token and form timestamp
$_SESSION['token'] = bin2hex(random_bytes(32));
$_SESSION['form_time'] = time();
?>
@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section id="about" class="content-section">
    <div class="container">
        <div class="card-styled">
            <div class="card-styled-body px-5">
                <h1 class="font-inter-bold text-primary text-center mb-3">{{ trans('add-review') }}</h1>
              <div class="text-center">
                {{ $language == "es" ? $page->content_es : $page->content }}
              </div>
               
              <form id="contact-form" action="{{ $_ENV['APP_URL'].'/'.'app/utils/send_review.php' }}" method="POST">
                <p class="mb-0 ">
                <label class="">{{ trans('rate-us') }}</label><br>
                <span class="star-rating" style="border: solid 1px #d4d4d4; padding: 8px; border-radius: 3px">
                  <label for="rate-1" style="--i:1"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rate" id="rate-1" value="1">
                  <label for="rate-2" style="--i:2"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rate" id="rate-2" value="2">
                  <label for="rate-3" style="--i:3"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rate" id="rate-3" value="3">
                  <label for="rate-4" style="--i:4"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rate" id="rate-4" value="4">
                  <label for="rate-5" style="--i:5"><i class="fa fa-solid fa-star"></i></label>
                  <input type="radio" name="rate" id="rate-5" value="5" checked>
                </span>
              </p>
              <input id="rate" type="hidden">
              <!-- CSRF Token -->
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    <!-- Timing -->
    <input type="hidden" name="form_time" value="<?= $_SESSION['form_time'] ?>">

    <!-- Honeypot field -->
    <input type="text" name="fullname" style="display: none;" autocomplete="off">

                    <div class="form-group mb-2">
                      <label class="mb-0" for="name">{{ trans('full-name') }}</label>
                      <input type="text" name="name" required class="form-control" id="name" placeholder="{{ trans('your').' '.trans('full-name') }}">
                    </div>
                    <div class="form-group mb-2">
                      <label class="mb-0" for="email">{{ trans('email') }}</label>
                      <input type="email" name="email" required class="form-control" id="email" placeholder="{{ trans('your').' '.trans('email') }}">
                    </div>
                    <div class="form-group mb-2">
                      <label for="message">{{ trans('your-review') }}</label>
                      <textarea class="form-control" name="message" rows="4" id="message"></textarea>
                    </div>
  <input type="hidden" name="submit" value="3nF03n0feDPLEndQz">
                    <div class="text-center mt-1">   <a class="btn btn-success car-rental-btn" href="{{ route('reviews/all') }}"><i class="fa fa-comments"></i> {{ trans('menu.reviews') }}</a>
             
                      <button type="submit" name="submit-message" class="btn btn-primary car-rental-btn"><span>{{ trans('submit') }}</span></button>
                    </div>
                </form>
            

            </div>
        </div>   
    </div>
</section>
@include(client.partials.footer)

