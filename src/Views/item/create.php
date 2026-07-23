<form method="POST" action="/item/create">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
    <div class="form-group">
        <label><?= \App\Core\I18n::get('global.name') ?>:</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label><?= \App\Core\I18n::get('global.description') ?>:</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
    </div>
    <button type="submit" class="btn"><?= \App\Core\I18n::get('global.save') ?></button>
    <a href="/" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
</form>
