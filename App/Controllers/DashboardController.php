<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Books;
use App\Models\Users;
use App\Models\BorrowRecords;

class DashboardController extends AppController
{
    protected $Books;
    protected $Users;
    protected $BorrowRecords;

    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->Books         = new Books();
        $this->Users         = new Users();
        $this->BorrowRecords = new BorrowRecords();
    }

    // Admin dashboard
    public function admin(): void
    {
        $this->requireAdmin();
        $this->syncOverdue();

        View::setLayout('default');
        View::render('Dashboard/admin', [
            'stats'               => $this->BorrowRecords->getStats(),
            'total_users'        => $this->Users->countMembers(),
            'total_books'        => $this->Books->countAll(),
            'recent_transactions' => $this->BorrowRecords->getRecent(10),
            'title'              => 'Admin Dashboard',
        ]);
    }

    // Librarian dashboard
    public function librarian(): void
    {
        $this->requireLibrarianOrHigher();
        $this->syncOverdue();

        View::setLayout('default');
        View::render('Dashboard/librarian', [
            'stats'               => $this->BorrowRecords->getStats(),
            'recent_transactions' => $this->BorrowRecords->getRecent(10),
            'title'               => 'Librarian Dashboard',
        ]);
    }

    // Member dashboard
    public function member(): void
    {
        $this->requireMember();

        $userId = $_SESSION['user_id'];

        View::setLayout('default');
        View::render('Dashboard/member', [
            'borrowed_books' => $this->BorrowRecords->getByUser($userId),
            'title'          => 'My Dashboard',
        ]);
    }
}