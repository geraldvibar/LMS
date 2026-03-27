<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Edit User</h4>
        <small class="text-muted">Update user information</small>
    </div>
    <a href="<?= BASE_URL ?>Users/index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>Users/edit/<?= $user->id ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= htmlspecialchars($old['name'] ?? ($old['fullname'] ?? $user->fullname ?? '')) ?>" required autofocus>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($old['email'] ?? $user->email ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Leave blank to keep current password</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user->role ?? 'N/A') ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" 
                           value="<?= htmlspecialchars($old['phone'] ?? $user->phone ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" 
                           value="<?= htmlspecialchars($old['address'] ?? $user->address ?? '') ?>">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Update User
                </button>
                <a href="<?= BASE_URL ?>Users/index" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
