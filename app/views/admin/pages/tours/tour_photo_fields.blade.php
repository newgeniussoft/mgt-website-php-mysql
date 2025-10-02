<div class="row">
    <div class="col-lg-6">
                                            <div class="form-group mb-2">
                                                <label>Map</label> <br>
                                                <?php if(isset($tour)): ?>
                                                @if($tour->map)
                                                <img src="{{ assets($tour->map) }}" alt="Current Map" width="100">
                                                @endif
                                                <?php endif; ?>
                                                <input type="file" name="map" class="form-control">
                                            </div>

    </div>
    <div class="col-lg-6">
    <div class="form-group mb-2">
                                                <label>Image</label> <br>
                                                <?php if(isset($tour)): ?>
                                                @if($tour->image)
                                                <img src="{{ assets($tour->image) }}" alt="Current Image" width="100">
                                                @endif
                                                <?php endif; ?>
                                                <input type="file" name="image" class="form-control">
                                               
                                            </div>
                                            <div class="form-group mb-2">
                                                <label>Image Cover</label> <br>
                                                <?php if(isset($tour)): ?>
                                                @if($tour->image_cover)
                                                <img src="{{ assets($tour->image_cover) }}" alt="Current Cover"
                                                    width="100">
                                                @endif
                                                <?php endif; ?>
                                                <input type="file" name="image_cover" class="form-control">
                                               
                                            </div>
    </div>
</div>

<div class="form-group mb-4">
    <label>Tour Photos</label>
    <div id="tour-photo-list">
        <?php if(isset($tour_photos) && is_array($tour_photos) && count($tour_photos)): ?>
            <div class="row">
            <?php foreach($tour_photos as $i => $photo): ?>
                <div class="mb-2 col-md-3 tour-photo-row align-items-center">
                    <input type="hidden" name="tour_photos[<?= $i ?>][id]" value="<?= isset($photo->id) ? $photo->id : '' ?>">
                    <input type="hidden" name="tour_photos[<?= $i ?>][image]" value="<?= isset($photo->image) ? $photo->image : '' ?>">
                    <span class="me-2">
                        <?php if(!empty($photo->image)): ?>
                            <a href="{{ assets($photo->image) }}" target="_blank">
                                <img src="{{ assets($photo->image) }}" alt="Photo" style="height:40px;">
                            </a>
                        <?php endif; ?>
                    </span>
                    <button type="button" class="btn btn-danger btn-sm remove-tour-photo" data-photo-id="<?= isset($photo->id) ? $photo->id : '' ?>">Delete</button>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="input-group mb-2">
        <input type="file" class="form-control" name="tour_photo_files[]" multiple>
    </div>
</div>
<script>
    // Remove photo row and mark for deletion
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.remove-tour-photo').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var row = this.closest('.tour-photo-row');
                var photoId = this.getAttribute('data-photo-id');
                if(photoId) {
                    var deletedInput = document.createElement('input');
                    deletedInput.type = 'hidden';
                    deletedInput.name = 'delete_tour_photo_ids[]';
                    deletedInput.value = photoId;
                    row.parentNode.insertBefore(deletedInput, row);
                }
                row.remove();
            });
        });
    });
</script>
