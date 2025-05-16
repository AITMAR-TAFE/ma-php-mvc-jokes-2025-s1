<?php
/**
 * Read Joke View
 *
 * Filename:        read.view.php
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
                <h1 class="text-2xl font-bold">Joke Details</h1>
            </section>

            <section class="my-4 p-4 gap-8 justify-start">
                <?php if (isset($joke)): ?>
                    <p><strong>Title:</strong> <?= htmlspecialchars($joke->title) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($joke->category_name) ?></p>
                    <p><strong>Author's Full Name:</strong>
                        <?= htmlspecialchars($joke->given_name) . ' ' . htmlspecialchars($joke->family_name) ?>
                    </p>
                    <p><strong>Tags:</strong> <?= htmlspecialchars($joke->tags ?? 'None') ?></p>
                    <p><strong>Content:</strong></p>
                    <div class="mt-4"><?= htmlspecialchars_decode($joke->body) ?></div>
                <?php else: ?>
                    <p>Joke not found.</p>
                <?php endif; ?>
            </section>
        </article>
    </main>


<?php
loadPartial("footer");
