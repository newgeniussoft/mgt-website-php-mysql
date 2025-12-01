@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">{{ $language == "es" ? "Contacto Madagascar Green Tours" : "Madagascar Travel Inquiries - Contact Us Today" }}</h1>
<h2 class="sr-only">{{ $language == "es" ? "Contacto Madagascar Green Tours: ¡Estamos aquí para ti!" : "Madagascar Travel Inquiries: Get in Touch Today!" }}</h2>
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">

<section id="about" class="container font-inter-regular">

    <div class="card card-styled px-6">
        <div class="two alt-two mt-4 mb-4">
            <h3 class="title text-center font-inter-bold text-primary">{{ trans('menu.contact-us') }}
                <span class="text-primary">
                </span>
            </h3>
        </div>
        <div class="row">
            <div class="col-lg-4  col-md-4">
                <div class="centered">
                    <div class="icon-contact">
                        <i class="fa fa-map-marker-alt"></i>
                    </div>
                </div>
                <div class="text-center fontRegular fs-13">
                    <b class="text-primary">{{ trans('address') }}</b><br>
                    <a href="#" class="text-secondary">Lot: 12 E 45 Antsenakely 110 Antsirabe</a>
                </div>
            </div>
            <div class="col-lg-4  col-md-4">
                <div class="centered">
                    <div class="icon-contact" style="padding-left: 28px">
                        <i class="fa fa-phone"></i>
                    </div>
                </div>
                <div class="text-center fontRegular fs-13">
                    <b class="text-primary">{{ trans('phone') }}</b><br>
                    <a href="#" class="text-secondary"> +261 34 71 071 00</a><br>
                    <a href="#" class="text-secondary"> +261 33 04 351 17</a>
                </div>
            </div>  
            <div class="col-lg-4  col-md-4">
                <div class="centered">
                    <div class="icon-contact" style="padding-left: 28px; padding-top: 15px">
                        <i class="fa fa-envelope"></i>
                    </div>
                </div>
                <div class="text-center fontRegular fs-13">
                    <b class="text-primary">{{ trans('email') }}</b><br>
                    <a href="#" class="text-secondary">info@madagascar-green-tours.com</a>
                </div>
            </div>  
        </div>
        
        <div class="row mb-4">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <?php if(isset($_GET['sent'])): ?> 
                    <div class="alert alert-success">
                        <strong>Success!</strong> Your message has been sent successfully.
                    </div>
                <?php endif ?>
                <form id="contact-form" action="{{ $_ENV['APP_URL'].'/'.'app/utils/send_email.php' }}" method="POST">
                    <div class="form-group mb-2">
                      <label class="mb-0" for="name">{{ trans('full-name') }}</label>
                      <input type="text" name="name" required class="form-control" id="name" placeholder="{{ trans('your').' '.trans('full-name') }}">
                    </div>
                    <div class="form-group mb-2">
                      <label class="mb-0" for="email">{{ trans('email') }}</label>
                      <input type="email" name="email" required class="form-control" id="email" placeholder="{{ trans('your').' '.trans('email') }}">
                    </div>
                    <div class="form-group mb-2">
                      <label class="mb-0" for="email">Subject</label>
                      <input type="text" name="subject" required class="form-control" id="subject" placeholder="Subject">
                    </div>
                    <div class="form-group mb-4">
                      <label for="message">{{ trans('your-message') }}</label>
                      <textarea class="form-control" name="message" rows="10" id="message"></textarea>
                    </div>
  <input type="hidden" name="submit" value="3nF03n0feDPLEndQz">
                    <div class="text-center mt-2">
                      <button type="submit" name="submit-message" class="btn btn-primary car-rental-btn"><span>{{ trans('submit') }}</span></button>
                    </div>
                </form>
                <div id="mail-result"></div>
            </div>
        </div>
        <iframe style="border: 0; margin-top: 30px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3752.21815711203!2d47.02692731540628!3d-19.87300798663577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x21e50ee555555569%3A0xd797aebb57145beb!2sMadagascar+Green+Tours!5e0!3m2!1sfr!2smg!4v1523127617660" width="100%" height="430" frameborder="0" allowfullscreen="allowfullscreen">
            <span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_start"></span>
            <span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_start"></span>
        </iframe>
        </div>
    </section>
<script>
function launch_toast() {
    var x = document.getElementById("toast")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
  }

</script>
<?php if (isset($_GET['sent'])): ?>
  <script>
    launch_toast();
  </script>
<?php endif; ?>

@include(client.partials.footer)

