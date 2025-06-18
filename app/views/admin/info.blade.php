@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
@include(admin.partials.sidebar)
<div class="content">
    <div class="content-header d-flex justify-content-between align-items-center">
        <h2>Website Info</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Website Info</h5>
                </div>
                <div class="card-body">
                <?php if(isset($success)): ?>
                        <div class="alert alert-success">{{ $success }}</div>
                        <?php endif;
                        if(isset($error)): ?>
                        <div class="alert alert-danger">{{ $error }}</div>
                        <?php endif ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $info['phone'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $info['whatsapp'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $info['email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <?php if(!empty($info['logo'])): ?>
                                <div class="mb-2">
                                    <img src="{{ assets($info['logo']) }}" alt="Logo" style="height:60px;">
                                </div>
                                <?php  endif  ?>
                            <input type="file" class="form-control" id="logo" name="logo">
                        </div>
                        <div class="mb-3">
                            <label for="short_about" class="form-label">Short About</label>
                            <textarea class="form-control" id="short_about" name="short_about" rows="2">{{ $info['short_about'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            <?php if(!empty($info['image'])): ?>
                                <div class="mb-2">
                                    <img src="{{ assets($info['image']) }}" alt="Main Image" style="height:80px;">
                                </div>
                            <?php endif ?>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3">
    <label for="image_property" class="form-label">Image Property</label>
    <?php if(!empty($info['image_property'])): ?>
        <div class="mb-2">
            <img src="{{ assets($info['image_property']) }}" alt="Image Property" style="height:80px;">
        </div>
    <?php endif ?>
    <input type="file" class="form-control" id="image_property" name="image_property">
</div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include(admin.partials.footer)
