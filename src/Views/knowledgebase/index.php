<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8"><?= htmlspecialchars($title) ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="/knowledgebase/view/<?= htmlspecialchars($article['slug']) ?>" class="hover:text-blue-600">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?= htmlspecialchars(substr(strip_tags($article['content']), 0, 150)) ?>...
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <a href="/knowledgebase/view/<?= htmlspecialchars($article['slug']) ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Read more &rarr;
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($articles)): ?>
            <div class="col-span-full">
                <p class="text-gray-500 text-center py-8">No articles found in the Knowledge Base.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
