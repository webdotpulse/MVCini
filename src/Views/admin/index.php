<div style="float: right;">
    <a href="/admin/create" class="btn" style="background: #28a745;">Create New File</a>
    <a href="/admin/translations" class="btn" style="background: #17a2b8;">Manage Translations</a>
</div>
<h2><?= \App\Core\I18n::get('global.files') ?></h2>

<?php foreach ($filesList as $type => $files): ?>
    <h3><?= htmlspecialchars((string)$type) ?></h3>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <?= htmlspecialchars((string)$file) ?>
                <a href="/admin/edit?file=<?= urlencode($file) ?>">[<?= \App\Core\I18n::get('global.edit') ?>]</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
