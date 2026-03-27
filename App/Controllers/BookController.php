<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Books;

class BookController extends AppController
{
    protected $Books;

    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->Books = new Books();
    }

    public function index(): void
    {
        $this->requireLibrarianOrHigher();
        $search = $_POST['q'] ?? '';

        View::setLayout('default');
        View::render('Book/index', [
            'books'  => $this->Books->getAll($search),
            'search' => $search,
            'title'  => 'Book Management',
        ]);
    }

    public function add(): void
    {
        $this->requireLibrarianOrHigher();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $isbn = trim($_POST['isbn'] ?? '');
            
            // Auto-generate ISBN if empty
            if (empty($isbn)) {
                $isbn = $this->generateUniqueISBN();
            }

            $data = [
                'isbn'             => $isbn,
                'title'            => trim($_POST['title'] ?? ''),
                'author'           => trim($_POST['author'] ?? ''),
                'category'         => trim($_POST['genre'] ?? ''),
                'total_copies'     => (int)($_POST['copies'] ?? 1),
                'available_copies' => (int)($_POST['copies'] ?? 1),
            ];

            if (empty($data['title'])) {
                $error = 'Title is required.';
            } else {
                if ($this->Books->create($data)) {
                    $_SESSION['flash_success'] = 'Book added successfully.';
                    header('Location: ' . BASE_URL . 'Book/index');
                    exit();
                }
                $error = 'Failed to add book.';
            }
        }

        View::setLayout('default');
        View::render('Book/add', [
            'error' => $error,
            'old'   => $_POST,
            'title' => 'Add Book',
        ]);
    }

    public function edit($id = null): void
    {
        $this->requireLibrarianOrHigher();

        $book = $this->Books->getById($id);
        if (!$book) {
            $_SESSION['flash_error'] = 'Book not found.';
            header('Location: ' . BASE_URL . 'Book/index');
            exit();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $copies       = (int)($_POST['copies'] ?? 1);
            $activeBorrows = $this->Books->getActiveBorrowCount($id);
            $available    = max(0, $copies - $activeBorrows);

            $isbn = trim($_POST['isbn'] ?? '');
            
            // Auto-generate ISBN if empty
            if (empty($isbn)) {
                $isbn = $this->generateUniqueISBN();
            }

            $data = [
                'isbn'             => $isbn,
                'title'            => trim($_POST['title'] ?? ''),
                'author'           => trim($_POST['author'] ?? ''),
                'category'         => trim($_POST['genre'] ?? ''),
                'total_copies'     => $copies,
                'available_copies' => $available,
            ];

            if (empty($data['title'])) {
                $error = 'Title is required.';
            } else {
                if ($this->Books->update($id, $data)) {
                    $_SESSION['flash_success'] = 'Book updated successfully.';
                    header('Location: ' . BASE_URL . 'Book/index');
                    exit();
                }
                $error = 'Failed to update book.';
            }
        }

        View::setLayout('default');
        View::render('Book/edit', [
            'book'  => $book,
            'error' => $error,
            'old'   => $_POST ?: (array)$book,
            'title' => 'Edit Book',
        ]);
    }

    public function delete($id = null): void
    {
        $this->requireAdmin();

        if ($this->Books->getActiveBorrowCount($id) > 0) {
            $_SESSION['flash_error'] = 'Cannot delete — book has active borrows.';
        } else {
            // Delete loan records first to avoid foreign key constraint error
            $borrowRecords = new \App\Models\BorrowRecords();
            $borrowRecords->deleteByBook($id);
            
            if ($this->Books->delete($id)) {
                $_SESSION['flash_success'] = 'Book deleted successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete book.';
            }
        }

        header('Location: ' . BASE_URL . 'Book/index');
        exit();
    }

    /**
     * Generate a unique ISBN
     * Format: AUTO-{timestamp}-{random}
     */
    private function generateUniqueISBN(): string
    {
        do {
            $isbn = time() . '-' . mt_rand(1000, 9999);
            $exists = $this->Books->findByAccessionOrId($isbn);
        } while ($exists);

        return $isbn;
    }

    /**
     * Member books view - read-only with search
     */
    public function browse(): void
    {
        $search = $_POST['q'] ?? '';

        View::setLayout('default');
        View::render('Book/browse', [
            'books'  => $this->Books->getAll($search),
            'search' => $search,
            'title'  => 'Browse Books',
        ]);
    }
}