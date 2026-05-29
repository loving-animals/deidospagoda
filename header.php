<?php
require_once 'config/db.php';
require_once 'includes/middleware.php';
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeyDospagoda - ប្រព័ន្ធគ្រប់គ្រងវត្តដីដុះ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top no-print px-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="fa-solid fa-gopuram me-2 fs-3"></i>
            <span class="d-none d-sm-inline">DeyDospagoda</span>
        </a>
        
        <div class="mx-auto text-center">
            <h4 class="mb-0 text-white font-weight-bold d-none d-md-block">ប្រព័ន្ធគ្រប់គ្រងព័ត៌មានវិទ្យា វត្តដីដុះ</h4>
        </div>
        
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-lg-inline">
                <i class="fa-solid fa-user-shield text-warning me-1"></i> 
                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'ភ្ញៀវ'); ?> 
                (<small><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Normal User'); ?></small>)
            </span>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
                <i class="fas fa-bars fs-3 text-warning"></i>
            </button>
        </div>
    </div>
</nav>

<div class="container-fluid d-flex p-0">