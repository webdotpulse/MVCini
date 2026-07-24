<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8"><?= htmlspecialchars($title) ?></h1>
    <div class="space-y-6">
        <?php foreach ($faqs as $faq): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?= htmlspecialchars($faq['question']) ?>
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Category: <?= htmlspecialchars($faq['category']) ?>
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                        </dd>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($faqs)): ?>
            <p class="text-gray-500">No FAQs found.</p>
        <?php endif; ?>
    </div>
</div>
