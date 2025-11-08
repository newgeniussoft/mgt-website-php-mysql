<div class="mt-4">
    <h4>Tour Highlights</h4>
    <table class="table table-bordered" id="tour-highlights-table">
        <thead>
            <tr>
                <th>Texte</th>
                <th>Texte (ES)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($tour_highlights) && count($tour_highlights) > 0): ?>
                <?php foreach($tour_highlights as $i => $highlight): ?>
                    <tr>
                        <td><input type="text" name="tour_highlights[<?= $i ?>][texte]" class="form-control" value="<?= htmlspecialchars($highlight->texte) ?>" required></td>
                        <td><input type="text" name="tour_highlights[<?= $i ?>][texte_es]" class="form-control" value="<?= htmlspecialchars($highlight->texte_es) ?>"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-highlight">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td><input type="text" name="tour_highlights[0][texte]" class="form-control" required></td>
                    <td><input type="text" name="tour_highlights[0][texte_es]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-highlight">Remove</button></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm" id="add-highlight">Add Highlight</button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let table = document.getElementById('tour-highlights-table').getElementsByTagName('tbody')[0];
    let addBtn = document.getElementById('add-highlight');
    let rowIdx = table.rows.length;

    addBtn.addEventListener('click', function() {
        let row = table.insertRow();
        row.innerHTML = `
            <td><input type="text" name="tour_highlights[${rowIdx}][texte]" class="form-control" required></td>
            <td><input type="text" name="tour_highlights[${rowIdx}][texte_es]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-highlight">Remove</button></td>
        `;
        row.querySelector('.remove-highlight').addEventListener('click', function() {
            row.remove();
        });
        rowIdx++;
    });

    Array.from(document.getElementsByClassName('remove-highlight')).forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.closest('tr').remove();
        });
    });
});
</script>