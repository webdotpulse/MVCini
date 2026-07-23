<h2>Manage Translations</h2>

<ul>
    <?php foreach ($languages as $lang): ?>
        <li>
            <strong><?= htmlspecialchars(strtoupper($lang)) ?></strong>
            <a href="/admin/editTranslation/<?= urlencode($lang) ?>">[<?= \App\Core\I18n::get('edit') ?>]</a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="/admin" class="btn" style="margin-top: 20px;">Back to Admin</a>
