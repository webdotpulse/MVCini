<h2><?= htmlspecialchars($title) ?></h2>

<?php if (!empty($error)): ?>
    <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/blog/create">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="form-group">
        <label>Language</label>
        <select name="lang" class="form-control">
            <option value="en">English (en)</option>
            <option value="es">Spanish (es)</option>
            <option value="fr">French (fr)</option>
            <option value="nl">Dutch (nl)</option>
        </select>
    </div>

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Slug (URL friendly)</label>
        <input type="text" name="slug" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="10" required></textarea>
    </div>

    <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" placeholder="news, tech, update">
    </div>

    <div class="form-group">
        <label>Header Media Type</label>
        <select name="header_type" class="form-control">
            <option value="none">None</option>
            <option value="image">Image</option>
            <option value="video">Video</option>
        </select>
    </div>

    <div class="form-group">
        <label>Header Media URL</label>
        <input type="text" name="header_url" class="form-control" placeholder="https://example.com/image.jpg">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="has_sidebar" value="1" checked> Enable Sidebar for this post
        </label>
    </div>

    <button type="submit" class="btn">Create Post</button>
    <a href="/blog/admin" class="btn" style="background:#6c757d;">Cancel</a>
</form>
