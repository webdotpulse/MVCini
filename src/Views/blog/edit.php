<h2><?= htmlspecialchars($title) ?></h2>

<?php if (!empty($error)): ?>
    <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/blog/edit/<?= $post['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="form-group">
        <label>Language</label>
        <select name="lang" class="form-control">
            <option value="en" <?= $post['lang'] === 'en' ? 'selected' : '' ?>>English (en)</option>
            <option value="es" <?= $post['lang'] === 'es' ? 'selected' : '' ?>>Spanish (es)</option>
            <option value="fr" <?= $post['lang'] === 'fr' ? 'selected' : '' ?>>French (fr)</option>
            <option value="nl" <?= $post['lang'] === 'nl' ? 'selected' : '' ?>>Dutch (nl)</option>
        </select>
    </div>

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
    </div>

    <div class="form-group">
        <label>Slug (URL friendly)</label>
        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($post['slug']) ?>" required>
    </div>

    <div class="form-group">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
    </div>

    <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($tagsStr ?? '') ?>" placeholder="news, tech, update">
    </div>

    <div class="form-group">
        <label>Header Media Type</label>
        <select name="header_type" class="form-control">
            <option value="none" <?= $post['header_type'] === 'none' ? 'selected' : '' ?>>None</option>
            <option value="image" <?= $post['header_type'] === 'image' ? 'selected' : '' ?>>Image</option>
            <option value="video" <?= $post['header_type'] === 'video' ? 'selected' : '' ?>>Video</option>
        </select>
    </div>

    <div class="form-group">
        <label>Header Media URL</label>
        <input type="text" name="header_url" class="form-control" value="<?= htmlspecialchars($post['header_url'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="has_sidebar" value="1" <?= $post['has_sidebar'] ? 'checked' : '' ?>> Enable Sidebar for this post
        </label>
    </div>

    <button type="submit" class="btn">Update Post</button>
    <a href="/blog/admin" class="btn" style="background:#6c757d;">Cancel</a>
</form>
