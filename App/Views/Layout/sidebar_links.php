<?php
$role    = strtolower($_SESSION['user_role'] ?? '');
$current = $_GET['url'] ?? '';
$isActive = fn(string $path) => strpos($current, $path) !== false ? 'active' : '';
?>
<ul class="nav flex-column py-2 w-100">

    <?php if ($role === 'admin'): ?>
    <!-- Admin has full access to all features -->
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Dashboard/admin') ?>" href="<?= BASE_URL ?>Dashboard/admin">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Users') ?>" href="<?= BASE_URL ?>Users/index">
            <i class="bi bi-people"></i> Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Book') ?>" href="<?= BASE_URL ?>Book/index">
            <i class="bi bi-book"></i> Books
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Librarian/lookup') ?>" href="<?= BASE_URL ?>Librarian/lookup">
            <i class="bi bi-search"></i> Lookup Student
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Librarian/transactions') ?>" href="<?= BASE_URL ?>Librarian/transactions">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
    </li>
    <?php endif; ?>

    <?php if ($role === 'librarian'): ?>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Dashboard/librarian') ?>" href="<?= BASE_URL ?>Dashboard/librarian">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Librarian/lookup') ?>" href="<?= BASE_URL ?>Librarian/lookup">
            <i class="bi bi-search"></i> Lookup Student
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Librarian/transactions') ?>" href="<?= BASE_URL ?>Librarian/transactions">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Book') ?>" href="<?= BASE_URL ?>Book/index">
            <i class="bi bi-book"></i> Books
        </a>
    </li>
    <?php endif; ?>

    <?php if ($role === 'member'): ?>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Dashboard/member') ?>" href="<?= BASE_URL ?>Dashboard/member">
            <i class="bi bi-speedometer2"></i> My Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $isActive('Book/browse') ?>" href="<?= BASE_URL ?>Book/browse">
            <i class="bi bi-book"></i> Books
        </a>
    </li>
    <?php endif; ?>

</ul>
