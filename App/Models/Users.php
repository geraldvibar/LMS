<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Users
{
    private $db;

    public function __construct() { $this->db = DB::getDB(); }

    public function getAll(string $role = 'all', string $search = ''): array
    {
        $sql    = "SELECT user_id as id, fullname as full_name, email, role, phone, address FROM users WHERE 1=1";
        $params = [];
        if ($role && $role !== 'all') { 
            // Convert to database format: admin -> Admin, librarian -> Librarian, member -> Member
            $dbRole = ucfirst($role);
            $sql .= " AND role = ?"; 
            $params[] = $dbRole; 
        }
        if ($search) {
            $sql .= " AND (fullname LIKE ? OR email LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%"]);
        }
        $sql .= " ORDER BY role, fullname";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById(int $id): object|false
    {
        $stmt = $this->db->prepare("SELECT user_id as id, fullname, email, role, phone, address, data_registered FROM users WHERE user_id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByIdentifier(string $identifier): object|false
    {
        // Find user by email only (any role: Admin, Librarian, Member)
        $stmt = $this->db->prepare("
            SELECT user_id as id, fullname, email, password, role FROM users WHERE email = ? LIMIT 1
        ");
        $stmt->execute([$identifier]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByStudentId(string $studentId): object|false
    {
        // Members use email for kiosk login
        $stmt = $this->db->prepare("SELECT user_id as id, fullname, email, role FROM users WHERE email = ? AND role = 'Member' LIMIT 1");
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findMember(string $query): object|false
    {
        $stmt = $this->db->prepare("
            SELECT user_id as id, fullname, email, role FROM users
            WHERE role = 'Member' AND (email = ? OR fullname LIKE ?)
            LIMIT 1
        ");
        $stmt->execute([$query, "%$query%"]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function countMembers(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role='Member'")->fetchColumn();
    }

    public function add(array $data): int|false
    {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $dbRole = ucfirst($data['role']);
        $stmt = $this->db->prepare("INSERT INTO users (fullname, email, password, role, phone, address, data_registered) VALUES (?,?,?,?,?,?, CURDATE())");
        $stmt->execute([
            $data['name'], 
            $data['email'], 
            $hash, 
            $dbRole,
            $data['phone'] ?? '',
            $data['address'] ?? ''
        ]);
        return (int)$this->db->lastInsertId() ?: false;
    }

    public function edit(array $data): bool
    {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE users SET fullname=?, email=?, password=?, phone=?, address=? WHERE user_id=?");
            return $stmt->execute([
                $data['name'], 
                $data['email'], 
                $hash,
                $data['phone'] ?? '',
                $data['address'] ?? '',
                $data['id']
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE user_id=?");
            return $stmt->execute([
                $data['name'], 
                $data['email'],
                $data['phone'] ?? '',
                $data['address'] ?? '',
                $data['id']
            ]);
        }
    }

    public function hasLoanRecords(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM loan WHERE user_id=?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function hasActiveLoanRecords(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM loan WHERE user_id=? AND status IN ('Borrowed', 'Overdue')");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id=?");
        return $stmt->execute([$id]);
    }

}