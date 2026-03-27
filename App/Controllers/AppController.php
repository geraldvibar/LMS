<?php

namespace App\Controllers;

class AppController
{
    protected array $loadedModels = [];

    public function __construct()
    {
        $this->loadDefaultModel();
    }

    /**
     * Automatically loads model based on controller name
     */
    protected function loadDefaultModel()
    {
        $className = get_class($this);
        // Get only the last part (UsersController)
        $parts = explode('\\', $className);
        $controller = end($parts);

        // Remove "Controller" to get the model name (Users)
        $model = str_replace('Controller', '', $controller);

        // Load the model
        $this->loadModel($model);
    }

    /**
     * Manually load any model (like CakePHP)
     */
    protected function loadModel(string $model)
    {
        $modelClass = "App\\Models\\{$model}";

        if (!class_exists($modelClass)) {
            return; // Or throw exception if you prefer strict behavior
        }

        $this->$model = new $modelClass();
        $this->loadedModels[] = $model;
    }

        // ── Auth Helpers ──────────────────────────────────────────────

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'Auth/login');
            exit();
        }
    }

    protected function requireRole(array|string $allowedRoles): void
    {
        $this->requireLogin();
        $userRole = $_SESSION['user_role'] ?? '';
        // Check both lowercase and capitalized versions
        $allRoles = array_merge((array)$allowedRoles, array_map('ucfirst', (array)$allowedRoles));
        if (!in_array($userRole, $allRoles)) {
            header('Location: ' . BASE_URL . 'Dashboard/index?error=access_denied');
            exit();
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireRole(['admin']);
    }

    protected function requireLibrarianOrHigher(): void
    {
        $this->requireRole(['admin', 'librarian']);
    }

    protected function requireMember(): void
    {
        $this->requireRole(['member']);
    }

    // ── Auto-update overdue records ───────────────────────────────

    protected function syncOverdue(): void
    {
        \App\Core\DB::getDB()->exec(
            "UPDATE loan SET status='Overdue'
            WHERE status='Borrowed' AND due_date < CURDATE()"
        );
    }
}
