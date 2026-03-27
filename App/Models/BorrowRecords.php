<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class BorrowRecords
{
    private $db;

    public function __construct() { $this->db = DB::getDB(); }

    public function getById(int $id): object|false
    {
        $stmt = $this->db->prepare("
            SELECT l.loan_id as id, l.*, b.title, b.book_id, u.email, u.fullname as full_name
            FROM loan l
            JOIN book b ON b.book_id = l.book_id
            JOIN users u ON u.user_id = l.user_id
            WHERE l.loan_id = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT l.loan_id as id, l.*, b.title, b.author
            FROM loan l
            JOIN book b ON b.book_id = l.book_id
            WHERE l.user_id = ?
            ORDER BY l.borrow_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getActiveBorrowsByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT l.loan_id as record_id, l.book_id,
                   b.title, b.author, l.borrow_date, l.due_date, l.status, l.fine_amount,
                   CASE WHEN l.due_date < CURDATE() THEN 1 ELSE 0 END AS overdue
            FROM loan l
            JOIN book b ON b.book_id = l.book_id
            WHERE l.user_id = ? AND l.status = 'Borrowed'
            ORDER BY l.due_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFiltered(string $filter = 'all'): array
    {
        $sql = "
            SELECT l.loan_id AS record_id, u.user_id AS user_id,
                   u.fullname as full_name, u.email,
                   b.title, l.borrow_date, l.due_date, l.return_date,
                   l.status, l.fine_amount
            FROM loan l
            JOIN users u ON u.user_id = l.user_id
            JOIN book b ON b.book_id = l.book_id
            WHERE 1=1
        ";
        if ($filter === 'borrowed')  $sql .= " AND l.status = 'Borrowed'";
        elseif ($filter === 'overdue')  $sql .= " AND l.status = 'Overdue'";
        elseif ($filter === 'returned') $sql .= " AND l.status = 'Returned'";
        $sql .= " ORDER BY l.loan_id DESC LIMIT 100";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRecent(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT u.fullname as full_name, b.title, l.status, l.borrow_date, l.return_date
            FROM loan l
            JOIN users u ON u.user_id = l.user_id
            JOIN book b ON b.book_id = l.book_id
            WHERE DATE(l.borrow_date) = CURDATE() OR DATE(l.return_date) = CURDATE()
            ORDER BY l.loan_id DESC LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getStats(): object
    {
        $active  = (int)$this->db->query("SELECT COUNT(*) FROM loan WHERE status='Borrowed'")->fetchColumn();
        $overdue = (int)$this->db->query("SELECT COUNT(*) FROM loan WHERE status='Overdue'")->fetchColumn();
        $fines   = (float)$this->db->query("SELECT COALESCE(SUM(fine_amount),0) FROM loan WHERE status IN ('Borrowed','Overdue') AND fine_amount>0")->fetchColumn();

        return (object)[
            'active_borrows' => $active,
            'overdue'        => $overdue,
            'pending_fines'  => $fines,
        ];
    }

    public function calculateFine(int $userId): float
    {
        $stmt = $this->db->prepare("
            SELECT due_date FROM loan
            WHERE user_id=? AND status='Borrowed' AND due_date < CURDATE()
        ");
        $stmt->execute([$userId]);
        $overdue = $stmt->fetchAll(PDO::FETCH_OBJ);

        $fine = 0;
        foreach ($overdue as $r) {
            $days = (int)floor((time() - strtotime($r->due_date)) / 86400);
            $fine += $days * FINE_PER_DAY;
        }
        return $fine;
    }

    public function computeReturnFine(string $dueDate): float
    {
        if (strtotime($dueDate) >= time()) return 0;
        $days = (int)floor((time() - strtotime($dueDate)) / 86400);
        return $days * FINE_PER_DAY;
    }

    public function issue(int $userId, int $bookId, string $dueDate): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO loan (user_id, book_id, borrow_date, due_date, status)
            VALUES (?, ?, CURDATE(), ?, 'Borrowed')
        ");
        return $stmt->execute([$userId, $bookId, $dueDate]);
    }

    public function markReturned(int $recordId, float $fine): bool
    {
        $stmt = $this->db->prepare("
            UPDATE loan SET status='Returned', return_date=CURDATE(), fine_amount=? WHERE loan_id=?
        ");
        return $stmt->execute([$fine, $recordId]);
    }

    public function clearFine(int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE loan SET fine_amount=0 WHERE user_id=? AND fine_amount>0");
        return $stmt->execute([$userId]);
    }

    public function deleteByUser(int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM loan WHERE user_id=?");
        return $stmt->execute([$userId]);
    }

    public function deleteByBook(int $bookId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM loan WHERE book_id=?");
        return $stmt->execute([$bookId]);
    }
}
