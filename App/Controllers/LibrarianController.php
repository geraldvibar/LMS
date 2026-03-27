<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Users;
use App\Models\Books;
use App\Models\BorrowRecords;

class LibrarianController extends AppController
{
    protected $Users;
    protected $Books;
    protected $BorrowRecords;

    public function __construct()
    {
        parent::__construct();
        $this->requireLibrarianOrHigher();
        $this->Users         = new Users();
        $this->Books         = new Books();
        $this->BorrowRecords = new BorrowRecords();
    }

    // Lookup student
    public function lookup(): void
    {
        $q      = trim($_POST['q'] ?? '');
        $error  = null;
        $student = null;
        $borrowed = [];

        if ($q) {
            $student = $this->Users->findMember($q);
            if ($student) {
                $borrowed = $this->BorrowRecords->getActiveBorrowsByUser($student->id);
                $student->total_fine = $this->BorrowRecords->calculateFine($student->id);
            } else {
                $error = "Student \"{$q}\" not found.";
            }
        }

        View::setLayout('default');
        View::render('Librarian/lookup', [
            'q'        => $q,
            'student'  => $student,
            'borrowed' => $borrowed,
            'error'    => $error,
            'title'    => 'Student Lookup',
        ]);
    }

    // Issue a book
    public function borrow(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'Librarian/lookup');
            exit();
        }

        $userId    = (int)($_POST['user_id'] ?? 0);
        $bookQuery = trim($_POST['book_query'] ?? '');
        $dueDate   = $_POST['due_date'] ?? date('Y-m-d', strtotime('+' . DEFAULT_BORROW_DAYS . ' days'));

        if (!$userId || !$bookQuery) {
            $_SESSION['flash_error'] = 'Missing user or book.';
            header('Location: ' . BASE_URL . 'Librarian/lookup');
            exit();
        }

        $book = $this->Books->findByAccessionOrId($bookQuery);
        if (!$book) {
            $_SESSION['flash_error'] = "Book \"{$bookQuery}\" not found.";
            header('Location: ' . BASE_URL . 'Librarian/lookup?q=' . urlencode($_POST['email'] ?? ''));
            exit();
        }

        if ($book->available_copies < 1) {
            $_SESSION['flash_error'] = 'No available copies of this book.';
            header('Location: ' . BASE_URL . 'Librarian/lookup?q=' . urlencode($_POST['email'] ?? ''));
            exit();
        }

        // Check outstanding fine
        $fine = $this->BorrowRecords->calculateFine($userId);
        if ($fine > 0) {
            $_SESSION['flash_error'] = "Student has ₱{$fine} outstanding fine. Please settle first.";
            header('Location: ' . BASE_URL . 'Librarian/lookup?q=' . urlencode($_POST['email'] ?? ''));
            exit();
        }

        $this->BorrowRecords->issue($userId, $book->book_id, $dueDate);
        $this->Books->decrementAvailable($book->book_id);

        $_SESSION['flash_success'] = "Book \"{$book->title}\" issued successfully. Due: {$dueDate}";
        header('Location: ' . BASE_URL . 'Librarian/lookup?q=' . urlencode($_POST['email'] ?? ''));
        exit();
    }

    // Return a book
    public function return($recordId = null): void
    {
        $this->requireLibrarianOrHigher();

        $record = $this->BorrowRecords->getById($recordId);
        if (!$record || $record->status !== 'Borrowed') {
            $_SESSION['flash_error'] = 'Borrow record not found.';
            header('Location: ' . BASE_URL . 'Librarian/lookup');
            exit();
        }

        $fine = $this->BorrowRecords->computeReturnFine($record->due_date);
        $this->BorrowRecords->markReturned($recordId, $fine);
        $this->Books->incrementAvailable($record->book_id);

        $msg = "Book returned successfully.";
        if ($fine > 0) $msg .= " Fine: ₱{$fine}.";
        $_SESSION['flash_success'] = $msg;

        header('Location: ' . BASE_URL . 'Librarian/lookup?q=' . urlencode($record->email ?? ''));
        exit();
    }

    // Pay fine
    public function payFine($userId = null): void
    {
        $this->requireLibrarianOrHigher();

        $this->BorrowRecords->clearFine($userId);
        $_SESSION['flash_success'] = 'Fine cleared successfully.';

        header('Location: ' . BASE_URL . 'Librarian/lookup');
        exit();
    }

    // Transactions list
    public function transactions(): void
    {
        $this->syncOverdue();
        $filter = $_GET['filter'] ?? 'all';

        View::setLayout('default');
        View::render('Librarian/transactions', [
            'transactions' => $this->BorrowRecords->getFiltered($filter),
            'filter'       => $filter,
            'title'        => 'Transactions',
        ]);
    }


}
