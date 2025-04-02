<?php
/**
 * About Page View
 *
 * Filename:        about.view.php
 * Location:        /App/views/static
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
            <h2 class="text-3xl font-light">Application Overview</h2>
            <p>This application is a simple Vanilla PHP MVC project designed to demonstrate basic web application principles.</p>
        </section>

        <section class="my-4 p-4 gap-8 justify-start">
            <h2 class="text-2xl font-semibold">Developer Information</h2>
            <p><strong>Name:</strong> Martina Ait</p>
            <p><strong>Email:</strong> 20114816@tafe.wa.edu.au</p>
            <p><strong>Project:</strong> ma-php-mvc-jokes-2025-s1</p>
        </section>

        <section class="my-4 p-4 gap-8 justify-start">
            <h2 class="text-2xl font-semibold">Technology Stack</h2>
            <ul class="list-disc pl-6">
                <li><strong>Programming Language:</strong> PHP 8.x</li>
                <li><strong>Framework:</strong> Custom Vanilla PHP MVC</li>
                <li><strong>Database:</strong> MySQL / MariaDB</li>
                <li><strong>Server:</strong> Apache / Nginx</li>
                <li><strong>Supporting Systems:</strong> Composer (Dependency Management), PHPUnit (Testing), TailwindCSS (Styling)</li>
            </ul>
        </section>
    </article>
</main>

<?php
loadPartial('footer');
?>
