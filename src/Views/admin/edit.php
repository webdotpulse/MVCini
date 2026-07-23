<h2>Editing: <?= htmlspecialchars((string)$file) ?></h2>
<form method="POST" action="/admin/edit?file=<?= urlencode($file) ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$csrf_token) ?>">
    <div class="form-group">
        <textarea name="content" class="form-control" rows="25" style="font-family: monospace; white-space: pre;"><?= htmlspecialchars((string)$content) ?></textarea>
    </div>
    <button type="submit" class="btn"><?= \App\Core\I18n::get('global.save') ?></button>
    <a href="/admin/index" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
</form>
