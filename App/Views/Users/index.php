<div class="page-header">
    <div>
        <h4>User Management</h4>
        <small class="text-muted">Manage system users</small>
    </div>
    <a href="<?= BASE_URL ?>Users/add" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Add User
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <form method="POST" action="<?= BASE_URL ?>Users/index" class="d-flex gap-2 flex-wrap align-items-center">
            <select name="role" class="form-select" style="max-width:160px;">
                <option value="all" <?= ($role ?? 'all') === 'all' ? 'selected' : '' ?>>All Roles</option>
                <option value="admin"     <?= ($role ?? '') === 'admin'     ? 'selected' : '' ?>>Admin</option>
                <option value="librarian" <?= ($role ?? '') === 'librarian' ? 'selected' : '' ?>>Librarian</option>
                <option value="member"    <?= ($role ?? '') === 'member'    ? 'selected' : '' ?>>Member</option>
            </select>
            <input type="text" name="q" class="form-control" placeholder="Search name or email..."
                   value="<?= htmlspecialchars($search ?? '') ?>" style="max-width:240px;">
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search me-1"></i> Search</button>
            <a href="<?= BASE_URL ?>Users/index" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i> Clear</a>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-people fs-3 d-block mb-2"></i>No users found.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-minimal">
                <thead>
                    <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th class="text-center">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($u->full_name ?? 'N/A') ?></td>
                        <td class="small"><?= htmlspecialchars($u->email ?? '') ?></td>
                        <td>
                            <?php
                            $r  = strtolower($u->role ?? '');
                            $rc = match($r) { 'admin' => 'danger', 'librarian' => 'info', 'member' => 'success', default => 'secondary' };
                            ?>
                            <span class="badge bg-<?= $rc ?>"><?= htmlspecialchars($u->role ?? 'N/A') ?></span>
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>Users/edit/<?= $u->id ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?= $u->id ?>, '<?= htmlspecialchars($u->full_name ?? 'User') ?>')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete <strong id="deleteUserName"></strong>?</div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash-fill me-1"></i> Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('confirmDeleteBtn').href = '<?= BASE_URL ?>Users/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
