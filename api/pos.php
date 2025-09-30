<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
require_post();
$input = json_decode(file_get_contents('php://input'), true);
if(!$input || !isset($input['cart']) || !is_array($input['cart'])) json_response(['success'=>false,'error'=>'Invalid input']);
$cart = $input['cart'];
$user = $_SESSION['user'] ?? null;
$user_id = $user['id'] ?? null;
try{
    $pdo->beginTransaction();
    $total = 0;
    foreach($cart as $it) { $total += $it['qty'] * $it['unit_price']; }
    $invoice = 'INV'.date('YmdHis');
    $stmt = $pdo->prepare('INSERT INTO sales (invoice_no, user_id, total_amount, paid_amount, payment_method) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$invoice, $user_id, $total, $input['paid'] ?? $total, $input['payment_method'] ?? 'cash']);
    $sale_id = $pdo->lastInsertId();
    $stmtItem = $pdo->prepare('INSERT INTO sale_items (sale_id, product_id, qty, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)');
    $stmtUpdate = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
    $stmtStock = $pdo->prepare('INSERT INTO stock_movements (product_id, change_qty, type, reference, user_id) VALUES (?, ?, ?, ?, ?)');
    foreach($cart as $it){
        $subtotal = $it['qty'] * $it['unit_price'];
        $stmtItem->execute([$sale_id, $it['product_id'], $it['qty'], $it['unit_price'], $subtotal]);
        $stmtUpdate->execute([$it['qty'], $it['product_id']]);
        $stmtStock->execute([$it['product_id'], -$it['qty'], 'sale', $invoice, $user_id]);
    }
    $pdo->commit();
    json_response(['success'=>true,'invoice'=>$invoice]);
} catch(Exception $e){
    $pdo->rollBack();
    json_response(['success'=>false,'error'=>$e->getMessage()]);
}
?>