<?php

namespace App\controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;
use JetBrains\PhpStorm\NoReturn;
use League\HTMLToMarkdown\HtmlConverter;

class JokeController
{
    protected Database $db;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Browse a list of jokes
     *
     */
    public function browse(): void
    {
        $sql = "SELECT * FROM ma_php_mvc_jokes_2025_s1.jokes";

        $stmt = $this->db->query($sql);
        $jokes = $stmt->fetchAll();

        loadView('static/home', [
            'jokes' => $jokes // Ensure jokes are passed
        ]);
    }


}