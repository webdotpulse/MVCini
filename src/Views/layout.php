<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MVCini Demo') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'A simple MVC framework in PHP') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'mvc, php, framework, demo') ?>">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/main.js" defer></script>
</head>
<body>
    <div class="container">
        <nav>
            <a href="/">Home</a>
            <a href="/blog"><?= \App\Core\I18n::get('global.blog') ?? 'Blog' ?></a>
            <a href="/tools"><?= \App\Core\I18n::get('global.tools') ?? 'Tools' ?></a>
            <a href="/item/index"><?= \App\Core\I18n::get('global.items') ?></a>
            <a href="/contact"><?= \App\Core\I18n::get('global.contact_us') ?></a>

            <?php if(\App\Core\Session::has('user_id')): ?>
                <?php if(\App\Core\Session::get('role') === 'admin'): ?>
                    <a href="/admin"><?= \App\Core\I18n::get('global.admin') ?></a>
                    <a href="/blog/admin"><?= \App\Core\I18n::get('global.blog_admin') ?? 'Blog Admin' ?></a>
                <?php endif; ?>
                <a href="/profile">Profile</a>
                <a href="/auth/logout"><?= \App\Core\I18n::get('global.logout') ?> (<?= htmlspecialchars(\App\Core\Session::get('username')) ?>)</a>
            <?php else: ?>
                <a href="/auth/login"><?= \App\Core\I18n::get('global.login') ?></a>
                <a href="/auth/register">Register</a>
            <?php endif; ?>

            <span style="float: right;">
                <a href="/lang/en">EN</a> | <a href="/lang/es">ES</a> | <a href="/lang/fr">FR</a> | <a href="/lang/nl">NL</a>
            </span>
        </nav>

        <h1><?= htmlspecialchars($title ?? 'MVCini') ?></h1>

        <?= $content ?? '' ?>

    </div>
</body>
</html>
