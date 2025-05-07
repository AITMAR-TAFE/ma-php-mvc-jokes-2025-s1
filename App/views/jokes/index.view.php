<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        index.view.php
 * Location:        ${FILE_LOCATION}
 * Project:         SaaS-Vanilla-MVC
 * Date Created:    20/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
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
                </tr>
                </thead>
                <tbody>
                <?php if (isset($jokes) && count($jokes) > 0): ?>
                    <?php foreach ($jokes as $joke): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="/jokes/<?= $joke->id ?>" class="text-blue-500 hover:underline">
                                    <?= htmlspecialchars($joke->title) ?>
                                </a>
                            </td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->category_name) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->tags ?? 'None') ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($joke->nickname) ?></td>
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