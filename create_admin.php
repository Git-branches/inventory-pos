<?php
// create_admin.php - run this once after importing schema to create admin user
require_once __DIR__ . '/inc/db.php';

$username = 'admin';
$password = 'Admin@123'; // default password - change after first login
$full_name = 'Administrator';
$role = 'admin';

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
if($stmt->fetch()){
    echo 'Admin user already exists.';
    exit;
}

$stmt = $pdo->prepare('INSERT INTO users (username, password_hash, full_name, role) VALUES (?, ?, ?, ?)');
if($stmt->execute([$username, $hash, $full_name, $role])){
    echo "Admin created successfully.\nUsername: $username\nPassword: $password\nPlease change the password after first login.";
} else {
    echo 'Failed to create admin.';
}
?>