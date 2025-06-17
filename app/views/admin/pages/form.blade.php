@include(admin.partials.head)
@include(admin.partials.sidebar)
    <div class="content">
        <div class="content-header d-flex justify-content-between align-items-center">
            <h2>{{ $action == 'edit' ? 'Edit' : 'Create' }} Page</h2>
            <div>
                <span class="text-muted">Today: {{ date('F j, Y') }}</span>
            </div>
        </div>
@if($error)
<div class="alert alert-danger">{{ $error }}</div>
@endif
<form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-6">
    <div class="form-group">
        <label>Menu Title (EN)</label>
        <input type="text" name="menu_title" class="form-control" value="{{ $page['menu_title'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Title (EN)</label>
        <input type="text" name="title_en" class="form-control" value="{{ $page['title_en'] ?? '' }}">
    </div>
    <div class="form-group">
    <label>Meta Title (EN)</label>
    <div class="input-group mb-1">
        <textarea name="meta_title" id="meta_title" class="form-control" required>{{ $page['meta_title'] ?? '' }}</textarea>
        <select class="form-select" id="meta_title_select" style="max-width:180px;" onchange="copyMetaField('meta_title', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_title'] }}">{{ $p['menu_title'] }}</option>
                <?php endif?>
            <?php endforeach ?>
        </select>
    </div>
</div>
    <div class="form-group">
    <label>Meta Description (EN)</label>
    <div class="input-group mb-1">
        <textarea name="meta_description" id="meta_description" class="form-control" required>{{ $page['meta_description'] ?? '' }}</textarea>
        <select class="form-select" id="meta_description_select" style="max-width:180px;" onchange="copyMetaField('meta_description', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_description'] }}">{{ $p['menu_title'] }}</option>
                    <?php endif ?>
                    <?php endforeach  ?>
        </select>
    </div>
</div>
    <div class="form-group">
    <label>Meta Keywords (EN)</label>
    <div class="input-group mb-1">
        <textarea name="meta_keywords" id="meta_keywords" class="form-control" required>{{ $page['meta_keywords'] ?? '' }}</textarea>
        <select class="form-select" id="meta_keywords_select" style="max-width:180px;" onchange="copyMetaField('meta_keywords', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_keywords'] }}">{{ $p['menu_title'] }}</option>
                    <?php endif ?>
                    <?php endforeach ?>
        </select>
    </div>
</div>
    <div class="form-group">
        <label>Title H1 (EN)</label>
        <input type="text" name="title_h1" class="form-control" value="{{ $page['title_h1'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H2 (EN)</label>
        <input type="text" name="title_h2" class="form-control" value="{{ $page['title_h2'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Content (EN)</label>
        <textarea name="content" id="content" class="form-control summernote">{{ $page['content'] ?? '' }}</textarea>
    </div>
</div>
        <div class="col-lg-6">
    <div class="form-group">
        <label>Menu Title (ES)</label>
        <input type="text" name="menu_title_es" class="form-control" value="{{ $page['menu_title_es'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Title (ES)</label>
        <input type="text" name="title_es" class="form-control" value="{{ $page['title_es'] ?? '' }}">
    </div>
    <div class="form-group">
    <label>Meta Title (ES)</label>
    <div class="input-group mb-1">
        <textarea name="meta_title_es" id="meta_title_es" class="form-control" required>{{ $page['meta_title_es'] ?? '' }}</textarea>
        <select class="form-select" id="meta_title_es_select" style="max-width:180px;" onchange="copyMetaField('meta_title_es', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_title_es'] }}">{{ $p['menu_title_es'] }} ({{ $p['path'] }})</option>
                    <?php endif ?>
                    <?php endforeach ?>
        </select>
    </div>
</div>
    <div class="form-group">
    <label>Meta Description (ES)</label>
    <div class="input-group mb-1">
        <textarea name="meta_description_es" id="meta_description_es" class="form-control" required>{{ $page['meta_description_es'] ?? '' }}</textarea>
        <select class="form-select" id="meta_description_es_select" style="max-width:180px;" onchange="copyMetaField('meta_description_es', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_description_es'] }}">{{ $p['menu_title_es'] }} ({{ $p['path'] }})</option>
                    <?php endif ?>
                    <?php endforeach ?>
        </select>
   
    </div>
