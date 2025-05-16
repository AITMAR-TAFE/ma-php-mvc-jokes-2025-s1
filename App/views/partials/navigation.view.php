<?php
/**
 * Page 'Header' and Navigation
 *
 * Filename:        navigation.view.php
 * Location:        App/views/partials
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    10/05/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

use Framework\Middleware\Authorise;

$authenticated = new Authorise();
$userNickname = $_SESSION['user']['nickname'] ?? null;
?>

<header class="bg-gray-950 text-gray-200 p-4 flex-grow-0 flex flex-row align-middle content-center mb-4">
    <h1 class="flex-0 w-32 text-xl p-4 ">
        <a href="#"
           class="py-4 px-4 -mx-4 -my-4 font-display font-bold rounded
             text-prussianblue-500 hover:text-black
             hover:bg-prussianblue-500
                 transition ease-in-out duration-700">
            MVC
        </a>
    </h1>
    <nav class="flex flex-row py-4 flex-grow ml-8">
        <ul class="flex flex-row gap-4 flex-grow">
            <li>
                <a href="/"
                   class="pb-2 px-1 text-gray-400 hover:text-gray-300
                     border-0 border-b-2 border-b-prussianblue-500 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                    Home
                </a>
            </li>

            <?php
            if ($authenticated->isAuthenticated()) {
                ?>
<!--                <li>-->
<!--                    <a href="/dashboard"-->
<!--                       class="pb-2 px-1 text-gray-400 hover:text-gray-300-->
<!--                     border-0 border-b-2 hover:border-b-gray-500-->
<!--                     transition ease-in-out duration-500">-->
<!--                        Dashboard-->
<!--                    </a>-->
<!--                </li>-->
                <?php
            }
            ?>

            <li>
                <a href="/jokes"
                   class="pb-2 px-1 text-gray-400 hover:text-gray-300
                     border-0 border-b-2 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                    Jokes
                </a>
            </li>
            <li>
                <a href="/about"
                   class="pb-2 px-1 text-gray-400 hover:text-gray-300
                     border-0 border-b-2 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                    About
                </a>
            </li>
<!--            <li>-->
<!--                <a href="#"-->
<!--                   class="pb-2 px-1 text-gray-400 hover:text-gray-300-->
<!--                     border-0 border-b-2 hover:border-b-gray-500-->
<!--                     transition ease-in-out duration-500">-->
<!--                    Link 1-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#"-->
<!--                   class="pb-2 px-1 text-gray-400 hover:text-gray-300-->
<!--                     border-0 border-b-2 hover:border-b-gray-500-->
<!--                     transition ease-in-out duration-500">-->
<!--                    Link 2-->
<!--                </a>-->
<!--            </li>-->
        </ul>

        <ul class="flex flex-row gap-4">
            <?php
            if (!$authenticated->isAuthenticated()) {
                ?>
                <li>
                    <form method="GET" action="/auth/login" class="">
                        <button class="pb-2 px-1 text-sm text-gray-400 hover:text-gray-300
                     border-0 border-b-2 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                            <i class="fa fa-door-open"></i> Login
                        </button>
                    </form>
                </li>
                <li>
                    <form method="GET" action="/auth/register" class="">
                        <button class="pb-2 px-1 text-sm text-gray-400 hover:text-gray-300
                     border-0 border-b-2 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                            <i class="fa fa-user-plus"></i> Register
                        </button>
                    </form>
                </li>
                <?php
            } else {
                ?>
                <li>
                    <form method="POST" action="/auth/logout" class="">
                        <button class="pb-2 px-1 text-sm text-gray-400 hover:text-gray-300
                     border-0 border-b-2 hover:border-b-gray-500
                     transition ease-in-out duration-500">
                            <i class="fa fa-door-closed"></i> Logout
                        </button>
                    </form>
                </li>
                <li>
                    <p><?= htmlspecialchars($userNickname) ?></p>
                </li>
                <?php
            }
            ?>

            <li>
                <form method="GET" action="#" class="block mx-5 flex">
                    <input type="text" name="keywords" placeholder="Product search..."
                           class="w-full md:w-auto px-4 py-1 border border-gray-800 focus:outline-none focus:border-b-gray-500"/>
                    <button class="w-full md:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 focus:outline-none transition ease-in-out duration-500">
                        <i class="fa fa-search"></i> Search
                    </button>
                </form>
            </li>

        </ul>

    </nav>
</header>