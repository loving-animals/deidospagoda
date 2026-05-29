<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav id="sidebar" class="collapse d-lg-block navbar-collapse no-print">
    <div class="sidebar-header">
        <h5 class="text-warning mb-0">មាតិកាបញ្ជា</h5>
    </div>
    <ul class="list-unstyled components w-100">
        <li class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php"><i class="fa-solid fa-gauge me-2"></i> ទំព័រដើម (Dashboard)</a>
        </li>
        <li class="<?php echo $current_page == 'monks.php' ? 'active' : ''; ?>">
            <a href="monks.php"><i class="fa-solid fa-users-viewfinder me-2"></i> ចំនួនព្រះសង្ឃ</a>
        </li>
        <li class="<?php echo $current_page == 'adsen.php' ? 'active' : ''; ?>">
            <a href="adsen.php"><i class="fa-solid fa-bowl-food me-2"></i> ព្រះសង្ឃមិនទើងឆាន់</a>
        </li>
        <li class="<?php echo $current_page == 'car-payment.php' ? 'active' : ''; ?>">
            <a href="car-payment.php"><i class="fa-solid fa-bus me-2"></i> ព្រះសង្ឃបង់ថ្លៃឡាន</a>
        </li>
        <li class="<?php echo $current_page == 'study-absent.php' ? 'active' : ''; ?>">
            <a href="study-absent.php"><i class="fa-solid fa-graduation-cap me-2"></i> ព្រះសង្ឃអវត្តមានរៀន</a>
        </li>
        <hr class="text-warning">
        <li>
            <a href="logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> ចាកចេញពីប្រព័ន្ធ</a>
        </li>
    </ul>
</nav>
<main class="main-content flex-grow-1 p-4" style="min-height: 90vh;">