</div>
    <div class="form-group">
    <label>Meta Keywords (ES)</label>
    <div class="input-group mb-1">
        <textarea name="meta_keywords_es" id="meta_keywords_es" class="form-control" required>{{ $page['meta_keywords_es'] ?? '' }}</textarea>
        <select class="form-select" id="meta_keywords_es_select" style="max-width:180px;" onchange="copyMetaField('meta_keywords_es', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_keywords_es'] }}">{{ $p['menu_title_es'] }} ({{ $p['path'] }})</option>
                    <?php endif ?>
                    <?php endforeach ?>
        </select>
    </div>
</div>
    <div class="form-group">
        <label>Title H1 (ES)</label>
        <input type="text" name="title_h1_es" class="form-control" value="{{ $page['title_h1_es'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H2 (ES)</label>
        <input type="text" name="title_h2_es" class="form-control" value="{{ $page['title_h2_es'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Content (ES)</label>
        <textarea name="content_es" id="content_es" class="form-control summernote">{{ $page['content_es'] ?? '' }}</textarea>
    </div>
</div>
    </div>
    <div class="form-group mt-3">
        <label>Path</label>
        <input type="text" name="path" class="form-control" value="{{ $page['path'] ?? '' }}">
    </div>
    <div class="form-group mt-3">
    <label>Meta Image</label>
    <div class="mb-2" id="meta_image_preview_wrapper">
    <?php if(!empty($page['meta_image'])): ?>
            <img id="meta_image_preview" src="http://localhost:8000/public/uploads/{{ $page['meta_image'] }}" alt="Meta Image" style="max-width:150px;max-height:80px;">
            <?php else: ?>
            <img id="meta_image_preview" src="" alt="Meta Image" style="display:none;max-width:150px;max-height:80px;">
            <?php endif ?>
    </div>
    <div class="input-group mb-1">
        <select class="form-select" id="meta_image_select" style="max-width:220px;" onchange="copyMetaField('meta_image', this)">
            <option value="">Copy from...</option>
            <?php foreach($allPages ?? [] as $p): ?>
                <?php if(empty($page) || ($p['id'] != ($page['id'] ?? null))): ?>
                    <option value="{{ $p['meta_image'] }}">{{ $p['menu_title'] }} ({{ $p['path'] }})</option>
                <?php endif ?>
            <?php endforeach ?>
        </select>
        <input type="hidden" name="old_meta_image" value="{{ $page['meta_image'] }}">
    </div>
    <input type="file" name="meta_image" class="form-control">
    <small class="form-text text-muted">Upload a new image to replace the current one.</small>
    
    <script>
      $(function() {
        $('.summernote').summernote({
          height: 220,
        });
      });
    </script>
    
</div>
<script>
function copyMetaField(field, select) {
    var val = select.value;
    if(field === 'meta_image') {
        if(val) {
            document.querySelector('input[name=old_meta_image]').value = val;
            var img = document.getElementById('meta_image_preview');
            if(img) {
                img.src = '/uploads/' + val;
                img.style.display = val ? '' : 'none';
            }
        }
    } else {
        var el = document.getElementById(field);
        if(el) el.value = val;
    }
}
</script>
<script>
function copyMetaField(field, select) {
    var val = select.value;
    if(field === 'meta_image') {
        if(val) {
            // Set hidden field for old_meta_image
            document.querySelector('input[name=old_meta_image]').value = val;
        }
    } else {
        var el = document.getElementById(field);
        if(el) el.value = val;
    }
}
</script>
    <button type="submit" class="btn btn-success">{{ $action == 'edit' ? 'Update' : 'Create' }}</button>
    <a href="/access/pages" class="btn btn-secondary">Cancel</a>
</form>
    </div>
    @include(admin.partials.footer)
