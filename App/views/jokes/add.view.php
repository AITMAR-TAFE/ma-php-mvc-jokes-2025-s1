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

loadPartial("header");
loadPartial('navigation');

?>
    <script src="https://cdn.ckeditor.com/ckeditor5/10.0.1/classic/ckeditor.js"></script>
    <script src="/public/assets/js/editor.js"></script>

    <main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg flex flex-col flex-grow">
        <article>
            <header class="bg-zinc-700 text-zinc-200 -mx-4 -mt-8 p-8 mb-8 flex">
                <h1 class="grow text-2xl font-bold ">Add Jokes</h1>

            </header>

            <section>
                <?= loadPartial('message') ?>

                <?= loadPartial('errors', [
                    'errors' => $errors ?? []
                ]) ?>

                <form id="JokesForm" method="POST" action="/jokes">

                    <h2 class="text-2xl font-bold mb-6 text-left text-gray-500">
                        Joke Information (fields for content, category, tags, and author)
                    </h2>

                    <div class="mb-4">
                        <label for="Title">Title:</label>
                        <input id="Title" type="text" name="title" placeholder="Joke Title"
                               class="w-full px-4 py-2 border rounded focus:outline-none"
                               value="<?= $jokes['title'] ?? '' ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="Category">Category:</label>
                        <select id="Category" name="category_id" class="w-full px-4 py-2 border rounded focus:outline-none">
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category->id) ?>">
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="0">No categories available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="Tag">Tag:</label>
                        <input id="Tag" type="text" name="tag" placeholder="Tags"
                               class="w-full px-4 py-2 border rounded focus:outline-none"
                               value="<?= $jokes['tags'] ?? '' ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="Author">Author:</label>
                        <input id="Author" type="text" name="author" placeholder="Author ID"
                               class="w-full px-4 py-2 border rounded focus:outline-none"
                               value="<?= $jokes['author_id'] ?? '' ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="Description">Content:</label>
                        <textarea id="content" name="content" placeholder="Joke Content"
                                  class="w-full px-4 py-2 border rounded focus:outline-none"
                        ><?= $jokes['body'] ?? '' ?></textarea>
                    </div>

                    <div class="grid grid-cols-4 gap-8">
                        <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2
                               rounded focus:outline-none flex justify-center">
                            <i class="fa fa-check inline-block mr-4"></i>
                            <span>Save</span>
                        </button>

                        <a href="/products"
                           class="text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2
                   rounded focus:outline-none flex justify-center">
                            <i class="fa fa-cancel inline-block mr-4"></i>
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>

            </section>

        </article>
    </main>

<?php
loadPartial("footer");

