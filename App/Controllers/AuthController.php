<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Users;

class AuthController extends AppController
{
    protected $Users;

    public function __construct()
    {
        parent::__construct();
        $this->Users = new Users();
    }

    public function login(): void
    {
        // Check if user is already logged in - safely handle missing role
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_role'])) {
            $this->redirectByRole($_SESSION['user_role']);
            return; // Important: prevent further execution
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $identifier = trim($_POST['identifier'] ?? '');
            $password   = $_POST['password']        ?? '';

            // Rate limiting key - use email for rate limiting
            $key = 'login_attempts_' . md5($identifier) . '_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

            $_SESSION[$key] = $_SESSION[$key] ?? 0;

            if ($_SESSION[$key] >= 5) {
                $error = 'Too many failed attempts. Please wait a few minutes.';
            } elseif (empty($identifier) || empty($password)) {
                $error = 'All fields are required.';
            } else {
                $user = $this->Users->findByIdentifier($identifier);

                if (!$user) {
                    $_SESSION[$key]++;
                    $error = 'Account not found. Check your credentials.';
                } elseif (!empty($user->status) && $user->status !== 'active') {
                    $error = 'Your account is inactive. Contact the librarian.';
                } elseif (!password_verify($password, $user->password ?? $user->password_hash)) {
                    $_SESSION[$key]++;
                    $remaining = 5 - $_SESSION[$key];
                    $error = "Incorrect password. {$remaining} attempt(s) remaining.";
                } else {
                    // Successful login
                    $_SESSION[$key] = 0;
                    session_regenerate_id(true);

                    $_SESSION['user_id']   = $user->id;
                    $_SESSION['user_name'] = $user->fullname ?? $user->full_name ?? 'User';
                    $_SESSION['user_role'] = $user->role;   // Use role from database (Admin, Librarian, Member)
                    $_SESSION['logged_in'] = true;

                    $this->redirectByRole($user->role);
                    return; // Prevent rendering login view after redirect
                }
            }
        }

        // Render login form only if not redirected
        View::setLayout('login');
        View::render('Auth/login', [
            'error' => $error,
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'Auth/login');
        exit();
    }

    private function redirectByRole(string $role): void
    {
        $map = [
            'admin'     => 'Dashboard/admin',
            'Admin'     => 'Dashboard/admin',
            'librarian' => 'Dashboard/librarian',
            'Librarian' => 'Dashboard/librarian',
            'member'    => 'Dashboard/member',
            'Member'    => 'Dashboard/member',
        ];

        $path = $map[$role] ?? 'Auth/login';

        header('Location: ' . BASE_URL . $path);
        exit();
    }
}