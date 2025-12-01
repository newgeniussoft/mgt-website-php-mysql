<div class="mt-4">
    <h4>Tour Price</h4>
    <table class="table table-bordered" id="tour-price-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Texte</th>
                <th>Texte (ES)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($tour_prices) && count($tour_prices) > 0): ?>
                <?php foreach($tour_prices as $i => $price): ?>
                    <tr>
                        <td>
                            <select name="tour_prices[<?= $i ?>][type]" class="form-control" required>
                                <option value="include" <?= ($price->type == 'include') ? 'selected' : '' ?>>Include</option>
                                <option value="exclude" <?= ($price->type == 'exclude') ? 'selected' : '' ?>>Exclude</option>
                            </select>
                        </td>
                        <td><input type="text" name="tour_prices[<?= $i ?>][texte]" class="form-control" value="<?= htmlspecialchars($price->texte) ?>" required></td>
                        <td><input type="text" name="tour_prices[<?= $i ?>][texte_es]" class="form-control" value="<?= htmlspecialchars($price->texte_es) ?>"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-price">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td>
                        <select name="tour_prices[0][type]" class="form-control" required>
                            <option value="include">Include</option>
                            <option value="exclude">Exclude</option>
                        </select>
                    </td>
                    <td><input type="text" name="tour_prices[0][texte]" class="form-control" required></td>
                    <td><input type="text" name="tour_prices[0][texte_es]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-price">Remove</button></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm" id="add-price">Add Price</button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let table = document.getElementById('tour-price-table').getElementsByTagName('tbody')[0];
    let addBtn = document.getElementById('add-price');
    let rowIdx = table.rows.length;

    addBtn.addEventListener('click', function() {
        let row = table.insertRow();
        row.innerHTML = `
            <td>
                <select name="tour_prices[${rowIdx}][type]" class="form-control" required>
                    <option value="include">Include</option>
                    <option value="exclude">Exclude</option>
                </select>
            </td>
            <td><input type="text" name="tour_prices[${rowIdx}][texte]" class="form-control" required></td>
            <td><input type="text" name="tour_prices[${rowIdx}][texte_es]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-price">Remove</button></td>
        `;
        row.querySelector('.remove-price').addEventListener('click', function() {
            row.remove();
        });
        rowIdx++;
    });

    Array.from(document.getElementsByClassName('remove-price')).forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.closest('tr').remove();
        });
    });
});
</script>
