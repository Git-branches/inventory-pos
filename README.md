# Inventory POS - NO DESIGN AND WALA PA NA TAPOS KAY GI KAPUY KO 

## Setup
1. Import `sql/schema.sql` into MySQL (creates tables). 
2. Update DB credentials in `inc/db.php` if needed.
3. Run `create_admin.php` once to create default admin:
   - Username: **admin**
   - Password: **Admin@123**
4. Open `public/index.php` to login.

## Notes
- POS uses QuaggaJS (camera) for barcode scanning and works best on mobile Chrome.
- JsBarcode can be used to render barcodes in product pages.
- Change the admin password after first login.
