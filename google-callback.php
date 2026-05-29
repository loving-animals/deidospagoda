<?php
require_once 'config/db.php';
require_once 'includes/middleware.php';

if (isset($_GET['code'])) {
    // Exchange Authorization Code for Access Token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'code' => $_GET['code'],
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URL,
        'grant_type' => 'authorization_code'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (isset($data['access_token'])) {
        // Query Profile Data via token
        $profile_response = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $data['access_token']);
        $profile = json_decode($profile_response, true);
        
        if (!empty($profile['email'])) {
            $email = $profile['email'];
            $name = $profile['name'];
            $google_id = $profile['id'];
            
            // Check identity mapping
            $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ? OR email = ?");
            $stmt->execute([$google_id, $email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                // Autoregister profile as Normal User role
                $stmt = $pdo->prepare("INSERT INTO users (name, email, google_id, role) VALUES (?, ?, ?, 'Normal User')");
                $stmt->execute([$name, $email, $google_id]);
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
                $stmt->execute([$google_id]);
                $user = $stmt->fetch();
            } else if (empty($user['google_id'])) {
                // Link account if registering via internal credential systems first
                $stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                $stmt->execute([$google_id, $user['id']]);
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['assigned_page'] = $user['assigned_page'];
            
            header("Location: dashboard.php");
            exit;
        }
    }
}
header("Location: login.php?error=oauth_failed");
exit;