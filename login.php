<?php
// 1. Initialize session and core configuration architectures
require_once 'config/db.php';
require_once 'includes/middleware.php';

// Direct traffic diversion if user profile states are already active
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// 2. Process login credential structures upon form post submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (!empty($email) && !empty($password)) {
        try {
            // SQL Injection Protected preparation query statement
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Password hash verification layer processing matrix
            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['assigned_page'] = $user['assigned_page'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'អុីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ!';
            }
        } catch (PDOException $e) {
            $error = 'មានបញ្ហាបច្ចេកទេសប្រព័ន្ធ៖ ' . $e->getMessage();
        }
    } else {
        $error = 'សូមបំពេញព័ត៌មានក្នុងប្រឡោនឲ្យបានគ្រប់គ្រាន់!';
    }
}

// 3. Google OAuth Redirect URL Configuration
$google_login_url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'scope'         => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
    'redirect_uri'  => GOOGLE_REDIRECT_URL,
    'response_type' => 'code',
    'client_id'     => GOOGLE_CLIENT_ID,
    'access_type'   => 'online'
]);
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចូលប្រើប្រព័ន្ធ - DeyDospagoda</title>
    <!-- Bootstrap 5 Framework Component UI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Library Stylesheet Setup -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.85)), 
                        url('https://images.unsplash.com/photo-1609137144813-979401923e53?q=80&w=1200') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border-top: 6px solid var(--accent-color);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

    <div class="card p-4 login-card" style="width: 100%; max-width: 430px;">
        <div class="text-center mb-4">
            <!-- Icon Symbolizing Pagoda Infrastructure -->
            <i class="fa-solid fa-gopuram text-danger fs-1 mb-2" style="color: var(--primary-color) !important;"></i>
            <h3 class="text-danger font-weight-bold mb-1" style="color: var(--primary-color) !important;">ប្រព័ន្ធគ្រប់គ្រងវត្តដីដុះ</h3>
            <span class="text-muted small d-block">DeyDospagoda Secure Identity Provider</span>
        </div>
        
        <!-- Alerts Presentation Matrix -->
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger text-center py-2 mb-3 small">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error']) && $_GET['error'] == 'oauth_failed'): ?>
            <div class="alert alert-warning text-center py-2 mb-3 small">
                <i class="fa-solid fa-circle-exclamation me-1"></i> ការភ្ជាប់តាមគណនី Google បរាជ័យ!
            </div>
        <?php endif; ?>

        <!-- Application Traditional Authentication Entry Fields -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="mb-3">
                <label class="form-label text-dark fw-bold"><i class="fa-solid fa-envelope me-1"></i> អុីមែលប្រព័ន្ធ (System Email)</label>
                <input type="email" name="email" class="form-control" required autocomplete="email" placeholder="ឧទាហរណ៍៖ admin@deydospagoda.gov.kh">
            </div>
            <div class="mb-4">
                <label class="form-label text-dark fw-bold"><i class="fa-solid fa-lock me-1"></i> ពាក្យសម្ងាត់ (Password)</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn w-100 btn-pagoda py-2.5 fw-bold mb-3 shadow-sm"><i class="fa-solid fa-right-to-bracket me-1"></i> ចូលប្រើប្រព័ន្ធ</button>
        </form>
        
        <div class="position-relative text-center my-3">
            <hr class="text-muted">
            <span class="px-2 bg-white position-absolute top-50 start-50 translate-middle text-muted small">ឬ</span>
        </div>
        
        <!-- External Strategic Integrations Platform Gateway -->
        <a href="<?php echo $google_login_url; ?>" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center py-2 border-secondary shadow-sm">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google API logo" style="width:18px;" class="me-2">
            <span class="fw-bold">ចូលតាមគណនី Google</span>
        </a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>