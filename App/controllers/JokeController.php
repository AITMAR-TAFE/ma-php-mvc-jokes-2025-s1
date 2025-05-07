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
        Authorisation::requireLogin();

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search) {
            $query = "SELECT jokes.*, categories.name AS category_name, users.nickname 
                  FROM jokes 
                  JOIN categories ON jokes.category_id = categories.id
                  JOIN users ON jokes.author_id = users.id
                  WHERE jokes.body LIKE :search";

            $params = [
                'search' => "%{$search}%",
            ];

            $stmt = $this->db->query($query, $params);
        } else {
            $sql = "SELECT jokes.*, categories.name AS category_name, users.nickname 
                FROM jokes 
                JOIN categories ON jokes.category_id = categories.id
                JOIN users ON jokes.author_id = users.id";
            $stmt = $this->db->query($sql);
        }

        $jokes = $stmt->fetchAll();

        loadView('jokes/index', [
            'jokes' => $jokes,
            'search' => $search
        ]);
    }

    public function read(int $id): void
    {
//        $sql = "SELECT jokes.*, categories.name AS category_name, users.name AS author
//            FROM jokes
//            JOIN categories ON jokes.category_id = categories.id
//            JOIN users ON jokes.user_id = users.id
//            WHERE jokes.id = :id";
//
//        $stmt = $this->db->query($sql, [':id' => $id]);
//        $joke = $stmt->fetch();
//
//        if (!$joke) {
//            http_response_code(404);
//            echo "Joke not found.";
//            return;
//        }
//
//        loadView('jokes/read', ['joke' => $joke]);
    }


}