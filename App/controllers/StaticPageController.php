<?php
/**
 * StaticPageController
 *
 * Handles static pages such as home, contact, and about.
 *
 * Filename:        StaticPageController.php
 * Location:        App/Controllers
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    02/04/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 */


namespace App\controllers;

class StaticPageController
{
    /**
     * Show the home page
     *
     * @return void
     */
    public function index()
    {

        loadView('static/home');
    }


    /**
     * Show the about page
     *
     * @return void
     */
    public function about()
    {

        loadView('about');
    }


}