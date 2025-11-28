<?php
session_start();
include 'codes/config.php'; 

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if(!$username || !$password){
    header("Location: login.php?error=Please+fill+all+fields");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user && password_verify($password, $user['password'])){
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $user['username'];
    header("Location: admin.php");
    exit;
} else {
    header("Location: index.php?error=Seems+you+are+on+the+wrong+page");
    exit;
}
