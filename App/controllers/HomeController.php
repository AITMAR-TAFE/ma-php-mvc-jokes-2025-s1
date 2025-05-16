<?php
/**
 * Home Controller
 *
 *
 * Filename:        HomeController.php
 * Location:
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    10/05/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

namespace App\controllers;

use Framework\Database;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /*
     * Show the latest products
     *
     * @return void
     */
    public function index()
    {
        $simpleRandomQuery = 'SELECT * FROM jokes ORDER BY RAND() LIMIT 1';
        $randomJoke = $this->db->query($simpleRandomQuery)->fetch();

        $jokesCount = $this->db->query('SELECT count(id) as total FROM jokes ')
            ->fetch();

        $userCount = $this->db->query('SELECT count(id) as total FROM users')
            ->fetch();

        $userNickname = $_SESSION['user']['nickname'] ?? null;

        loadView('home', [
            'randomJoke' => $randomJoke,
            'userNickname' => $userNickname,
            'jokeCount' => $jokesCount ->total,
            'userCount' => $userCount->total
        ]);
    }



}