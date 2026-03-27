<div class="page-header">
    <div>
        <h4>Student Lookup</h4>
        <small class="text-muted">Search student to issue or return books</small>
    </div>
</div>

<!-- Search -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>Librarian/lookup" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" class="form-control" placeholder="Enter Name"
                   value="<?= htmlspecialchars($q ?? '') ?>" autofocus style="max-width:360px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Search
            </button>
        </form>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-warning d-flex align-items-center gap-2 border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
        <div><?= htmlspecialchars($error) ?></div>
    </div>
<?php endif; ?>

<?php if ($student): ?>

<!-- Student Info + Issue Book -->
<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold border-bottom py-3">
                <i class="bi bi-person-fill text-primary me-1"></i> Student Information
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted fw-normal" style="width:40%;">Name</th><td class="fw-semibold"><?= htmlspecialchars($student->fullname) ?></td></tr>
                    <tr><th class="text-muted fw-normal">Email</th><td><?= htmlspecialchars($student->email) ?></td></tr>
                    <tr><th class="text-muted fw-normal">Status</th>
                        <td><span class="badge bg-<?= $student->status === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($student->status) ?></span></td>
                    </tr>
                    <tr><th class="text-muted fw-normal">Active Borrows</th><td><?= count($borrowed) ?></td></tr>
                    <tr><th class="text-muted fw-normal">Outstanding Fine</th>
                        <td class="fw-semibold <?= $student->total_fine > 0 ? 'text-danger' : 'text-success' ?>">
                            ₱<?= number_format($student->total_fine, 2) ?>
                        </td>
                    </tr>
                </table>

                <?php if ($student->total_fine > 0): ?>
                <form method="POST" action="<?= BASE_URL ?>Librarian/payFine/<?= $student->id ?>" class="mt-3">
                    <button type="submit" class="btn btn-outline-success btn-sm w-100"
                            onclick="return confirm('Mark fine as paid?')">
                        <i class="bi bi-check-circle me-1"></i> Mark Fine as Paid
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Issue Book -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold border-bottom py-3">
                <i class="bi bi-book text-success me-1"></i> Issue a Book
            </div>
            <div class="card-body">
                <?php if ($student->total_fine > 0): ?>
                    <div class="alert alert-warning border-0 mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Student has an outstanding fine. Please settle before issuing.
                    </div>
                <?php else: ?>
                <form method="POST" action="<?= BASE_URL ?>Librarian/borrow">
                    <input type="hidden" name="user_id" value="<?= $student->id ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($student->email) ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Book ID or ISBN</label>
                        <input type="text" name="book_query" class="form-control"
                               placeholder="e.g. 1 or 9780132350884" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control"
                               value="<?= date('Y-m-d', strtotime('+' . DEFAULT_BORROW_DAYS . ' days')) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-book me-1"></i> Issue Book
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Currently Borrowed -->
<?php if (!empty($borrowed)): ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold border-bottom py-3">
        <i class="bi bi-bookmark-check text-primary me-1"></i> Currently Borrowed
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-minimal">
                <thead>
                    <tr><th>Book ID</th><th>Title</th><th>Borrowed</th><th>Due Date</th><th>Status</th><th class="text-center">Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowed as $b): ?>
                    <tr>
                        <td class="small"><?= htmlspecialchars($b->book_id) ?></td>
                        <td class="fw-semibold small"><?= htmlspecialchars($b->title) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($b->borrow_date)) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($b->due_date)) ?></td>
                        <td>
                            <span class="badge bg-<?= $b->overdue ? 'danger' : 'warning text-dark' ?>">
                                <?= $b->overdue ? 'Overdue' : 'Borrowed' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>Librarian/return/<?= $b->record_id ?>"
                               class="btn btn-sm btn-outline-success"
                               onclick="return confirm('Return this book?')">
                                <i class="bi bi-box-arrow-in-left me-1"></i> Return
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>
