<h2>Create New File</h2>
<?php if(!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars((string)$error) ?></p>
<?php endif; ?>
<form method="POST" action="/admin/create">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">

    <div class="form-group">
        <label>Type:</label>
        <select name="type" class="form-control" required>
            <option value="Model">Model</option>
            <option value="View">View</option>
            <option value="Controller">Controller</option>
        </select>
    </div>

    <div class="form-group">
        <label>Filename (e.g., 'Category', 'category/index'):</label>
        <input type="text" name="filename" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Content:</label>
        <textarea name="content" class="form-control" rows="15" style="font-family: monospace; white-space: pre;"></textarea>
    </div>

    <button type="submit" class="btn"><?= \App\Core\I18n::get('save') ?></button>
    <a href="/admin/index" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
</form>
