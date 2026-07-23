<!-- Tailwind CSS via CDN for demo purposes -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto mt-10">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4"><?= \App\Core\I18n::get('global.welcome') ?></h1>
        <p class="text-gray-600 text-lg">A custom, lightweight MVC framework built with native PHP 8 and MySQL.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Explore Features</h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li><a href="/item/index" class="text-blue-600 hover:underline"><?= \App\Core\I18n::get('global.items') ?> (CRUD Demo)</a></li>
                <li><a href="/contact/index" class="text-blue-600 hover:underline"><?= \App\Core\I18n::get('global.contact_us') ?></a></li>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="/admin/index" class="text-blue-600 hover:underline"><?= \App\Core\I18n::get('global.admin') ?></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Language Options</h2>
            <p class="text-gray-600 mb-4">Switch the application language below:</p>
            <div class="flex flex-wrap gap-3">
                <a href="/item/lang/en" class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">English</a>
                <a href="/item/lang/es" class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">Español</a>
                <a href="/item/lang/fr" class="px-4 py-2 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">Français</a>
                <a href="/item/lang/nl" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors">Nederlands</a>
            </div>
        </div>
    </div>
</div>
