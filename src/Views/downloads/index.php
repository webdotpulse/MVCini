<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8"><?= htmlspecialchars($title) ?></h1>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            <?php foreach ($downloads as $download): ?>
                <li>
                    <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                        <div class="text-sm font-medium text-blue-600 truncate">
                            <?= htmlspecialchars($download['title']) ?>
                        </div>
                        <div class="ml-2 flex-shrink-0 flex">
                            <a href="<?= htmlspecialchars($download['file_url']) ?>" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200" download>
                                Download
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($downloads)): ?>
                <li class="px-4 py-4 sm:px-6 text-gray-500">
                    No downloads available at this time.
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
