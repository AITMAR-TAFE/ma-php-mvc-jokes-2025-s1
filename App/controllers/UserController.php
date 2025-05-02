<?php
/**
 * User Controller
 *
 * Provides the Register, Login and Logout capabilities
 * of the application
 *
 * Filename:        UserController.php
 * Location:        App/Controllers
 * Project:         ma-php-mvc-jokes-2025-s1
 * Date Created:    02/04/2025
 *
 * Author:          Martina Ait <20114816@tafe.wa.edu.au>
 *
 */

namespace App\controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserController
{

    /* Properties */

    /**
     * @var Database
     */
    protected $db;

    /**
     * UserController Constructor
     *
     * Instantiate the database connection for use in this class
     * storing the connection in the protected <code>$db</code>
     * property.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the login page
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Show the register page
     *
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Store user in database
     *
     * @return void
     */
    public function store()
    {
        $givenName = $_POST['given_name'] ?? null;
        $familyName = $_POST['family_name'] ?? null;  // Family name is optional
        $nickname = $_POST['nickname'] ?? null;
        $email = $_POST['email'] ?? null;
        $city = $_POST['city'] ?? 'Unknown';  // Default to 'Unknown'
        $state = $_POST['state'] ?? 'Unknown';  // Default to 'Unknown'
        $country = $_POST['country'] ?? 'Unknown';  // Default to 'Unknown'
        $password = $_POST['password'] ?? null;
        $passwordConfirmation = $_POST['password_confirmation'] ?? null;

        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($givenName, 2, 50)) {
            $errors['given_name'] = 'Given name must be between 2 and 50 characters';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        // Set nickname to given name if not provided
        if (empty($nickname)) {
            $nickname = $givenName;
        }

        if (!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'given_name' => $givenName,
                    'family_name' => $familyName,
                    'nickname' => $nickname,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country
                ]
            ]);
            exit;
        }

        // Check if email exists
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'That email already exists';
            loadView('users/create', [
                'errors' => $errors
            ]);
            exit;
        }

        // Create user account
        $params = [
            'given_name' => $givenName,
            'family_name' => $familyName,
            'nickname' => $nickname,  // Add nickname
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'country' => $country,    // Add country
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (given_name, family_name, nickname, email, city, state, country, password) VALUES (:given_name, :family_name, :nickname, :email, :city, :state, :country, :password)', $params);

        // Get new user ID
        $userId = $this->db->conn->lastInsertId();

        // Set user session
        Session::set('user', [
            'id' => $userId,
            'nickname' => $nickname,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'country' => $country
        ]);

        redirect('/');
    }

    /**
     * Logout a user and kill session
     *
     * @return void
     */
    public function logout()
    {
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }

    /**
     * Authenticate a user with email and password
     *
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        // Check for errors
        if (!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check for email
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check if password is correct
        if (!password_verify($password, $user->password)) {
            $errors['email'] = 'Incorrect credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Set user session
        Session::set('user', [
            'id' => $user->id,
            'nickname' => $user->nickname,
            'given_name' => $user->given_name,
            'family_name' => $user->family_name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
            'country' => $user->country
        ]);

        redirect('/');
    }

    public function update()
    {
        // Check if the user is authenticated
        $userId = Session::get('user')['id'] ?? null;
        if (!$userId) {
            redirect('/login');
            exit;
        }

        // Get user data from POST
        $givenName = $_POST['given_name'] ?? null;
        $familyName = $_POST['family_name'] ?? null;
        $nickname = $_POST['nickname'] ?? null;
        $city = $_POST['city'] ?? 'Unknown';  // Default to 'Unknown'
        $state = $_POST['state'] ?? 'Unknown';  // Default to 'Unknown'
        $country = $_POST['country'] ?? 'Unknown';  // Default to 'Unknown'

        $errors = [];

        // Validation
        if (!Validation::string($givenName, 2, 50)) {
            $errors['given_name'] = 'Given name must be between 2 and 50 characters';
        }

        // Set nickname to given name if not provided
        if (empty($nickname)) {
            $nickname = $givenName;
        }

        if (!empty($errors)) {
            loadView('users/edit', [
                'errors' => $errors,
                'user' => [
                    'given_name' => $givenName,
                    'family_name' => $familyName,
                    'nickname' => $nickname,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country
                ]
            ]);
            exit;
        }

        // Update user in the database
        $params = [
            'given_name' => $givenName,
            'family_name' => $familyName,
            'nickname' => $nickname,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'user_id' => $userId
        ];

        $this->db->query('UPDATE users SET given_name = :given_name, family_name = :family_name, nickname = :nickname, city = :city, state = :state, country = :country, updated_at = CURRENT_TIMESTAMP WHERE id = :user_id', $params);

        // Update session
        Session::set('user', [
            'id' => $userId,
            'nickname' => $nickname,
            'city' => $city,
            'state' => $state,
            'country' => $country
        ]);

        redirect('/profile');
    }

}

