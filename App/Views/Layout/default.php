<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' — ' : '' ?><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg lms-navbar sticky-top">
    <div class="container-fluid">
        <!-- Mobile menu button (left, hidden on md+) -->
        <button class="btn btn-sm btn-link text-dark d-md-none me-1 p-1 lms-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <span class="navbar-brand fw-bold">
            <i class="bi bi-book-half me-2"></i><?= APP_NAME ?>
        </span>

        <div class="d-flex align-items-center gap-2">
            <span class="d-none d-sm-inline small text-muted">
                <strong class="text-dark"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong>
            </span>
            <a href="<?= BASE_URL ?>Auth/logout" class="btn btn-sm btn-outline-secondary" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">

        <!-- Sidebar - Desktop -->
        <nav class="col-md-2 d-none d-md-flex flex-column lms-sidebar">
            <?php include __DIR__ . '/sidebar_links.php'; ?>
        </nav>

        <!-- Mobile Sidebar (collapsible) -->
        <nav class="col-12 d-md-none lms-sidebar collapse" id="sidebarMenu">
            <?php include __DIR__ . '/sidebar_links.php'; ?>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 p-4">
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</div>

<script src="<?= BASE_URL ?>bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
