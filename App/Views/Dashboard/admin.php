<div class="page-header">
    <div>
        <h4>Admin Dashboard</h4>
        <small class="text-muted">Welcome, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></small>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card shadow-sm border-0 stat-card stat-primary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-people fs-4 text-white"></i>
                   
                </div>
                <div>
                    <div class="text-muted small">Total Members</div>
                    <div class="fs-4 fw-bold"><?= $total_users ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card shadow-sm border-0 stat-card stat-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-book fs-4 text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Books</div>
                    <div class="fs-4 fw-bold"><?= $total_books ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card shadow-sm border-0 stat-card stat-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-exclamation-triangle fs-4 text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Overdue</div>
                    <div class="fs-4 fw-bold"><?= $stats->overdue ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card shadow-sm border-0 stat-card stat-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                    <i class="bi bi-cash-stack fs-4 text-white"></i>
                </div>
                <div>
                    <div class="text-muted small">Pending Fines</div>
                    <div class="fs-5 fw-bold text-danger">₱<?= number_format($stats->pending_fines, 2) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold border-bottom py-3">
                <i class="bi bi-clock-history text-primary me-1"></i> Today's Activity
            </div>
            <div class="card-body p-0">
                <?php if (empty($recent_transactions)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>No activity today.
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0 table-minimal">
                        <thead>
                            <tr><th>Member</th><th>Book</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $t): ?>
                            <tr>
                                <td class="small fw-semibold"><?= htmlspecialchars($t->full_name) ?></td>
                                <td class="small"><?= htmlspecialchars($t->title) ?></td>
                                <td>
                                    <span class="badge bg-<?= $t->status === 'returned' ? 'success' : ($t->status === 'overdue' ? 'danger' : 'warning text-dark') ?>">
                                        <?= ucfirst($t->status) ?>
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
    </div>
</div>
