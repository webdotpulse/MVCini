<a href="/item/create" class="btn"><?= \App\Core\I18n::get('create_item') ?></a>
<button id="loadAjax" class="btn" style="background: #28a745; margin-left: 10px;">Test AJAX API</button>

<div id="ajaxResult" class="ajax-result"></div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th><?= \App\Core\I18n::get('name') ?></th>
            <th><?= \App\Core\I18n::get('description') ?></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars((string)$item['id']) ?></td>
            <td><?= htmlspecialchars((string)$item['name']) ?></td>
            <td><?= htmlspecialchars((string)$item['description']) ?></td>
            <td>
                <a href="/item/edit/<?= $item['id'] ?>"><?= \App\Core\I18n::get('edit') ?></a> |
                <a href="/item/delete/<?= $item['id'] ?>" onclick="return confirm('Are you sure?');" style="color: #dc3545;"><?= \App\Core\I18n::get('delete') ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($items)): ?>
        <tr><td colspan="4">No items found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
document.getElementById('loadAjax').addEventListener('click', function() {
    fetch('/item/api')
        .then(response => response.json())
        .then(data => {
            const div = document.getElementById('ajaxResult');
            div.style.display = 'block';
            div.textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>
