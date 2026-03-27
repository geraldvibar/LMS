<div class="page-header">
    <div>
        <h4>Browse Books</h4>
        <small class="text-muted">Search and view available books</small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <form method="POST" action="<?= BASE_URL ?>Book/browse" class="d-flex gap-2 align-items-center flex-wrap">
            <input type="search" name="q" class="form-control" placeholder="Search title, author, ISBN"
                   value="<?= htmlspecialchars($search ?? '') ?>" style="max-width:320px;">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-search me-1"></i> Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?= BASE_URL ?>Book/browse" class="btn btn-outline-secondary">
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
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td class="small fw-semibold"><?= htmlspecialchars($book->isbn ?: '—') ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($book->title) ?></td>
                        <td class="small"><?= htmlspecialchars($book->author ?: '—') ?></td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($book->genre ?: '—') ?></span></td>
                        <td>
                            <span class="badge bg-<?= $book->available_copies > 0 ? 'success' : 'danger' ?>">
                                <?= $book->available_copies ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
