<div class="mt-4">
    <h4>Tour Details</h4>
    <table class="table table-bordered" id="tour-details-table">
        <thead>
            <tr>
                <th style="width: 70px">Day</th>
                <th>Details</th>
                <th>Details (ES)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($tour_details) && count($tour_details) > 0): ?>
                <?php foreach($tour_details as $i => $detail): ?>
                    <tr>
                        <td>
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="day{{$i}}" name="tour_details[{{ $i }}][day]" value="{{ $detail->day }}" required/>
                                <label for="day{{$i}}">Day</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="title{{$i}}" name="tour_details[{{ $i }}][title]" value="{{ $detail->title }}" required/>
                                <label for="title{{$i}}">Title</label>
                            </div>
                            
                            <div class="form-floating mb-7">
                            <textarea id="details{{$i}}"  class="form-control" rows="4" name="tour_details[{{ $i }}][details]" id="">{{ $detail->details }}</textarea>
                            <label for="details{{$i}}">Details</label>
                </div>
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="name_tour{{$i}}" name="tour_details[{{ $i }}][name_tour]" value="{{ $detail->name_tour }}" required/>
                                <label for="name_tour{{$i}}">Name Tour</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="title_es{{$i}}" name="tour_details[{{ $i }}][title_es]" value="{{ $detail->title_es }}" required/>
                                <label for="title_es{{$i}}">Title (ES)</label>
                            </div>
                            <div class="form-floating mb-7">
                            <textarea id="details_es{{$i}}"  class="form-control" rows="4" name="tour_details[{{ $i }}][details_es]" id="">{{ $detail->details_es }}</textarea>
                            <label for="details_es{{$i}}">Details (ES)</label>
                        </div>
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="name_tour_es{{$i}}" name="tour_details[{{ $i }}][name_tour_es]" value="{{ $detail->name_tour_es }}" required/>
                                <label for="name_tour_es{{$i}}">Name Tour (ES)</label>
                            </div>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td>
                        <div class="form-floating mb-7">
                            <input type="text" class="form-control" id="day0" name="tour_details[0][day]" required/>
                            <label for="day0">Day</label>
                        </div>
                    </td>
                    <td>
                    <div class="form-floating mb-7">
                        <input type="text" id="title0" name="tour_details[0][title]" class="form-control">
                        <label for="title0">Title</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="details0" name="tour_details[0][details]" class="form-control" rows="4"></textarea>
                        <label for="details0">Details</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name_tour0" name="tour_details[0][name_tour]" class="form-control">
                        <label for="name_tour0">Name Tour</label>
                    </div>
                    </td>
                    <td>
                    <div class="form-floating mb-7">
                        <input type="text" id="title_es0" name="tour_details[0][title_es]" class="form-control">
                        <label for="title_es0">Title (ES)</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea id="details_es0" name="tour_details[0][details_es]" class="form-control" rows="4"></textarea>
                        <label for="details_es0">Details (ES)</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name_tour_es0" name="tour_details[0][name_tour_es]" class="form-control">
                        <label for="name_tour_es0">Name Tour (ES)</label>
                    </div>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm" id="add-detail">Add Detail</button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let table = document.getElementById('tour-details-table').getElementsByTagName('tbody')[0];
    let addBtn = document.getElementById('add-detail');
    let rowIdx = table.rows.length;

    addBtn.addEventListener('click', function() {
        let row = table.insertRow();
        row.innerHTML = `
            <td>
                <div class="form-floating mb-7">
                    <input type="text" class="form-control" id="day${rowIdx}" name="tour_details[${rowIdx}][day]" required/>
                    <label for="day${rowIdx}">Day</label>
                </div>
                </td><td>
                <div class="form-floating mb-7">
                    <input type="text" class="form-control" id="title${rowIdx}" name="tour_details[${rowIdx}][title]" required/>
                    <label for="title${rowIdx}">Title</label>
                </div>
                <div class="form-floating mb-7">
                <textarea id="details${rowIdx}" name="tour_details[${rowIdx}][details]" class="form-control" rows="4"></textarea>
                <label for="details${rowIdx}">Details</label>
                </div>
                <div class="form-floating mb-7">
                    <input type="text" class="form-control" id="name_tour${rowIdx}" name="tour_details[${rowIdx}][name_tour]" required/>
                    <label for="name_tour${rowIdx}">Name Tour</label>
                </div>
            </td>
            <td>
                <div class="form-floating mb-7">
                    <input type="text" class="form-control" id="title_es${rowIdx}" name="tour_details[${rowIdx}][title_es]" required/>
                    <label for="title_es${rowIdx}">Title (ES)</label>
                </div>
                <div class="form-floating mb-7">
                <textarea id="details_es${rowIdx}" name="tour_details[${rowIdx}][details_es]" class="form-control" rows="4"></textarea>
                <label for="details_es${rowIdx}">Details (ES)</label>
                </div>
                <div class="form-floating mb-7">
                    <input type="text" class="form-control" id="name_tour_es${rowIdx}" name="tour_details[${rowIdx}][name_tour_es]" required/>
                    <label for="name_tour_es${rowIdx}">Name Tour (ES)</label>
                </div>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button></td>
        `;
        row.querySelector('.remove-detail').addEventListener('click', function() {
            row.remove();
        });
        rowIdx++;
    });

    Array.from(document.getElementsByClassName('remove-detail')).forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.closest('tr').remove();
        });
    });
});
</script>
