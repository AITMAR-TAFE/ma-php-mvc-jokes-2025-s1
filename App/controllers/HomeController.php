<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        HomeController.php
 * Location:
 * Project:         SaaS-Vanilla-MVC
 * Date Created:    20/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
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

        $userId = $_SESSION['user']['id'] ?? null;
        $userNickname = $_SESSION['user']['nickname'] ?? null;
        $userId = (int) $userId;
        var_dump($userId);

        loadView('home', [
            'randomJoke' => $randomJoke,
            'userNickname' => $userNickname,
            'jokeCount' => $jokesCount ->total,
            'userCount' => $userCount->total
        ]);
    }

    /*
     * Show the latest products
     *
     * @return void
     */
    public function dashboard()
    {

    }



}