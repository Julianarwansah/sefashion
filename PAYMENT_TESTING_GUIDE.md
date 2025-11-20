# Payment Flow Testing Guide

## Halaman yang Sudah Dibuat

### 1. **Waiting Payment Page**
File: `resources/views/frontend/waiting-payment.blade.php`
- Auto-check payment status setiap 5 detik
- Menampilkan Order ID dan Total Pembayaran
- Auto-redirect ke success/failed page

### 2. **Payment Success Page**
File: `resources/views/frontend/payment-success.blade.php`
- Animated success checkmark
- Order summary lengkap
- Preview order items dengan gambar
- Tombol "View Order Details" dan "My Orders"

### 3. **Payment Failed Page**
File: `resources/views/frontend/payment-failed.blade.php`
- Error icon dan pesan
- Alasan kemungkinan gagal
- Tombol "Try Again" dan "View My Orders"

---

## Cara Testing Payment Flow

### Option 1: Testing dengan Simulasi Manual (Tanpa Xendit)

#### Step 1: Buat Order Baru
1. Login sebagai customer
2. Tambahkan produk ke cart
3. Klik "Checkout"
4. Isi form checkout (alamat, shipping, payment method)
5. Klik "Place Order"
6. Anda akan di-redirect ke **Waiting Payment Page** (`/order/success/{id}`)

#### Step 2: Lihat Waiting Payment Page
- Halaman ini menampilkan:
  - Order ID
  - Total Payment
  - Status "Checking payment status..."
  - Payment instructions
  - Tombol "Check Status" dan "View My Orders"

#### Step 3: Simulasi Payment Success (Manual)
Untuk testing, update status pembayaran manual di database:

```sql
-- Cek payment yang belum dibayar
SELECT * FROM pembayaran WHERE status_pembayaran = 'belum bayar';

-- Update status payment menjadi sudah bayar
UPDATE pembayaran
SET status_pembayaran = 'sudah bayar'
WHERE id_pemesanan = [ORDER_ID];

-- Update status order menjadi diproses
UPDATE pemesanan
SET status = 'diproses'
WHERE id_pemesanan = [ORDER_ID];
```

Setelah update, **Waiting Payment Page** akan otomatis detect perubahan dan redirect ke **Payment Success Page**.

#### Step 4: Simulasi Payment Failed (Manual)
```sql
-- Update status payment menjadi gagal
UPDATE pembayaran
SET status_pembayaran = 'gagal'
WHERE id_pemesanan = [ORDER_ID];
```

Halaman akan otomatis redirect ke **Payment Failed Page**.

---

### Option 2: Testing dari My Orders Page

#### Step 1: Buka My Orders
1. Login sebagai customer
2. Klik menu "My Orders" di navbar
3. Lihat list order yang pernah dibuat

#### Step 2: Klik Tombol "Pay Now"
- Untuk order dengan status **Pending** dan **belum dibayar**, akan muncul tombol **"Pay Now"** (biru gradient)
- Klik tombol tersebut
- Anda akan di-redirect ke **Waiting Payment Page**

#### Step 3: Atau Klik "View Details"
1. Klik "View Details" pada order apapun
2. Di halaman Order Detail, untuk order pending akan ada tombol:
   - **"Complete Payment Now"** (biru gradient) - untuk lanjut bayar
   - **"Cancel This Order"** (merah) - untuk cancel order

---

### Option 3: Testing dengan Xendit Sandbox (Real Integration)

#### Prerequisite:
1. Pastikan Xendit API keys sudah terkonfigurasi di `.env`
2. Gunakan Xendit Sandbox/Test Mode

#### Step 1: Checkout dengan Payment Method
1. Pilih payment method (Virtual Account, E-Wallet, Retail, atau COD)
2. Pilih channel (contoh: BCA, Mandiri, OVO, GoPay, dll)
3. Submit checkout

#### Step 2: Di Waiting Payment Page
Halaman akan menampilkan:
- **Virtual Account Number** (untuk VA payment) - dengan tombol copy
- **Payment URL button** (untuk E-Wallet/Retail) - klik untuk bayar
- **QR Code** (untuk QRIS payment) - scan dengan mobile app
- Payment expiry time
- Auto-check status setiap 5 detik

#### Step 3: Simulasi Payment di Xendit Dashboard

