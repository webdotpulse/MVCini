<?php if ($post['header_type'] === 'image' && !empty($post['header_url'])): ?>
    <div style="margin-bottom: 20px;">
        <img src="<?= htmlspecialchars($post['header_url']) ?>" alt="Header Image" style="width: 100%; max-height: 400px; object-fit: cover;">
    </div>
<?php elseif ($post['header_type'] === 'video' && !empty($post['header_url'])): ?>
    <div style="margin-bottom: 20px;">
        <video controls style="width: 100%; max-height: 400px;">
            <source src="<?= htmlspecialchars($post['header_url']) ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
<?php endif; ?>

<div style="display: flex; gap: 20px;">
    <!-- Main Content -->
    <div style="flex: 3;">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p>
            <small>
                <?= htmlspecialchars($post['created_at']) ?>
                <?php if (!empty($tags)): ?>
                    | Tags:
                    <?php foreach ($tags as $tag): ?>
                        <a href="/blog?tag=<?= urlencode($tag['name']) ?>"><?= htmlspecialchars($tag['name']) ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </small>
        </p>

        <div style="margin-top: 20px;">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <p style="margin-top: 30px;"><a href="/blog">&laquo; Back to Blog</a></p>
    </div>

    <!-- Optional Sidebar -->
    <?php if ($post['has_sidebar']): ?>
    <div style="flex: 1; border-left: 1px solid #eee; padding-left: 20px;">
        <h4>About this Blog</h4>
        <p>This is an advanced blog system built with MVCini.</p>

        <form method="GET" action="/blog" class="form-group" style="margin-top: 20px;">
            <input type="text" name="q" placeholder="Search blog..." class="form-control">
            <button type="submit" class="btn" style="margin-top: 5px; width: 100%;">Search</button>
        </form>
    </div>
    <?php endif; ?>
</div>
