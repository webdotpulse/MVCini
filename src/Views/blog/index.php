<h2><?= htmlspecialchars($title) ?></h2>

<div style="display: flex; gap: 20px;">
    <!-- Main Content -->
    <div style="flex: 3;">
        <?php if (empty($posts)): ?>
            <p>No posts found.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div style="border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px;">
                    <h3><a href="/blog/view/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                    <p>
                        <small>
                            <?= htmlspecialchars($post['created_at']) ?>
                            <?php if (!empty($post['tags'])): ?>
                                | Tags:
                                <?php foreach ($post['tags'] as $tag): ?>
                                    <a href="/blog?tag=<?= urlencode($tag['name']) ?>"><?= htmlspecialchars($tag['name']) ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </small>
                    </p>
                    <div>
                        <?= nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 150, '...'))) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div style="flex: 1; border-left: 1px solid #eee; padding-left: 20px;">
        <form method="GET" action="/blog" class="form-group">
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search blog..." class="form-control">
            <button type="submit" class="btn" style="margin-top: 5px; width: 100%;">Search</button>
        </form>

        <h4>Tags</h4>
        <ul style="list-style: none; padding: 0;">
            <li><a href="/blog">All</a></li>
            <?php foreach ($tags as $tag): ?>
                <li>
                    <a href="/blog?tag=<?= urlencode($tag['name']) ?>" <?= $tagFilter === $tag['name'] ? 'style="font-weight:bold;"' : '' ?>>
                        <?= htmlspecialchars($tag['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
