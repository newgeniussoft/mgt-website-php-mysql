@import('app/utils/helpers/helper.php')
<?php if (empty($socialMedias)): ?>
<p>No social media found</p>
<?php else: ?>


    <?php foreach($socialMedias as $sm): ?>
<!--begin::Form-->
<form class="form-social" action="#" method="POST" enctype="multipart/form-data">
    <!--begin::Input group-->
    <div class="fv-row">
        <!--begin::Dropzone-->
        <div class="dropzone mb-5">
            <!--begin::Message-->
            <div class="dz-message needsclick">
                <div class="mb-5">
                <img src="{{ assets($sm->image) }}" alt="social media image" style="width: 50px; cursor: default">
                </div>

                <!--begin::Info-->
                <div class="ms-4 me-2">
                <div class="input-group mb-5">
                    <input type="hidden" name="social_action" value="edit">
    <input type="text" class="form-control" placeholder="Title" name="name" value="<?php echo htmlspecialchars($sm->name); ?>" aria-label="Username"/>
    <span class="input-group-text">@</span>
    <input type="text" class="form-control" placeholder="Link" name="link" value="<?php echo htmlspecialchars($sm->link); ?>" aria-label="Server"/>
    <input type="hidden" name="id" value="<?php echo $sm->id; ?>">
    <input type="file" name="image" class="form-control" accept="image/*" />
</div>
                
                </div>
                <!--end::Info-->
                <div class="me-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                <div>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Input group-->
</form>
<!--end::Form-->
        <?php endforeach; ?>
<?php endif; ?>