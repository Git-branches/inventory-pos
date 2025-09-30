<?php
// public/index.php - login
session_start();
if(isset($_SESSION['user'])){ header('Location: dashboard.php'); exit; }
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Inventory POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-3">Inventory POS</h3>
          <p class="text-muted">Sign in to your account</p>
          <form id="loginForm">
            <div class="mb-2"><input class="form-control form-control-lg" name="username" placeholder="Username" required></div>
            <div class="mb-3"><input class="form-control form-control-lg" name="password" type="password" placeholder="Password" required></div>
            <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Sign In</button></div>
          </form>
          <div id="msg" class="mt-3 text-danger"></div>
        </div>
      </div>
      <p class="text-center text-muted mt-2">Default admin: <strong>admin</strong> / <strong>Admin@123</strong> (run create_admin.php)</p>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#loginForm').on('submit', function(e){
  e.preventDefault();
  $.post('../api/auth.php?action=login', $(this).serialize(), function(res){
    if(res.success) location.href='dashboard.php';
    else $('#msg').text(res.error);
  }, 'json');
});
</script>
</body></html>
