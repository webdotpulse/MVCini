<h2>Edit Translations: <?= htmlspecialchars(strtoupper($lang)) ?></h2>

<?php if(!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars((string)$success) ?></p>
<?php endif; ?>

<form method="POST" action="/admin/editTranslation/<?= urlencode($lang) ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">

    <table id="translationsTable">
        <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($translations as $key => $value): ?>
                <tr>
                    <td><input type="text" name="keys[]" value="<?= htmlspecialchars((string)$key) ?>" class="form-control" required></td>
                    <td><input type="text" name="values[]" value="<?= htmlspecialchars((string)$value) ?>" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger" onclick="this.closest('tr').remove()">Remove</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="button" class="btn" style="background: #28a745; margin-top: 10px;" onclick="addTranslationRow()">Add Key</button>
    <br><br>
    <button type="submit" class="btn"><?= \App\Core\I18n::get('save') ?></button>
    <a href="/admin/translations" class="btn" style="background: #6c757d; margin-left: 10px;">Back</a>
</form>

<script>
function addTranslationRow() {
    const tbody = document.querySelector('#translationsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="keys[]" value="" class="form-control" required></td>
        <td><input type="text" name="values[]" value="" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger" onclick="this.closest('tr').remove()">Remove</button></td>
    `;
    tbody.appendChild(tr);
}
</script>
