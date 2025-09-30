<?php
// Helper functions
function json_response($data){ header('Content-Type: application/json'); echo json_encode($data); exit; }
function require_post(){ if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); json_response(['error'=>'Method not allowed']); } }
function sanitize($v){ return htmlspecialchars(trim($v)); }
function current_user(){ return $_SESSION['user'] ?? null; }
?>