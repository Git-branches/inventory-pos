<?php require_once __DIR__.'/../inc/auth_check.php'; ?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Inventory POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<nav class="navbar navbar-expand bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#">Inventory POS</a>
    <div class="ms-auto">
      <a class="btn btn-outline-secondary me-2" href="products.php">Products</a>
      <a class="btn btn-primary me-2" href="pos.php">Open POS</a>
      <a class="btn btn-danger" href="../api/auth.php?action=logout">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-4">
  <div class="row">
    <div class="col-md-4">
      <div class="card mb-3"><div class="card-body">
        <h5>Quick Stats</h5>
        <p class="mb-0">Sales today: <strong>0</strong></p>
        <p>Low stock items: <strong>--</strong></p>
      </div></div>
    </div>
    <div class="col-md-8">
      <div class="card"><div class="card-body">
        <h5>Recent Activity</h5>
        <p class="text-muted">No recent activity</p>
      </div></div>
    </div>
  </div>
</div>
</body></html>
