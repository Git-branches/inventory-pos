<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

$action = $_GET['action'] ?? 'login';
if($action == 'login'){
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    if(!$username || !$password) json_response(['success'=>false,'error'=>'Missing credentials']);
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password_hash'])){
        $_SESSION['user'] = ['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role'],'full_name'=>$user['full_name']];
        json_response(['success'=>true]);
    } else {
        json_response(['success'=>false,'error'=>'Invalid username or password']);
    }
}
if($action == 'logout'){
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../public/index.php');
    exit;
}
?>