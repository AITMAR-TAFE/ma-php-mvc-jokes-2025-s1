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

        $userNickname = null;
        $jokeCount = 0;
        $userCount = 0;

        if (isset($_SESSION['user_id'])) {
            $userQuery = 'SELECT nickname FROM users WHERE id = :id';
            $user = $this->db->query($userQuery, ['id' => $_SESSION['user_id']])->fetch();
            $userNickname = $user['nickname'];

            // Get the number of jokes and users
            $jokeCountQuery = 'SELECT COUNT(*) FROM jokes';
            $userCountQuery = 'SELECT COUNT(*) FROM users';

            $jokeCount = $this->db->query($jokeCountQuery)->fetchColumn();
            $userCount = $this->db->query($userCountQuery)->fetchColumn();
        }

        loadView('home', [
            'randomJoke' => $randomJoke,
            'userNickname' => $userNickname,
            'jokeCount' => $jokeCount,
            'userCount' => $userCount
        ]);
    }

    /*
     * Show the latest products
     *
     * @return void
     */
    public function dashboard()
    {
        $lastSixQuery = 'SELECT * FROM jokes ORDER BY created_at DESC LIMIT 0,6';
        $simpleRandomSixQuery = 'SELECT * FROM jokes ORDER BY RAND() LIMIT 0,6';

        $jokes = $this->db->query($simpleRandomSixQuery)
            ->fetchAll();

        $jokesCount = $this->db->query('SELECT count(id) as total FROM jokes ')
            ->fetch();

        $userCount = $this->db->query('SELECT count(id) as total FROM users')
            ->fetch();

        loadView('dashboard', [
            'jokes' => $jokes,
            'jokesCount' => $jokesCount,
            'userCount' => $userCount,
        ]);
    }



}