<?php
/**
 * Index Page View
 *
 * Filename:        index.view.php
 * Location:        /App/views/jokes
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    02/04/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

/* Load HTML header and navigation areas */
loadPartial("header");
loadPartial('navigation');

?>
<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-lg">
    <article class="grid grid-cols-1">
        <section class="my-4 p-4 gap-8 justify-start">
            <h1>Jokes List</h1>
            <a href="/jokes/add" class="btn btn-primary">Add Joke</a>
            <?= loadPartial('message') ?>

            <?= loadPartial('errors', [
                'errors' => $errors ?? []
            ]) ?>
        </section>
        <section class="my-4 p-4 gap-8 justify-start">
            <!-- Search Form -->
            <form method="get" action="" class="my-4">
                <input type="text" name="search" placeholder="Search jokes..." value="<?= htmlspecialchars($jokes['search'] ?? '') ?>" class="px-4 py-2 border rounded">
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded">Search</button>
            </form>
        </section>
        <section class="my-4 p-4 gap-8 justify-start">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Title</th>
                    <th class="border border-gray-300 px-4 py-2">Category</th>
                    <th class="border border-gray-300 px-4 py-2">Tags</th>
                    <th class="border border-gray-300 px-4 py-2">Author</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($jokes) && count($jokes) > 0): ?>
                    <?php foreach ($jokes as $joke): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->title) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->category_name) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->tags ?? 'None') ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->nickname ?? '') ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="/jokes/edit/<?= $joke->id ?>" class="btn btn-secondary">Edit</a>
                                <form action="/jokes/delete/<?= $joke->id ?>" method="POST" class="inline-block">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="/jokes/read<?= $joke->id ?>" class="btn btn-secondary">Read</a>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center border border-gray-300 px-4 py-2">No jokes found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </article>
</main>
<?php
loadPartial("footer");