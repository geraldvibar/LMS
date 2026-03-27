<div class="page-header">
    <div>
        <h4>My Dashboard</h4>
        <small class="text-muted">Welcome, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold border-bottom py-3">
        <i class="bi bi-bookmark-check text-primary me-1"></i> My Borrowed Books
    </div>
    <div class="card-body p-0">
        <?php if (empty($borrowed_books)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-book fs-3 d-block mb-2"></i>
                You have no borrowed books.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-minimal">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Borrowed</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowed_books as $b): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($b->title) ?></td>
                        <td class="small"><?= htmlspecialchars($b->author) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($b->borrow_date)) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($b->due_date)) ?></td>
                        <td>
                            <span class="badge bg-<?= $b->status === 'returned' ? 'success' : ($b->status === 'overdue' ? 'danger' : 'warning text-dark') ?>">
                                <?= ucfirst($b->status) ?>
                            </span>
                        </td>
                        <td class="small <?= $b->fine_amount > 0 ? 'text-danger fw-semibold' : '' ?>">
                            <?= $b->fine_amount > 0 ? '₱' . number_format($b->fine_amount, 2) : '—' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
