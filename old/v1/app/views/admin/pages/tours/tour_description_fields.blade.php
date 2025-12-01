@import('app/utils/helpers/helper.php')
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-2">
            <!-- ENGLISH -->
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                value="{{ isset($tour) ? $tour->name : '' }}" required>
        </div>
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                value="{{ isset($tour) ? $tour->title : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Subtitle</label>
            <input type="text" name="subtitle" class="form-control"
                value="{{ isset($tour) ? $tour->subtitle : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ isset($tour) ? $tour->description : '' }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label>Text for Customer</label>
            <textarea name="text_for_customer" class="form-control">{{ isset($tour) ? $tour->text_for_customer : '' }}</textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <!-- ESPANOL -->
            <label>Name (ES)</label>
            <input type="text" name="name_es" class="form-control"
                value="{{ isset($tour) ? $tour->name_es : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Title (ES)</label>
            <input type="text" name="title_es" class="form-control"
                value="{{ isset($tour) ? $tour->title_es : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Subtitle (ES)</label>
            <input type="text" name="subtitle_es" class="form-control"
                value="{{ isset($tour) ? $tour->subtitle_es : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Description (ES)</label>
            <textarea name="description_es" class="form-control">{{ isset($tour) ? $tour->description_es : '' }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label>Text for Customer (ES)</label>
            <textarea name="text_for_customer_es" class="form-control">{{ isset($tour) ? $tour->text_for_customer_es : '' }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label>Itinerary</label>
            <textarea name="itinerary"
                class="form-control">{{ isset($tour) ? $tour->itinerary : '' }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label>Meta Title</label>
            <input type="text" name="meta_title" class="form-control"
                value="{{ isset($tour) ? $tour->meta_title : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Meta Description</label>
            <textarea name="meta_description"
                class="form-control">{{ isset($tour) ? $tour->meta_description : '' }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label>Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control"
                value="{{ isset($tour) ? $tour->meta_keywords : '' }}">
        </div>
    </div>
    <div class="col-md-6">
        
    <div class="form-group mb-2">
            <label>Meta Title (ES)</label>
            <input type="text" name="meta_title_es" class="form-control"
                value="{{ isset($tour) ? $tour->meta_title_es : '' }}">
        </div>
        <div class="form-group mb-2">
            <label>Meta Description (ES)</label>
            <textarea name="meta_description_es"
                class="form-control">{{ isset($tour) ? $tour->meta_description_es : '' }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label>Meta Keywords (ES)</label>
            <input type="text" name="meta_keywords_es" class="form-control"
                value="{{ isset($tour) ? $tour->meta_keywords_es : '' }}">
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label>Path</label>
            <input type="text" name="path" class="form-control"
                value="{{ isset($tour) ? $tour->path : '' }}">
        </div>
    </div>
</div>

<div class="row">
    
</div>
