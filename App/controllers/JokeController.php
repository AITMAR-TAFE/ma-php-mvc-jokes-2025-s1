<?php
/**
 * Joke Controller
 *
 * This controller provides functionality for managing jokes, including:
 * - Browsing a list of jokes
 * - Reading a specific joke
 * - Adding new jokes
 * - Editing existing jokes
 * - Deleting jokes
 *
 * Filename:        JokeController.php
 * Location:        App/Controllers
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    10/05/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

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
     * @return void
     * @throws \Exception
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
    /**
     * Read a specific joke
     *
     *
     * @param int $id The ID of the joke to fetch.
     * @return void
     * @throws \Exception
     */
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
    /**
     * Add a new joke
     *
     * @return void
     * @throws \Exception
     */
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
    #[NoReturn] public function store() :void
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

    /**
     * Edit an existing joke
     *
     * @param array $params Parameters including the joke ID.
     * @return void
     * @throws \Exception
     */
    public function edit(array $params): void
    {
        $id = $params['id'] ?? '';
        $userId = Session::get('user')['id'];

        $params = ['id' => $id];
        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();

        if (!$joke) {
            ErrorController::notFound('Joke not found');
            return;
        }
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();

        if ($joke->author_id !== $userId) {
            Session::setFlashMessage('error_message', 'You are not authorized to update this joke');
            redirect('/jokes/');
        }

        $converter = new HtmlConverter();
        $joke->body = $converter->convert($joke->body ?? '');

        loadView('jokes/edit', [
            'joke' => $joke,
            'categories'=>$categories
        ]);
    }

    /**
     * Update a joke
     *
     * @param array $params
     * @return null
     * @throws \Exception
     */
    #[NoReturn] public function update(array $params): null
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();


        $allowedFields = ['title', 'body', 'tags'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields)) ?? [];

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'body', 'tags'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('jokes/edit', [
                'joke' => $joke,
                'errors' => $errors
            ]);
            exit;
        }

        if (isset($updateValues['body'])) {
            $body = $updateValues['body'] ?? '';
            $markdown = htmlToMarkdown($body);
            $updateValues['body'] = $markdown;
        }

        // Submit to database
        $updateFields = [];

        foreach (array_keys($updateValues) as $field) {
            $updateFields[] = "{$field} = :{$field}";
        }

        $updateFields = implode(', ', $updateFields);

        $updateQuery = "UPDATE jokes SET $updateFields WHERE id = :id";

        $updateValues['id'] = $id;
        if (isset($updateValues['tags'])) {
            $updateValues['tags'] = sanitize($updateValues['tags']);
        }

        $this->db->query($updateQuery, $updateValues);

        // Set flash message
        Session::setFlashMessage('success_message', 'joke updated');

        redirect('/jokes/' . $id);

    }

    /**
     * Delete a joke
     *
     * @param array $params Parameters including the joke ID.
     * @return void
     * @throws \Exception
     */
    public function delete($params):void
    {
        $id = $params['id'];
        $userId = Session::get('user')['id'];

        $query = "SELECT author_id FROM jokes WHERE id = :id";
        $params = [
            'id' => $id
        ];

        $stmt = $this->db->query($query, $params);
        $joke = $stmt->fetch();

        if ($joke && $joke->author_id == $userId) {
            $deleteQuery = "DELETE FROM jokes WHERE id = :id";
            $deleteParams = [
                'id' => $id
            ];

            $deleteStmt = $this->db->query($deleteQuery, $deleteParams);

            if ($deleteStmt->rowCount() > 0) {
                Session::setFlashMessage('success_message', 'Joke deleted successfully.');
            } else {
                Session::setFlashMessage('error_message', 'Joke not found.');
            }
        } else {
            Session::setFlashMessage('error_message', 'You are not authorized to delete this joke.');
        }
        redirect('/jokes');
    }

}