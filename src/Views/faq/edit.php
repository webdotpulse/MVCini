<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center mb-6">
            <a href="/faq/admin" class="text-blue-600 hover:text-blue-800 mr-4 font-medium">&larr; Back to Admin</a>
            <h1 class="text-2xl font-semibold text-gray-900"><?= htmlspecialchars($title) ?></h1>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/faq/edit/<?= $faq['id'] ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                            <div class="mt-1">
                                <input type="text" name="question" id="question" value="<?= htmlspecialchars($faq['question'] ?? '') ?>" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <div class="mt-1">
                                <input type="text" name="category" id="category" value="<?= htmlspecialchars($faq['category'] ?? '') ?>" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="answer" class="block text-sm font-medium text-gray-700">Answer</label>
                            <div class="mt-1">
                                <textarea id="answer" name="answer" rows="6" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"><?= htmlspecialchars($faq['answer'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>