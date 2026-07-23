<h2><?= htmlspecialchars($title) ?></h2>

<p>
    <a href="/admin" class="btn">&laquo; Back to Admin Dashboard</a>
    <a href="/blog/create" class="btn" style="float: right;">Create New Post</a>
</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Lang</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $post['id'] ?></td>
                <td><?= htmlspecialchars($post['lang']) ?></td>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= htmlspecialchars($post['slug']) ?></td>
                <td><?= $post['created_at'] ?></td>
                <td>
                    <a href="/blog/edit/<?= $post['id'] ?>" class="btn">Edit</a>
                    <form action="/blog/delete/<?= $post['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <a href="/blog/view/<?= htmlspecialchars($post['slug']) ?>" class="btn" target="_blank">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
