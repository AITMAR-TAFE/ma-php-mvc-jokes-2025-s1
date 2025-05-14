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

    public function read($id): void
    {
        Authorisation::requireLogin();

        $id = (int)$id;

        $query = "SELECT jokes.*, categories.name AS category_name, users.given_name, users.family_name
              FROM jokes
              JOIN categories ON jokes.category_id = categories.id
              JOIN users ON jokes.author_id = users.id
              WHERE jokes.id = :id";

        $params = [
            'id' => $id,
        ];

        $stmt = $this->db->query($query, $params);

        $joke = $stmt->fetch();

        if ($joke) {
            loadView('jokes/read', [
                'joke' => $joke,
            ]);
        } else {
            loadView('error/not_found');
        }
    }

    public function add(): void
    {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();

        loadView('jokes/add', ['categories' => $categories]);
    }

    /**
     * Store data in database
     *
     * @return void
     * @throws \Exception
     */
    #[NoReturn] public function store()
    {
        $allowedFields = ['title','category_id','author_id','tags','body'];

        $newJokeData = array_intersect_key($_POST, array_flip($allowedFields));

        $newJokeData['author_id'] = Session::get('user')['id'];

        $newJokeData = array_map('sanitize', $newJokeData);

        $requiredFields = ['title','tags','body'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newJokeData[$field]) || !Validation::string($newJokeData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (empty($newJokeData['category_id']) || !is_numeric($newJokeData['category_id'])) {
            $errors['category_id'] = 'Category is required and must be valid.';
        }

        if (!empty($errors)) {
            // Reload view with errors
            loadView('jokes/add', [
                'errors' => $errors,
                'jokes' => $newJokeData
            ]);
        }


        // accept the Markdown from the form and store as HTML
        if (isset($newJokeData['body'])) {

            $description = $newJokeData['body'] ?? '';
            $markdown = htmlToMarkdown($description);
            $newJokeData['body'] = $markdown;
        }

        // Save the submitted data
        $fields = [];

        foreach ($newJokeData as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(', ', $fields);

        $values = [];

        foreach ($newJokeData as $field => $value) {
            // Convert empty strings to null
            if ($value === '') {
                $newJokeData[$field] = null;
            }

            $values[] = ':' . $field;
        }

        $values = implode(', ', $values);

        $insertQuery = "INSERT INTO jokes ({$fields}) VALUES ({$values})";

        $this->db->query($insertQuery, $newJokeData);

        Session::setFlashMessage('success_message', 'Joke created successfully');

        loadView('jokes/add', [
            'success_message' => Session::get('success_message'),
            'jokes' => $newJokeData
        ]);
    }


}