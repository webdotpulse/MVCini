<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="/knowledgebase" class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center">
            &larr; Back to Knowledge Base
        </a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($article['title']) ?></h1>
        </div>
        <div class="px-4 py-5 sm:p-6 prose max-w-none text-gray-800">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>
    </div>
</div>
