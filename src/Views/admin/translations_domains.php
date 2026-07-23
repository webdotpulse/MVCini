<h2>Manage Domains: <?= htmlspecialchars(strtoupper($lang)) ?></h2>

<ul>
    <?php foreach ($domains as $domain): ?>
        <li>
            <strong><?= htmlspecialchars($domain) ?></strong>
            <a href="/admin/editTranslation/<?= urlencode($lang) ?>/<?= urlencode($domain) ?>">[<?= \App\Core\I18n::get('global.edit') ?>]</a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="/admin/translations" class="btn" style="margin-top: 20px;">Back to Languages</a>
