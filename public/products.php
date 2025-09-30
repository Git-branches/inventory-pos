<?php require_once __DIR__.'/../inc/auth_check.php'; ?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Products - Inventory POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<nav class="navbar navbar-expand bg-white shadow-sm"><div class="container"><a class="navbar-brand" href="dashboard.php">Inventory POS</a></div></nav>
<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>Products</h4>
    <div>
      <button id="btnAdd" class="btn btn-success">Add Product</button>
      <a class="btn btn-secondary" href="dashboard.php">Back</a>
    </div>
  </div>
  <table class="table table-hover" id="tbl"><thead><tr><th>ID</th><th>Name</th><th>Barcode</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody></tbody></table>
</div>

<!-- Modal -->
<div class="modal" tabindex="-1" id="modal">
  <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">
    <form id="form">
      <input type="hidden" name="id">
      <div class="mb-2"><input name="name" class="form-control" placeholder="Name" required></div>
      <div class="mb-2"><input name="sku" class="form-control" placeholder="SKU"></div>
      <div class="mb-2"><input name="barcode" class="form-control" placeholder="Barcode"></div>
      <div class="mb-2"><input name="sell_price" class="form-control" placeholder="Sell Price" type="number" step="0.01"></div>
      <div class="mb-2 row"><div class="col"><input name="stock" class="form-control" placeholder="Stock" type="number"></div><div class="col"><input name="reorder_level" class="form-control" placeholder="Reorder Level" type="number"></div></div>
      <div class="d-grid"><button class="btn btn-primary">Save</button></div>
    </form>
    <div id="barcodePreview" class="mt-3 text-center"></div>
  </div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
let modal = new bootstrap.Modal(document.getElementById('modal'));
function load(){
  $.getJSON('../api/products.php?action=list', function(res){
    let rows = '';
    res.forEach(p => {
      rows += `<tr>
        <td>${p.id}</td><td>${p.name}</td><td>${p.barcode||''}</td><td>${p.sell_price}</td><td>${p.stock}</td>
        <td>
          <button class="btn btn-sm btn-primary btn-edit" data-id="${p.id}">Edit</button>
          <button class="btn btn-sm btn-danger btn-del" data-id="${p.id}">Delete</button>
        </td></tr>`;
    });
    $('#tbl tbody').html(rows);
  });
}
$(function(){
  load();
  $('#btnAdd').click(()=>{ $('#form')[0].reset(); $('#barcodePreview').html(''); modal.show(); });
  $(document).on('click','.btn-edit', function(){ let id=$(this).data('id'); $.getJSON('../api/products.php?action=get&id='+id, function(p){ $('#form [name=id]').val(p.id); $('#form [name=name]').val(p.name); $('#form [name=sku]').val(p.sku); $('#form [name=barcode]').val(p.barcode); $('#form [name=sell_price]').val(p.sell_price); $('#form [name=stock]').val(p.stock); $('#form [name=reorder_level]').val(p.reorder_level); if(p.barcode) $('#barcodePreview').html('<svg id="barcode"></svg>'), JsBarcode('#barcode', p.barcode, {displayValue:true}); modal.show(); }); });
  $(document).on('click','.btn-del', function(){ if(!confirm('Delete?')) return; $.post('../api/products.php?action=delete',{id:$(this).data('id')}, function(){ load(); }, 'json'); });
  $('#form').on('submit', function(e){ e.preventDefault(); let id = $('#form [name=id]').val(); let data = $(this).serialize(); let url = '../api/products.php?action=' + (id ? 'update' : 'create'); $.post(url, data, function(res){ if(res.success){ modal.hide(); load(); } else alert('Error'); }, 'json'); });
  $('#form [name=barcode]').on('input', function(){ let v=$(this).val().trim(); if(v) $('#barcodePreview').html('<svg id="barcode"></svg>'), JsBarcode('#barcode', v, {displayValue:true}); else $('#barcodePreview').html(''); });
});
</script>
</body></html>
