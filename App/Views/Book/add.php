<div class="page-header">
    <div>
        <h4>Add Book</h4>
        <small class="text-muted">Add a new book to the collection</small>
    </div>
    <a href="<?= BASE_URL ?>Book/index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 border-0">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($old['isbn'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($old['title'] ?? '') ?>" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Author</label>
                        <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($old['author'] ?? '') ?>">
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Genre</label>
                            <input type="text" name="genre" class="form-control" value="<?= htmlspecialchars($old['genre'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. of Copies</label>
                            <input type="number" name="copies" class="form-control" value="<?= htmlspecialchars($old['copies'] ?? '1') ?>" min="1" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Book</button>
                        <a href="<?= BASE_URL ?>Book/index" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
