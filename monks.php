<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php';

$has_write_access = check_permission('monks');

// 1. ACTION COMPILER PARSER: CREATE / UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $has_write_access) {
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
        $id = $_POST['id'] ?? '';
        $name = trim($_POST['name']);
        $chhaya = trim($_POST['chhaya']);
        $birthplace = trim($_POST['birthplace']);
        $phone = trim($_POST['phone']);
        
        if ($id == '') {
            // Write Mode
            $stmt = $pdo->prepare("INSERT INTO monks (name, chhaya, birthplace, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $chhaya, $birthplace, $phone]);
        } else {
            // Edit Mode
            $stmt = $pdo->prepare("UPDATE monks SET name=?, chhaya=?, birthplace=?, phone=? WHERE id=?");
            $stmt->execute([$name, $chhaya, $birthplace, $phone, $id]);
        }
        echo "<script>window.location.href='monks.php';</script>";
    }
}

// 2. ACTION COMPILER PARSER: DELETE
if (isset($_GET['delete']) && $has_write_access) {
    $stmt = $pdo->prepare("DELETE FROM monks WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<script>window.location.href='monks.php';</script>";
}

// 3. SELECTION DATA CONTROLLER RUNNER
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM monks WHERE name LIKE ? OR chhaya LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM monks ORDER BY id DESC");
}
$monks = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3 class="text-danger mb-0"><i class="fa-solid fa-address-card me-2"></i>ការគ្រប់គ្រងចំនួនព្រះសង្ឃ</h3>
    <div>
        <button onclick="window.print();" class="btn btn-secondary btn-sm"><i class="fa-solid fa-print me-1"></i>បោះពុម្ភ</button>
        <?php if ($has_write_access): ?>
            <button class="btn btn-pagoda btn-sm" data-bs-toggle="modal" data-bs-target="#monkModal" onclick="clearForm()"><i class="fa-solid fa-circle-plus me-1"></i>បន្ថែមព្រះសង្ឃ</button>
        <?php endif; ?>
    </div>
</div>

<form class="row g-2 mb-3 no-print" method="GET" action="">
    <div class="col-md-4">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="ស្វែងរកតាមនាម ឬឆាយា...">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-dark w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>ស្វែងរក</button>
    </div>
</form>

<div class="card p-3 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table table-hover table-bordered alignment-middle text-nowrap">
            <thead class="table-dark">
                <tr>
                    <th>អត្តលេខ</th>
                    <th>ព្រះនាម/នាម</th>
                    <th>ឆាយា</th>
                    <th>លេខទូរស័ព្ទ</th>
                    <th>ស្រុកកំណើត</th>
                    <th class="no-print text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($monks) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted">មិនមានទិន្នន័យឡើយ</td></tr>
                <?php else: ?>
                    <?php foreach ($monks as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($row['chhaya']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['birthplace']); ?></td>
                            <td class="no-print text-center">
                                <?php if ($has_write_access): ?>
                                    <button class="btn btn-sm btn-info text-white me-1" onclick="editMonk(<?php echo htmlspecialchars(json_encode($row)); ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <a href="monks.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យនេះមែនទេ?')"><i class="fa-solid fa-trash-can"></i></a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Read-Only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($has_write_access): ?>
<div class="modal fade" id="monkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="">
            <div class="modal-header card-header-gold">
                <h5 class="modal-title" id="modalTitle">បញ្ចូលព័ត៌មានព្រះសង្ឃ</h5>
                <button type="button" class="btn-close text-white" data-bs-toggle="modal" data-bs-target="#monkModal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" id="monk_id">
                <div class="mb-3">
                    <label class="form-label">ព្រះនាម/នាម</label>
                    <input type="text" name="name" id="monk_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ឆាយា</label>
                    <input type="text" name="chhaya" id="monk_chhaya" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" id="monk_phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ស្រុកកំណើត</label>
                    <textarea name="birthplace" id="monk_birthplace" class="form-control" rows="2" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                <button type="submit" class="btn btn-pagoda">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>
<script>
function clearForm() {
    document.getElementById('monk_id').value = '';
    document.getElementById('monk_name').value = '';
    document.getElementById('monk_chhaya').value = '';
    document.getElementById('monk_phone').value = '';
    document.getElementById('monk_birthplace').value = '';
    document.getElementById('modalTitle').innerText = "បញ្ចូលព័ត៌មានព្រះសង្ឃ";
}
function editMonk(data) {
    document.getElementById('monk_id').value = data.id;
    document.getElementById('monk_name').value = data.name;
    document.getElementById('monk_chhaya').value = data.chhaya;
    document.getElementById('monk_phone').value = data.phone;
    document.getElementById('monk_birthplace').value = data.birthplace;
    document.getElementById('modalTitle').innerText = "កែប្រែព័ត៌មានព្រះសង្ឃ";
    new bootstrap.Modal(document.getElementById('monkModal')).show();
}
</script>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>