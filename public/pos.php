<?php require_once __DIR__.'/../inc/auth_check.php'; ?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>POS - Inventory POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
<style>#scanner{width:100%;height:320px;border:1px solid #eaeaea;border-radius:6px;overflow:hidden}</style>
</head><body>
<nav class="navbar navbar-expand bg-white shadow-sm"><div class="container"><a class="navbar-brand" href="dashboard.php">Inventory POS</a></div></nav>
<div class="container py-4">
  <div class="row">
    <div class="col-md-7">
      <div id="scanner"></div>
      <div class="input-group my-2"><input id="manual" class="form-control" placeholder="Enter barcode or product name and press Enter"><button class="btn btn-outline-secondary" id="btnSearch">Search</button></div>
      <div id="productList" class="row g-2"></div>
    </div>
    <div class="col-md-5">
      <div class="card"><div class="card-body">
        <h5 class="card-title">Cart</h5>
        <div id="cart" style="min-height:220px"></div>
        <hr>
        <div class="d-flex justify-content-between"><div>Total</div><div><strong id="total">0.00</strong></div></div>
        <div class="mt-3">
          <button id="checkout" class="btn btn-success w-100">Checkout</button>
        </div>
      </div></div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/quagga/dist/quagga.min.js"></script>
<script>
let cart = [];
function renderCart(){ let html=''; let total=0; cart.forEach((it,idx)=>{ total += it.qty*it.unit_price; html += `<div class="d-flex justify-content-between align-items-center mb-2"><div><strong>${it.name}</strong><div class="text-muted small">${it.qty} x ${it.unit_price.toFixed(2)}</div></div><div><button class="btn btn-sm btn-danger btn-remove" data-idx="${idx}">Remove</button></div></div>`; }); $('#cart').html(html); $('#total').text(total.toFixed(2)); }
function addToCart(p){ let idx = cart.findIndex(i=>i.product_id==p.id); if(idx>=0) cart[idx].qty++; else cart.push({product_id:p.id,name:p.name,qty:1,unit_price:parseFloat(p.sell_price)}); renderCart(); }
function searchBarcode(code){ $.getJSON('../api/products.php?action=get_by_barcode&barcode='+encodeURIComponent(code), function(res){ if(res.success) addToCart(res.product); else alert('Not found: '+code); }); }
function searchText(q){ $.getJSON('../api/products.php?action=list', function(res){ let list = res.filter(p=> p.name.toLowerCase().includes(q.toLowerCase()) || (p.barcode && p.barcode.includes(q))); let html=''; list.slice(0,20).forEach(p=>{ html += `<div class="col-md-6"><div class="card p-2"><div class="d-flex justify-content-between"><div><strong>${p.name}</strong><div class="small text-muted">${p.barcode||''}</div></div><div><button class="btn btn-sm btn-primary btn-add" data-id="${p.id}">Add</button></div></div></div></div>`; }); $('#productList').html(html); }); }

$(function(){
  // Init Quagga
  Quagga.init({ inputStream: { name: 'Live', type: 'LiveStream', target: document.querySelector('#scanner'), constraints: { facingMode: 'environment' } }, decoder: { readers: ['ean_reader','code_128_reader','upc_reader'] }}, function(err){ if(err){ console.log(err); $('#scanner').text('Camera not available'); return; } Quagga.start(); });
  Quagga.onDetected(data => { let code = data.codeResult.code; searchBarcode(code); });

  $('#btnSearch').on('click', function(){ let q = $('#manual').val().trim(); if(!q) return; if(/^[0-9-]+$/.test(q)) searchBarcode(q); else searchText(q); $('#manual').val(''); });
  $('#manual').on('keypress', function(e){ if(e.key==='Enter'){ $('#btnSearch').click(); } });
  $(document).on('click','.btn-add', function(){ let id=$(this).data('id'); $.getJSON('../api/products.php?action=get&id='+id, function(p){ addToCart(p); }); });
  $(document).on('click','.btn-remove', function(){ cart.splice($(this).data('idx'),1); renderCart(); });
  $('#checkout').on('click', function(){
    if(cart.length==0){ alert('Cart empty'); return; }
    $.ajax({ url: '../api/pos.php', method: 'POST', contentType:'application/json', data: JSON.stringify({ cart: cart, paid: null, payment_method:'cash' }), success: function(res){ if(res.success){ alert('Sale complete. Invoice: '+res.invoice); cart=[]; renderCart(); } else alert('Error: '+res.error); }, dataType:'json' });
  });
});
</script>
</body></html>
