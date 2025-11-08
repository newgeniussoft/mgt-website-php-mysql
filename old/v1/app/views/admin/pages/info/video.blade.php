@import('app/utils/helpers/helper.php')
<?php if (empty($videos)): ?>
<p>No social media found</p>
<?php else: ?>
<?php foreach($videos as $video): ?>
<form class="form-video" action="#" method="POST">
    <div class="fv-row">
        <div class="dropzone mb-5">
            <div class="dz-message needsclick">
                <div class="mb-5">
                    <img src="https://img.youtube.com/vi/{{ idYoutubeVideo($video->link) }}/maxresdefault.jpg" style="width: 190px" alt="upload">
                </div>
                <div class="ms-4 me-2">
                    <div class="input-group mb-5">
                        <input type="hidden" name="video_action" value="edit">
                        <input type="text" class="form-control" placeholder="Title" name="title"
                            value="<?php echo htmlspecialchars($video->title); ?>" aria-label="Title" />
                        <input type="text" class="form-control" placeholder="Title (Spanish)" name="title_es"
                            value="<?php echo htmlspecialchars($video->title_es); ?>" aria-label="Title" />
                        <input type="hidden" name="id" value="<?php echo $video->id; ?>">
                    </div>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" placeholder="Subtitle" name="subtitle"
                            value="<?php echo htmlspecialchars($video->subtitle); ?>" aria-label="Subtitle" />
                        <input type="text" class="form-control" placeholder="Subtitle (Spanish)" name="subtitle_es"
                            value="<?php echo htmlspecialchars($video->subtitle_es); ?>" aria-label="Subtitle" />
                    </div>
                    
                    
                    <input type="text" class="form-control" placeholder="Link" name="link"
                            value="<?php echo htmlspecialchars($video->link); ?>" aria-label="Link" />
                </div>
                <div class="me-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                <div>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php endforeach; ?>
<?php endif; ?>