<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>Auth/login">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="identifier" class="form-control" 
               placeholder="Enter your email" required autofocus>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" 
               placeholder="Enter your password" required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
            
        </button>
    </div>
</form>
