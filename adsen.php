<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php';

$has_write_access = check_permission('adsen');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $has_write_access) {
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
        $name = trim($_POST['name']);
        
        // Check structural overlap constraints (if the name matches, increment quantitative units)
        $chk = $pdo->prepare("SELECT id, qty FROM adsen_monk WHERE name = ?");
        $chk->execute([$name]);
        $existing = $chk->fetch();
        
        if ($existing) {
            $new_qty = $existing['qty'] + 1;
            $new_money = $new_qty * 5000;
            $stmt = $pdo->prepare("UPDATE adsen_monk SET qty = ?, money = ? WHERE id = ?");
            $stmt->execute([$new_qty, $new_money, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO adsen_monk (name, qty, money) VALUES (?, 1, 5000)");
            $stmt->execute([$name]);
        }
        echo "<script>window.location.href='adsen.php';</script>";
    }
}

// Execution Pipeline Handler: Individual Deletion Tasks
if (isset($_GET['delete']) && $has_write_access) {
    $stmt = $pdo->prepare("DELETE FROM adsen_monk WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<script>window.location.href='adsen.php';</script>";
}

// Data Array Pull Queries Configuration Execution
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM adsen_monk WHERE name LIKE ? ORDER BY updated_at DESC");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM adsen_monk ORDER BY updated_at DESC");
}
$records = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3 class="text-danger mb-0"><i class="fa-solid fa-bowl-food me-2"></i>បញ្ជីព្រះសង្ឃមិនទើងឆាន់</h3>
    <?php if ($has_write_access): ?>
        <button class="btn btn-pagoda btn-sm" data-bs-toggle="modal" data-bs-target="#adsenModal"><i class="fa-solid fa-circle-plus me-1"></i>បន្ថែមការអវត្តមាន</button>
    <?php endif; ?>
</div>

<div class="card p-3 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table table-hover table-bordered alignment-middle text-nowrap">
            <thead class="table-dark">
                <tr>
                    <th>ព្រះនាម/នាម</th>
                    <th>ចំនួនដងអវត្តមាន</th>
                    <th>ប្រាក់ពិន័យសរុប (១ដង = ៥០០០៛)</th>
                    <th class="no-print text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><span class="badge bg-danger"><?php echo htmlspecialchars($row['qty']); ?> ដង</span></td>
                        <td class="text-success font-weight-bold"><?php echo number_format($row['money']); ?> ៛</td>
                        <td class="no-print text-center">
                            <?php if ($has_write_access): ?>
                                <a href="adsen.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('លុបទិន្នន័យ?')"><i class="fa-solid fa-trash"></i></a>
                            <?php else: ?>
                                <span class="badge bg-secondary">View Only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="adsenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="">
            <div class="modal-header card-header-gold">
                <h5 class="modal-title">កត់ត្រាការអវត្តមានឆាន់</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="save">
                <div class="mb-3">
                    <label class="form-label">ព្រះនាម/នាម ព្រះសង្ឃ</label>
                    <input type="text" name="name" class="form-control" required placeholder="បញ្ចូលឈ្មោះឲ្យបានត្រឹមត្រូវ">
                </div>
                <div class="alert alert-info py-2 mb-0 text-center">ប្រព័ន្ធនឹងបូកបង្កើនចំនួនដង និងគណនាប្រាក់ពិន័យជាស្វ័យប្រវត្តក្នុងករណីឈ្មោះជាន់គ្នា។</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                <button type="submit" class="btn btn-pagoda">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>