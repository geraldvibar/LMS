<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Books
{
    private $db;

    public function __construct() { $this->db = DB::getDB(); }

    public function getAll(string $search = ''): array
    {
        $sql    = "SELECT book_id as id, title, author, isbn, genre, publication_year, total_copies, available_copies FROM book WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (title LIKE ? OR author LIKE ? OR isbn LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%"];
        }
        $sql .= " ORDER BY title";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById(int|string $id): object|false
    {
        $stmt = $this->db->prepare("SELECT * FROM book WHERE book_id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByAccessionOrId(string $query): object|false
    {
        // Search by book_id or isbn
        $stmt = $this->db->prepare("SELECT * FROM book WHERE book_id = ? OR isbn = ? LIMIT 1");
        $stmt->execute([$query, $query]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM book")->fetchColumn();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO book (title, author, isbn, genre, publication_year, total_copies, available_copies)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['title'],
            $data['author'] ?? '',
            $data['isbn'] ?? '',
            $data['category'] ?? null,
            $data['year'] ?? null,
            $data['total_copies'],
            $data['available_copies'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE book SET title=?, author=?, isbn=?, genre=?, publication_year=?, total_copies=?, available_copies=?
            WHERE book_id=?
        ");
        return $stmt->execute([
            $data['title'],
            $data['author'] ?? '',
            $data['isbn'] ?? '',
            $data['category'] ?? null,
            $data['year'] ?? null,
            $data['total_copies'],
            $data['available_copies'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM book WHERE book_id = ?");
        return $stmt->execute([$id]);
    }

    public function getActiveBorrowCount(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM loan WHERE book_id=? AND status='Borrowed'");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn();
    }

    public function decrementAvailable(int $id): void
    {
        $this->db->prepare("UPDATE book SET available_copies = available_copies - 1 WHERE book_id=?")->execute([$id]);
    }

    public function incrementAvailable(int $id): void
    {
        $this->db->prepare("UPDATE book SET available_copies = available_copies + 1 WHERE book_id=?")->execute([$id]);
    }
}