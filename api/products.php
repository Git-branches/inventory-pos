<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

$action = $_GET['action'] ?? 'list';
if($action == 'list'){
    $stmt = $pdo->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
    json_response($stmt->fetchAll());
}
if($action == 'get'){
    $id = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    json_response($stmt->fetch());
}
if($action == 'get_by_barcode'){
    $barcode = $_GET['barcode'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM products WHERE barcode = ? LIMIT 1');
    $stmt->execute([$barcode]);
    $p = $stmt->fetch();
    if($p) json_response(['success'=>true,'product'=>$p]);
    json_response(['success'=>false,'error'=>'Product not found']);
}
if($action == 'create'){
    require_post();
    $data = $_POST;
    $stmt = $pdo->prepare('INSERT INTO products (sku,name,barcode,category_id,cost_price,sell_price,stock,reorder_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([ $data['sku'] ?: null, $data['name'], $data['barcode'], $data['category_id'] ?: null, $data['cost_price']?:0, $data['sell_price']?:0, $data['stock']?:0, $data['reorder_level']?:0 ]);
    json_response(['success'=>true,'id'=>$pdo->lastInsertId()]);
}
if($action == 'update'){
    require_post();
    $data = $_POST;
    $stmt = $pdo->prepare('UPDATE products SET sku=?, name=?, barcode=?, category_id=?, cost_price=?, sell_price=?, stock=?, reorder_level=? WHERE id=?');
    $stmt->execute([ $data['sku']?:null, $data['name'], $data['barcode'], $data['category_id']?:null, $data['cost_price']?:0, $data['sell_price']?:0, $data['stock']?:0, $data['reorder_level']?:0, $data['id'] ]);
    json_response(['success'=>true]);
}
if($action == 'delete'){
    require_post();
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    json_response(['success'=>true]);
}
?>