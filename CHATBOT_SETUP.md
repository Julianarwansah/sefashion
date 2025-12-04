# Setup Chatbot AI - Panduan Lengkap

## 📋 Langkah Setup

### 1. Dapatkan Google AI API Key

1. **Kunjungi Google AI Studio:**
   - Buka: https://aistudio.google.com/u/0/api-keys
   - Login dengan akun Google Anda

2. **Buat API Key:**
   - Klik tombol **"Create API Key"**
   - Pilih project atau buat project baru
   - Copy API key yang dihasilkan (format: `AIzaSy...`)

### 2. Tambahkan API Key ke .env

Buka file `.env` di root project Anda dan tambahkan:

```env
# Google AI (Gemini) Configuration
GOOGLE_AI_API_KEY=AIzaSy...paste_your_api_key_here
CHATBOT_ENABLED=true
```

> ⚠️ **PENTING:** Jangan commit file `.env` ke Git! API key adalah data sensitif.

### 3. Verifikasi Setup

Chatbot sudah siap digunakan! Tidak perlu restart server.

## 🎯 Cara Menggunakan

### Untuk Customer:

1. **Buka halaman website** (home, shop, product, dll)
2. **Klik tombol chat** di pojok kanan bawah (icon chat ungu)
3. **Ketik pertanyaan** Anda
4. **Tekan Enter** atau klik tombol kirim

### Contoh Pertanyaan:

```
- "Halo, ada yang bisa dibantu?"
- "Berapa jumlah produk yang tersedia?"
- "Apa saja kategori produk?"
- "Berapa range harga produk?"
- "Rekomendasikan produk untuk saya"
```

## 🔧 Troubleshooting

### Chatbot tidak muncul?
- Pastikan Anda membuka halaman customer (bukan admin)
- Clear browser cache dan refresh halaman
- Check browser console untuk error

### Error "API key not configured"?
- Pastikan `GOOGLE_AI_API_KEY` sudah ditambahkan di `.env`
- Pastikan tidak ada spasi atau karakter tambahan
- Restart Laravel server jika perlu

### Chatbot tidak merespon?
- Check API key valid di Google AI Studio
- Pastikan ada koneksi internet
- Check Laravel logs: `storage/logs/laravel.log`

## 📊 Monitoring

### Check API Usage:
- Kunjungi: https://aistudio.google.com/
- Lihat usage statistics
- Google Gemini API memiliki free tier yang generous

### Check Chat History:
Database table `chat_messages` menyimpan semua percakapan untuk analytics.

## ✅ Checklist

- [ ] API key sudah didapatkan dari Google AI Studio
- [ ] API key sudah ditambahkan ke `.env`
- [ ] Chatbot button muncul di halaman customer
- [ ] Chatbot bisa merespon pertanyaan
- [ ] Database queries berfungsi (coba tanya jumlah produk)

## 🎉 Selesai!

Chatbot AI Anda sudah siap digunakan! 🚀
