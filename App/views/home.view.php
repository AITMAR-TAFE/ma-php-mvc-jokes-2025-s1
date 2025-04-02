<?php
/**
 * Home Page View
 *
 * Filename:        home.view.php
 * Location:        /App/views
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    02/04/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

loadPartial('header');
loadPartial('navigation');

?>

<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-lg">
    <article class="grid grid-cols-1">

        <section class="my-4 p-4 gap-8 justify-start">
            <p class="text-3xl font-light">Welcome to the Home Page</p>
            <p>Here's a joke for you: <strong><?= htmlspecialchars($data['randomJoke'] ?? 'Jokes feature coming soon!') ?></strong></p>
            <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">New Joke</button>
        </section>
    </article>


</main>


<?php
loadPartial('footer');
?>
