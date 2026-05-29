<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$has_write_access = check_permission('car');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $has_write_access) {
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
        $name = trim($_POST['name']);
        $month = trim($_POST['month_record']); 
        
        try {
            $stmt = $pdo->prepare("INSERT INTO car_monk (name, month_record, money) VALUES (?, ?, 45000)");
            $stmt->execute([$name, $month]);
        } catch (PDOException $e) {
            echo "<script>alert('ព្រះសង្ឃអង្គនេះបានបង់ថ្លៃឡានប្រចាំខែនេះរួចរាល់ហើយ!');</script>";
        }
        echo "<script>window.location.href='car-payment.php';</script>";
    }
}

if (isset($_GET['delete']) && $has_write_access) {
    $stmt = $pdo->prepare("DELETE FROM car_monk WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<script>window.location.href='car-payment.php';</script>";
}

$stmt = $pdo->query("SELECT * FROM car_monk ORDER BY month_record DESC, id DESC");
$records = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3 class="text-danger mb-0"><i class="fa-solid fa-bus me-2"></i>ការប្រមូលថវិកាថ្លៃឡាន (ព្រះសង្ឃបង់ថ្លៃឡាន)</h3>
    <?php if ($has_write_access): ?>
        <button class="btn btn-pagoda btn-sm" data-bs-toggle="modal" data-bs-target="#carModal"><i class="fa-solid fa-circle-plus me-1"></i>បន្ថែមការបង់ប្រាក់</button>
    <?php endif; ?>
</div>

<div class="card p-3 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table table-hover table-bordered alignment-middle">
            <thead class="table-dark">
                <tr>
                    <th>ព្រះនាម/នាម</th>
                    <th>ប្រចាំខែ</th>
                    <th>ចំនួនទឹកប្រាក់បង់ (ថេរ)</th>
                    <th class="no-print text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['month_record']); ?></span></td>
                        <td class="text-success font-weight-bold"><?php echo number_format($row['money']); ?> ៛</td>
                        <td class="no-print text-center">
                            <?php if ($has_write_access): ?>
                                <a href="car-payment.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('លុបទិន្នន័យ?')"><i class="fa-solid fa-trash"></i></a>
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

<div class="modal fade" id="carModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="">
            <div class="modal-header card-header-gold">
                <h5 class="modal-title">កត់ត្រាការបង់ថ្លៃឡានប្រចាំខែ</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="save">
                <div class="mb-3">
                    <label class="form-label">ព្រះនាម/នាម ព្រះសង្ឃ</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ជ្រើសរើសខែបង់ប្រាក់</label>
                    <input type="month" name="month_record" class="form-control" required value="<?php echo date('Y-m'); ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                <button type="submit" class="btn btn-pagoda">រក្សាទុកទិន្នន័យ</button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>