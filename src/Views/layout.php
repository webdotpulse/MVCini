<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MVCini Demo') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'A simple MVC framework in PHP') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'mvc, php, framework, demo') ?>">
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        nav { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; font-weight: bold; }
        nav a:hover { color: #007bff; }
        .btn { display: inline-block; padding: 8px 15px; background: #007bff; color: #fff; text-decoration: none; border-radius: 3px; border: none; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-control { width: 100%; padding: 8px; box-sizing: border-box; }
        .ajax-result { margin-top: 20px; padding: 10px; background: #e9ecef; border: 1px solid #ccc; white-space: pre-wrap; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="/">Home</a>
            <a href="/blog"><?= \App\Core\I18n::get('global.blog') ?? 'Blog' ?></a>
            <a href="/tools"><?= \App\Core\I18n::get('global.tools') ?? 'Tools' ?></a>
            <a href="/item/index"><?= \App\Core\I18n::get('global.items') ?></a>
            <a href="/contact"><?= \App\Core\I18n::get('global.contact_us') ?></a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="/admin"><?= \App\Core\I18n::get('global.admin') ?></a>
                    <a href="/blog/admin"><?= \App\Core\I18n::get('global.blog_admin') ?? 'Blog Admin' ?></a>
                <?php endif; ?>
                <a href="/profile">Profile</a>
                <a href="/auth/logout"><?= \App\Core\I18n::get('global.logout') ?> (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
            <?php else: ?>
                <a href="/auth/login"><?= \App\Core\I18n::get('global.login') ?></a>
                <a href="/auth/register">Register</a>
            <?php endif; ?>

            <span style="float: right;">
                <a href="/item/lang/en">EN</a> | <a href="/item/lang/es">ES</a> | <a href="/item/lang/fr">FR</a> | <a href="/item/lang/nl">NL</a>
            </span>
        </nav>

        <h1><?= htmlspecialchars($title ?? 'MVCini') ?></h1>

        <?= $content ?? '' ?>

    </div>
</body>
</html>