##### Untuk Virtual Account (VA):
1. Login ke **Xendit Dashboard** (https://dashboard.xendit.co/)
2. Masuk ke **Test Mode** (toggle di pojok kanan atas)
3. Klik menu **Transactions** → **Virtual Accounts**
4. Cari VA yang baru dibuat (sesuai order ID)
5. Klik tombol **Simulate Payment** atau **Actions** → **Complete Payment**
6. Isi amount sesuai total order
7. Klik **Confirm**
8. ✅ Payment berhasil → Webhook otomatis terkirim → Halaman auto-redirect ke success page!

##### Untuk E-Wallet (OVO, GoPay, DANA, dll):
1. Klik tombol **"Complete Payment Now"** di waiting payment page
2. Anda akan diarahkan ke halaman pembayaran Xendit
3. Di halaman Xendit test mode, klik **"Simulate Success"**
4. ✅ Payment berhasil → Auto redirect ke success page

##### Untuk Retail (Alfamart, Indomaret):
1. Login ke Xendit Dashboard
2. Menu **Transactions** → **Retail Outlets**
3. Cari payment code yang dibuat
4. Klik **Simulate Payment**
5. ✅ Payment berhasil

#### Step 4: Testing Webhook
Xendit akan mengirim webhook ke endpoint `/xendit/callback` ketika:
- Payment berhasil → Auto update status ke "sudah bayar" + order status "diproses"
- Payment gagal/expired → Auto update status ke "gagal"

**Cek webhook log:**
```bash
tail -f storage/logs/laravel.log
```

Akan ada log:
```
[timestamp] local.INFO: Xendit Webhook Received: {...}
[timestamp] local.INFO: Payment marked as paid: external_id
```

---

## Flow Diagram

```
1. Customer Checkout
   ↓
2. Order Created (status: pending, payment: belum bayar)
   ↓
3. Redirect ke Waiting Payment Page (/order/success/{id})
   ↓
4a. Payment Success
    → Auto redirect ke Payment Success Page
    → Order status: diproses

4b. Payment Failed/Expired
    → Auto redirect ke Payment Failed Page
    → Order status: pending (atau batal)
```

---

## Testing Checklist

- [ ] Order baru redirect ke Waiting Payment Page
- [ ] Auto-check payment status berjalan setiap 5 detik
- [ ] Update manual payment status → auto redirect ke success page
- [ ] Update manual payment failed → auto redirect ke failed page
- [ ] Tombol "Pay Now" muncul di My Orders untuk order pending
- [ ] Tombol "Complete Payment Now" muncul di Order Detail
- [ ] Payment Success page menampilkan order items dengan benar
- [ ] Payment Failed page menampilkan error message
- [ ] Tombol "Check Status" manual berfungsi
- [ ] Cancel order berfungsi untuk status pending

---

## Troubleshooting

### Issue: Stuck di Waiting Payment Page
**Solution**:
- Cek apakah JavaScript berjalan (lihat Console)
- Cek endpoint `/payment/status/{orderId}` mengembalikan response yang benar
- Pastikan status_pembayaran di database sudah update

### Issue: Error saat redirect
**Solution**:
- Pastikan route `payment.success` dan `payment.failed` sudah terdaftar
- Cek di `routes/web.php` line 83-85

### Issue: Payment status tidak update
**Solution**:
- Cek method `checkPaymentStatus()` di `CheckoutController.php`
- Pastikan mapping status benar (paid, failed, pending)
- Cek koneksi database

---

## Advanced Testing: Webhook Simulation

Untuk testing Xendit webhook tanpa menunggu payment real:

### 1. Install tool seperti Postman atau curl

### 2. Kirim POST request ke `/xendit/callback`
```json
{
  "event": "payment.succeeded",
  "data": {
    "external_id": "ORDER-{orderId}-{timestamp}",
    "id": "va_xxxxxxxx",
    "status": "COMPLETED"
  }
}
```

### 3. Cek log di `storage/logs/laravel.log`
Akan ada log: "Payment marked as paid: external_id"

---

## File-File Penting

1. **Routes**: `routes/web.php` (line 83-110)
2. **Controller**: `app/Http/Controllers/CheckoutController.php`
3. **Views**:
   - `resources/views/frontend/waiting-payment.blade.php`
   - `resources/views/frontend/payment-success.blade.php`
   - `resources/views/frontend/payment-failed.blade.php`
   - `resources/views/frontend/my-orders.blade.php`
   - `resources/views/frontend/order-detail.blade.php`

---

## Summary

Payment flow sudah **100% complete** dengan:
- ✅ Waiting payment page dengan auto-check status
- ✅ Payment success page dengan order details
- ✅ Payment failed page dengan error handling
- ✅ Integration dengan My Orders page
- ✅ Tombol "Pay Now" dan "Complete Payment Now"
- ✅ Auto-redirect berdasarkan payment status
- ✅ Xendit webhook integration
- ✅ Manual payment status checking

Semua siap untuk production! 🚀
