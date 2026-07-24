<div class="text-center mb-12">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-4 tracking-tight"><?= \App\Core\I18n::get('global.welcome') ?></h2>
    <p class="text-gray-500 text-xl max-w-2xl mx-auto">A custom, lightweight MVC framework built with native PHP 8 and MySQL.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-blue-50 p-8 rounded-2xl border border-blue-100 hover:shadow-md transition-shadow">
        <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            Explore Features
        </h3>
        <ul class="space-y-3 text-blue-800">
            <li class="flex items-center">
                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                <a href="/item/index" class="hover:text-blue-600 font-medium transition-colors"><?= \App\Core\I18n::get('global.items') ?> (CRUD Demo)</a>
            </li>
            <li class="flex items-center">
                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                <a href="/contact/index" class="hover:text-blue-600 font-medium transition-colors"><?= \App\Core\I18n::get('global.contact_us') ?></a>
            </li>
            <?php if(\App\Core\Session::has('user_id') && \App\Core\Session::get('role') === 'admin'): ?>
                <li class="flex items-center">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                    <a href="/admin/index" class="hover:text-blue-600 font-medium transition-colors"><?= \App\Core\I18n::get('global.admin') ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 hover:shadow-md transition-shadow">
        <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
            Language Options
        </h3>
        <p class="text-slate-600 mb-6 text-sm">Switch the application language dynamically to see i18n in action:</p>
        <div class="flex flex-wrap gap-3">
            <a href="/lang/en" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-100 hover:border-slate-300 font-medium shadow-sm transition-all focus:ring-2 focus:ring-slate-200 focus:outline-none">English</a>
            <a href="/lang/es" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-100 hover:border-slate-300 font-medium shadow-sm transition-all focus:ring-2 focus:ring-slate-200 focus:outline-none">Español</a>
            <a href="/lang/fr" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-100 hover:border-slate-300 font-medium shadow-sm transition-all focus:ring-2 focus:ring-slate-200 focus:outline-none">Français</a>
            <a href="/lang/nl" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-100 hover:border-slate-300 font-medium shadow-sm transition-all focus:ring-2 focus:ring-slate-200 focus:outline-none">Nederlands</a>
        </div>
    </div>
</div>