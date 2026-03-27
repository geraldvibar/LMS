<div class="page-header">
    <div>
        <h4>Book Management</h4>
        <small class="text-muted">Manage library book collection</small>
    </div>
    <a href="<?= BASE_URL ?>Book/add" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Book
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <form method="POST" action="<?= BASE_URL ?>Book/index" class="d-flex gap-2 align-items-center flex-wrap">
            <input type="search" name="q" class="form-control" placeholder="Search title, author"
                   value="<?= htmlspecialchars($search ?? '') ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-search me-1"></i> Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?= BASE_URL ?>Book/index" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <?php if (empty($books)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-book fs-3 d-block mb-2"></i> No books found.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-minimal">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Copies</th>
                        <th>Available</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td class="small fw-semibold"><?= htmlspecialchars($book->isbn ?: '—') ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($book->title) ?></td>
                        <td class="small"><?= htmlspecialchars($book->author ?: '—') ?></td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($book->genre ?: '—') ?></span></td>
                        <td><?= $book->total_copies ?></td>
                        <td>
                            <span class="badge bg-<?= $book->available_copies > 0 ? 'success' : 'danger' ?>">
                                <?= $book->available_copies ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>Book/edit/<?= $book->id ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?= $book->id ?>, '<?= htmlspecialchars($book->title) ?>')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Delete Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete <strong id="deleteBookTitle"></strong>?</div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash-fill me-1"></i> Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(id, title) {
    document.getElementById('deleteBookTitle').textContent = title;
    document.getElementById('confirmDeleteBtn').href = '<?= BASE_URL ?>Book/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
