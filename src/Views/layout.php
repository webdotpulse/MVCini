<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MVCini Demo') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'A simple MVC framework in PHP') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'mvc, php, framework, demo') ?>">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer base {
            h1 { @apply text-3xl font-extrabold text-gray-900 mb-6 tracking-tight; }
            h2 { @apply text-2xl font-bold text-gray-800 mb-4; }
            h3 { @apply text-xl font-semibold text-gray-700 mb-3; }
            p { @apply mb-4; }
            a { @apply text-blue-600 hover:text-blue-800 hover:underline transition-colors; }
            table { @apply w-full text-left border-collapse mt-4 shadow-sm rounded-lg overflow-hidden; }
            thead { @apply bg-gray-50; }
            th { @apply py-3 px-4 font-semibold text-gray-700 border-b border-gray-200 uppercase text-xs tracking-wider; }
            td { @apply py-3 px-4 border-b border-gray-200 text-gray-600; }
            tr:hover td { @apply bg-gray-50; }
        }
        @layer components {
            .btn { @apply inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer text-center no-underline; }
            .btn-danger { @apply bg-red-600 hover:bg-red-700 focus:ring-red-500; }
            .form-group { @apply mb-5; }
            .form-group label { @apply block text-sm font-medium text-gray-700 mb-1; }
            .form-control { @apply w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow sm:text-sm; }
            .ajax-result { @apply mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md shadow-inner whitespace-pre-wrap hidden text-sm font-mono text-gray-800; }
            .nav-link { @apply text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors no-underline; }
        }
    </style>
    <script src="/js/main.js" defer></script>
</head>
<body class="bg-slate-50 font-sans text-gray-800 flex flex-col min-h-screen antialiased">

    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Main Nav -->
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center text-2xl font-black text-blue-600 tracking-tighter hover:no-underline">
                        MVCini
                    </a>
                    <nav class="hidden md:ml-8 md:flex md:space-x-2 items-center">
                        <a href="/" class="nav-link">Home</a>
                        <a href="/blog" class="nav-link"><?= \App\Core\I18n::get('global.blog') ?? 'Blog' ?></a>
                        <a href="/tools" class="nav-link"><?= \App\Core\I18n::get('global.tools') ?? 'Tools' ?></a>
                        <a href="/item/index" class="nav-link"><?= \App\Core\I18n::get('global.items') ?></a>
                        <a href="/contact" class="nav-link"><?= \App\Core\I18n::get('global.contact_us') ?></a>

                        <?php if(\App\Core\Session::has('user_id') && \App\Core\Session::get('role') === 'admin'): ?>
                            <div class="h-4 w-px bg-gray-300 mx-2"></div>
                            <a href="/admin" class="nav-link"><?= \App\Core\I18n::get('global.admin') ?></a>
                            <a href="/blog/admin" class="nav-link"><?= \App\Core\I18n::get('global.blog_admin') ?? 'Blog Admin' ?></a>
                        <?php endif; ?>
                    </nav>
                </div>

                <!-- Secondary Nav & Auth -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="hidden sm:flex text-xs font-medium text-gray-400 space-x-2 border-r border-gray-200 pr-4">
                        <a href="/lang/en" class="hover:text-blue-600 hover:no-underline transition-colors">EN</a>
                        <span>&bull;</span>
                        <a href="/lang/es" class="hover:text-blue-600 hover:no-underline transition-colors">ES</a>
                        <span>&bull;</span>
                        <a href="/lang/fr" class="hover:text-blue-600 hover:no-underline transition-colors">FR</a>
                        <span>&bull;</span>
                        <a href="/lang/nl" class="hover:text-blue-600 hover:no-underline transition-colors">NL</a>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-3">
                        <?php if(\App\Core\Session::has('user_id')): ?>
                            <a href="/profile" class="nav-link !px-2">Profile</a>
                            <a href="/auth/logout" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md hover:no-underline">
                                <?= \App\Core\I18n::get('global.logout') ?>
                                <span class="text-xs opacity-75 ml-1">(<?= htmlspecialchars(\App\Core\Session::get('username')) ?>)</span>
                            </a>
                        <?php else: ?>
                            <a href="/auth/login" class="nav-link"><?= \App\Core\I18n::get('global.login') ?></a>
                            <a href="/auth/register" class="btn !py-1.5 !px-4 hover:no-underline">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 lg:p-10">
            <h1 class="mb-8 pb-4 border-b border-gray-100"><?= htmlspecialchars($title ?? 'MVCini') ?></h1>

            <div class="prose max-w-none prose-blue">
                <?= $content ?? '' ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm mb-4 md:mb-0">
                &copy; <?= date('Y') ?> MVCini Framework. Built with native PHP 8.
            </p>
            <div class="flex space-x-6 text-sm text-gray-400">
                <a href="#" class="hover:text-gray-600 hover:no-underline transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-gray-600 hover:no-underline transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>
</html>
