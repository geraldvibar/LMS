<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Users;

class UsersController extends AppController
{
    protected $Users;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->Users = new Users();
    }

    public function index(): void
    {
        $role   = $_POST['role'] ?? 'all';
        $search = $_POST['q']    ?? '';

        View::setLayout('default');
        View::render('Users/index', [
            'users'  => $this->Users->getAll($role, $search),
            'role'   => $role,
            'search' => $search,
            'title'  => 'User Management',
        ]);
    }

    public function add(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name']     ?? '');
            $role     = trim($_POST['role']     ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            $phone    = trim($_POST['phone']    ?? '');
            $address  = trim($_POST['address']  ?? '');

            if (!$name || !$role || !$email || !$password) {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } else {
                $userId = $this->Users->add([
                    'name' => $name, 'role' => $role,
                    'email' => $email, 'password' => $password,
                    'phone' => $phone, 'address' => $address,
                ]);

                if ($userId) {
                    $_SESSION['flash_success'] = 'User added successfully.';
                    header('Location: ' . BASE_URL . 'Users/index');
                    exit();
                }
                $error = 'Failed to add user. Email may already exist.';
            }
        }

        View::setLayout('default');
        View::render('Users/add', [
            'error' => $error,
            'old'   => $_POST,
            'title' => 'Add User',
        ]);
    }

    public function edit($id = null): void
    {
        $user = $this->Users->getById($id);

        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: ' . BASE_URL . 'Users/index');
            exit();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name']     ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            $phone    = trim($_POST['phone']    ?? '');
            $address  = trim($_POST['address']  ?? '');

            if (!$name || !$email) {
                $error = 'Name and email are required.';
            } elseif (!empty($password) && strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } else {
                if ($this->Users->edit([
                    'id' => $id, 'name' => $name,
                    'email' => $email, 'password' => $password,
                    'phone' => $phone, 'address' => $address,
                    'role' => $user->role,
                ])) {
                    $_SESSION['flash_success'] = 'User updated successfully.';
                    header('Location: ' . BASE_URL . 'Users/index');
                    exit();
                }
                $error = 'Failed to update user.';
            }
        }

        View::setLayout('default');
        View::render('Users/edit', [
            'user'  => $user,
            'error' => $error,
            'old'   => $_POST ?: (array)$user,
            'title' => 'Edit User',
        ]);
    }

    public function delete($id = null): void
    {
        if ($this->Users->hasActiveLoanRecords($id)) {
            $_SESSION['flash_error'] = 'Cannot delete user with active loan records.';
        } else {
            // Delete loan records first to avoid foreign key constraint error
            $borrowRecords = new \App\Models\BorrowRecords();
            $borrowRecords->deleteByUser($id);
            
            if ($this->Users->delete($id)) {
                $_SESSION['flash_success'] = 'User deleted.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete user.';
            }
        }
        header('Location: ' . BASE_URL . 'Users/index');
        exit();
    }
}