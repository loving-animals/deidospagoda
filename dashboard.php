<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php';

// System Data Analytics Aggregations
$totalMonks = $pdo->query("SELECT COUNT(*) FROM monks")->fetchColumn();
$totalAdsen = $pdo->query("SELECT SUM(qty) FROM adsen_monk")->fetchColumn() ?? 0;
$totalCarPayout = $pdo->query("SELECT SUM(money) FROM car_monk")->fetchColumn() ?? 0;
$totalAbsences = $pdo->query("SELECT SUM(qty) FROM study_monk")->fetchColumn() ?? 0;

// Write User Message Processing Integration Engine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_msg'])) {
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $msg = trim($_POST['message']);
        if (!empty($msg)) {
            $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $msg]);
            echo "<script>alert('សារបញ្ជូនបានជោគជ័យ');</script>";
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 rounded shadow-sm text-white" style="background: linear-gradient(135deg, #7A0616, #B00B1E); border-left: 5px solid var(--accent-color);">
            <h2>សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ DeyDospagoda</h2>
            <p class="mb-0">ការគ្រប់គ្រងព័ត៌មាន បច្ចេកវិទ្យា និងរបាយការណ៍ហិរញ្ញវត្ថុក្នុងវត្តអារាមប្រកបដោយប្រសិទ្ធភាព និងសុវត្ថិភាពខ្ពស់។</p>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card dashboard-card p-3 border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">ចំនួនព្រះសង្ឃសរុប</h6>
                    <h3 class="mb-0 text-danger font-weight-bold"><?php echo $totalMonks; ?> អង្គ</h3>
                </div>
                <i class="fa-solid fa-dharmachakra text-danger fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card p-3 border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">អវត្តមានឆាន់ (ដង)</h6>
                    <h3 class="mb-0 text-warning font-weight-bold"><?php echo $totalAdsen; ?> ដង</h3>
                </div>
                <i class="fa-solid fa-bowl-rice text-warning fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">ថវិកាថ្លៃឡានសរុប</h6>
                    <h3 class="mb-0 text-success font-weight-bold"><?php echo number_format($totalCarPayout); ?> ៛</h3>
                </div>
                <i class="fa-solid fa-money-bill-trend-up text-success fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card p-3 border-start border-dark border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">អវត្តមានសាលារៀន</h6>
                    <h3 class="mb-0 text-dark font-weight-bold"><?php echo $totalAbsences; ?> ដង</h3>
                </div>
                <i class="fa-solid fa-user-slash text-dark fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm p-4 bg-white">
            <h5 class="text-danger mb-3 border-bottom pb-2"><i class="fa-solid fa-chart-simple me-2"></i>គំនូសតាងស្ថិតិរួម</h5>
            <canvas id="pagodaMainChart" style="max-height: 320px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3 bg-white mb-3">
            <h5 class="text-danger mb-2"><i class="fa-solid fa-envelope-open-text me-2"></i>ផ្ញើសារចូលប្រព័ន្ធ</h5>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <textarea class="form-control mb-2" name="message" rows="3" placeholder="វាយបញ្ចូលមតិយោបល់ ឬសំណើទីនេះ..." required></textarea>
                <button type="submit" name="submit_msg" class="btn btn-pagoda btn-sm w-100">បញ្ជូនសារ</button>
            </form>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('pagodaMainChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['ចំនួនភិក្ខុ-សាមណេរ', 'អវត្តមានឆាន់ (ដង)', 'អវត្តមានរៀន (ដង)'],
            datasets: [{
                label: 'ទិន្នន័យជាក់ស្តែង',
                data: [<?php echo $totalMonks; ?>, <?php echo $totalAdsen; ?>, <?php echo $totalAbsences; ?>],
                backgroundColor: ['#7A0616', '#D4AF37', '#212529'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, scalar: { y: { beginAtZero: true } } }
    });
</script>

<?php include_once 'includes/footer.php'; ?>