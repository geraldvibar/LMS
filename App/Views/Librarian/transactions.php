<div class="page-header">
    <div>
        <h4>Transactions</h4>
        <small class="text-muted">All borrow and return records</small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <div class="d-flex gap-2 flex-wrap">
            <?php foreach (['all', 'borrowed', 'overdue', 'returned'] as $f): ?>
            <a href="<?= BASE_URL ?>Librarian/transactions?filter=<?= $f ?>"
               class="btn btn-sm <?= $filter === $f ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <?= ucfirst($f) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <?php if (empty($transactions)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-3 d-block mb-2"></i>No records found.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-minimal">
                <thead>
                    <tr><th>Student</th><th>Email</th><th>Book</th><th>Borrowed</th><th>Due</th><th>Returned</th><th>Status</th><th>Fine</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td class="fw-semibold small"><?= htmlspecialchars($t->full_name) ?></td>
                        <td class="small"><?= htmlspecialchars($t->email) ?></td>
                        <td class="small"><?= htmlspecialchars($t->title) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($t->borrow_date)) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($t->due_date)) ?></td>
                        <td class="small"><?= $t->return_date ? date('M d, Y', strtotime($t->return_date)) : '—' ?></td>
                        <td>
                            <span class="badge bg-<?= $t->status === 'returned' ? 'success' : ($t->status === 'overdue' ? 'danger' : 'warning text-dark') ?>">
                                <?= ucfirst($t->status) ?>
                            </span>
                        </td>
                        <td class="small <?= $t->fine_amount > 0 ? 'text-danger fw-semibold' : '' ?>">
                            <?= $t->fine_amount > 0 ? '₱' . number_format($t->fine_amount, 2) : '—' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
