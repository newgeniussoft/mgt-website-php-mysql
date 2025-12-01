@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    @include(admin.partials.sidebar)
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content  px-lg-3 ">
                            <div id="kt_app_content_container" class="app-container  container-fluid ">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ isset($page) ? 'Edit' : 'Create' }} Page</h3>
                                    </div>
                                    <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="{{ isset($page) ? 'edit' : 'create' }}">
    <div class="row">
        <div class="col-lg-6">
    <div class="form-group">
        <label>Menu Title (EN)</label>
        <input type="text" name="menu_title" class="form-control" value="{{ $page['menu_title'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Title (EN)</label>
        <input type="text" name="title" class="form-control" value="{{ $page['title'] ?? '' }}">
    </div>
    <div class="form-group">
    <label>Meta Title (EN)</label>
    <div class="input-group mb-1">
        <textarea name="meta_title" id="meta_title" class="form-control" required>{{ $page['meta_title'] ?? '' }}</textarea>
        <select class="form-select" id="meta_title_select" style="max-width:180px;" onchange="copyMetaField('meta_title', this)">
            <option value="">Copy from...</option>
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_title }}">{{ $p->menu_title }}</option>
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
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_description }}">{{ $p->menu_title }}</option>
                <?php endif ?>
            <?php endforeach ?>
        </select>
    </div>
</div>
    <div class="form-group">
    <label>Meta Keywords (EN)</label>
    <div class="input-group mb-1">
        <textarea name="meta_keywords" id="meta_keywords" class="form-control" required>{{ $page['meta_keywords'] ?? '' }}</textarea>
        <select class="form-select" id="meta_keywords_select" style="max-width:180px;" onchange="copyMetaField('meta_keywords', this)">
            <option value="">Copy from...</option>
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_keywords }}">{{ $p->menu_title }}</option>
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
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_title_es }}">{{ $p->menu_title_es }} ({{ $p->path }})</option>
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
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_description_es }}">{{ $p->menu_title_es }} ({{ $p->path }})</option>
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
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_keywords_es }}">{{ $p->menu_title_es }} ({{ $p->path }})</option>
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

    <!-- Dynamic Contents CRUD Table -->
    <div class="form-group mt-3">
        <label>Page Contents (h1, h2, h3, etc.)</label>
        <table class="table table-bordered" id="contentsTable">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Value (EN)</th>
                    <th>Value (ES)</th>
                    <th style="width:80px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(($contents ?? []) as $c): ?>
                <tr>
                    <td>
                        <input type="hidden" name="content_id[]" value="<?= htmlspecialchars($c->id) ?>">
                        <input type="text" name="content_type[]" class="form-control" value="<?= htmlspecialchars($c->type) ?>" required>
                    </td>
                    <td>
                        <input type="text" name="content_val[]" class="form-control" value="<?= htmlspecialchars($c->val) ?>" required>
                    </td>
                    <td>
                        <input type="text" name="content_val_es[]" class="form-control" value="<?= htmlspecialchars($c->val_es ?? '') ?>">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteContentRow(this, '<?= $c->id ?>')">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary btn-sm" onclick="addContentRow()">Add Content</button>
        <input type="hidden" name="delete_content_id[]" id="delete_content_ids">
    </div>
    <script>
    function addContentRow() {
        var tbody = document.querySelector('#contentsTable tbody');
        var tr = document.createElement('tr');
        tr.innerHTML = `<td><input type="hidden" name="content_id[]" value=""><input type="text" name="content_type[]" class="form-control" required></td><td><input type="text" name="content_val[]" class="form-control" required></td><td><input type="text" name="content_val_es[]" class="form-control"></td><td><button type="button" class="btn btn-danger btn-sm" onclick="deleteContentRow(this, '')">Delete</button></td>`;
        tbody.appendChild(tr);
    }
    function deleteContentRow(btn, id) {
        if(id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_content_id[]';
            input.value = id;
            document.forms[0].appendChild(input);
        }
        btn.closest('tr').remove();
    }
    </script>
    <!-- End Dynamic Contents CRUD Table -->

    <div class="form-group mt-3">
    <label>Meta Image</label>
    <div class="mb-2" id="meta_image_preview_wrapper">
    <?php if(!empty($page['meta_image'])): ?>
            <img id="meta_image_preview" src="{{ assets($page['meta_image']) }}" alt="Meta Image" style="max-width:150px;max-height:80px;">
            <?php else: ?>
            <img id="meta_image_preview" src="" alt="Meta Image" style="display:none;max-width:150px;max-height:80px;">
            <?php endif ?>
    </div>
    <div class="input-group mb-1">
        <select class="form-select" id="meta_image_select" style="max-width:220px;" onchange="copyMetaField('meta_image', this)">
            <option value="">Copy from...</option>
            <?php foreach($pages ?? [] as $p): ?>
                <?php if(empty($page) || ($p->id != ($page->id ?? null))): ?>
                    <option value="{{ $p->meta_image }}">{{ $p->menu_title }} ({{ $p->path }})</option>
                <?php endif ?>
            <?php endforeach ?>
        </select>
        <input type="hidden" name="old_meta_image" value="{{ $page->meta_image }}">
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
    <button type="submit" class="btn btn-success">{{ isset($page) ? 'Update' : 'Create' }}</button>
    <a href="/access/pages" class="btn btn-secondary">Cancel</a>
</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)
<?php if (isset($success)): ?>
<script>
    
    Swal.fire(
                        'Success!',
                        'Update successfully.',
                        'success'
                    );
</script>
    <?php endif; ?>
{{ isset($success) ? 'ok' : "" }}
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote CSS/JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 250,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>
