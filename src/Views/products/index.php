<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8"><?= htmlspecialchars($title) ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">
                        <?= $product['price'] ? '$' . number_format($product['price'], 2) : 'Contact for price' ?>
                    </span>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Buy Now
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <div class="col-span-full">
                <p class="text-gray-500 text-center py-8">No products found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
