@import('app/utils/helpers/helper.php')
@include(client.partials.head)
@include(client.partials.header)
<h1 class="sr-only">Madagascar Green Tours - Blog: {{ $language == "es" ? $blog->title_es : $blog->title }}</h1>
<h2 class="sr-only">Blog Details</h2>
<img src="{{ assets('img/images/cover-main-tours.jpg') }}" style="width: 100%" alt="Tours image">
<section class="content-section">
    <div class="container mb-8">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title text-primary font-inter-bold">{{ $language == "es" ? $blog->title_es : $blog->title }}
  </h4>
                        
                    </div>
                    <div class="card-body">
                        <img src="{{ assets($blog->image) }}" style="width: 100%" alt="Blog image">
                        <div class="card-text font-inter-regular">
                            
                        <small class="text-muted font-inter-regular">Posted on {{ formatDateTime($blog->created_at) }}</small>
                      
                         {{ $language == "es" ? $blog->description_es : $blog->description }}
                            
                        </div>
                        <div class="mt-3">
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(currentLink()) }}" 
                                   target="_blank" 
                                   class="btn btn-primary btn-sm mr-2">
                                    <i class="fab fa-facebook-f me-1"></i> Share on Facebook
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($blog->title.' '.currentLink()) }}" 
                                   target="_blank" 
                                   class="btn btn-success btn-sm mr-2">
                                    <i class="fab fa-whatsapp me-1"></i> Share on WhatsApp
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(currentLink()) }}&text={{ urlencode($blog->title) }}" 
                                   target="_blank" 
                                   class="btn btn-info btn-sm">
                                    <i class="fab fa-twitter me-1"></i> Share on Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="text-primary font-inter-bold">Our tours</h4>
                        <div class="list-group">
                            <?php foreach($tours as $tour): ?>
                            <a href="{{ route('tours/'.$tour->path) }}" class="list-group-item list-group-item-action">{{ $tour->name }}</a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="text-primary font-inter-bold">Recent blogs</h4>
                        <div class="list-group">
                            <?php foreach($recentBlogs as $b): ?>
                            <a href="{{ route('blogs/'.strtolower(str_replace(' ','_',$b->title))) }}" class="list-group-item list-group-item-action">{{ $b->title }}</a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include(client.partials.footer)