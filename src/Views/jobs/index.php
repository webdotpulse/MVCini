<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8"><?= htmlspecialchars($title) ?></h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            <?php foreach ($jobs as $job): ?>
                <li>
                    <a href="#" class="block hover:bg-gray-50 transition-colors">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-blue-600 truncate">
                                    <?= htmlspecialchars($job['title']) ?>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Open
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <?= htmlspecialchars($job['location'] ?: 'Remote') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <?= nl2br(htmlspecialchars($job['description'])) ?>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>

            <?php if (empty($jobs)): ?>
                <li class="px-4 py-8 text-center text-gray-500">
                    There are currently no job openings. Please check back later.
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
