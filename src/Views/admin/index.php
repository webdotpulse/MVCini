<div style="float: right;">
    <a href="/admin/create" class="btn" style="background: #28a745;">Create New File</a>
</div>
<h2><?= \App\Core\I18n::get('files') ?></h2>

<?php foreach ($filesList as $type => $files): ?>
    <h3><?= htmlspecialchars((string)$type) ?></h3>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <?= htmlspecialchars((string)$file) ?>
                <a href="/admin/edit?file=<?= urlencode($file) ?>">[<?= \App\Core\I18n::get('edit') ?>]</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
