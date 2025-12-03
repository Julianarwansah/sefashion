# Google OAuth Setup Guide

Panduan lengkap untuk mengatur Google OAuth di aplikasi SeFashion.

## Prerequisites

- Akun Google (Gmail)
- Akses ke [Google Cloud Console](https://console.cloud.google.com/)

## Langkah 1: Buat Project di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Klik dropdown project di bagian atas (sebelah kiri logo Google Cloud)
3. Klik **"New Project"**
4. Masukkan nama project: `SeFashion` (atau nama lain yang Anda inginkan)
5. Klik **"Create"**
6. Tunggu beberapa detik sampai project selesai dibuat

## Langkah 2: Enable Google+ API

1. Pastikan Anda sudah memilih project yang baru dibuat
2. Di menu sebelah kiri, klik **"APIs & Services"** > **"Library"**
3. Cari **"Google+ API"** di search bar
4. Klik pada **"Google+ API"**
5. Klik tombol **"Enable"**

## Langkah 3: Configure OAuth Consent Screen

1. Di menu sebelah kiri, klik **"APIs & Services"** > **"OAuth consent screen"**
2. Pilih **"External"** sebagai User Type
3. Klik **"Create"**
4. Isi form berikut:
   - **App name**: `SeFashion`
   - **User support email**: `julianarwansahh@gmail.com`
   - **App logo**: (Opsional) Upload logo aplikasi Anda
   - **Application home page**: `https://sefashion.my.id`
   - **Authorized domains**: `sefashion.my.id`
   - **Developer contact information**: `julianarwansahh@gmail.com`
5. Klik **"Save and Continue"**
6. Di halaman **"Scopes"**, klik **"Add or Remove Scopes"**
7. Pilih scope berikut:
   - `.../auth/userinfo.email`
   - `.../auth/userinfo.profile`
8. Klik **"Update"** kemudian **"Save and Continue"**
9. Di halaman **"Test users"**, tambahkan `julianarwansahh@gmail.com` (dan email lain untuk testing)
10. Klik **"Save and Continue"**
11. Review summary dan klik **"Back to Dashboard"**

## Langkah 4: Buat OAuth 2.0 Credentials

1. Di menu sebelah kiri, klik **"APIs & Services"** > **"Credentials"**
2. Klik **"Create Credentials"** > **"OAuth client ID"**
3. Pilih **"Web application"** sebagai Application type
4. Isi form berikut:
   - **Name**: `SeFashion Web Client`
   - **Authorized JavaScript origins**: 
     - `http://localhost:8000`
     - `https://sefashion.my.id`
   - **Authorized redirect URIs**:
     - `http://localhost:8000/auth/google/callback`
     - `https://sefashion.my.id/auth/google/callback`
5. Klik **"Create"**
6. **PENTING**: Popup akan muncul dengan **Client ID** dan **Client Secret**
   - Copy kedua nilai ini dan simpan di tempat yang aman
   - Anda akan membutuhkannya di langkah berikutnya

## Langkah 5: Konfigurasi Environment Variables

1. Buka file `.env` di root project Anda
2. Tambahkan konfigurasi Google OAuth:

```env
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

3. Ganti `your-client-id-here` dan `your-client-secret-here` dengan nilai yang Anda copy dari Google Cloud Console
4. Save file `.env`

## Langkah 6: Clear Config Cache

Jalankan command berikut untuk clear config cache:

```bash
php artisan config:clear
```

## Testing

### Test di Localhost

1. Jalankan aplikasi Laravel:
   ```bash
   php artisan serve
   ```

2. Buka browser dan akses: `http://localhost:8000/login`

3. Klik tombol **"Login with Google"**

4. Anda akan diredirect ke halaman login Google

5. Pilih akun Google Anda

6. Jika diminta, klik **"Allow"** untuk memberikan permission

7. Anda akan diredirect kembali ke aplikasi dan otomatis login

### Test Scenarios

1. **User Baru (Belum Pernah Daftar)**
   - Login dengan Google menggunakan email yang belum terdaftar
   - Sistem akan otomatis membuat account baru
   - User langsung login

2. **User Existing (Sudah Pernah Login dengan Google)**
   - Login dengan Google menggunakan email yang sudah pernah login via Google
   - User langsung login

3. **User Existing (Daftar Manual)**
   - Login dengan Google menggunakan email yang sudah terdaftar via form registrasi
   - Sistem akan link Google account ke account existing
   - User langsung login

## Production Deployment

Ketika deploy ke production:

1. Update **Authorized JavaScript origins** dan **Authorized redirect URIs** di Google Cloud Console dengan domain production Anda

2. Update `.env` di server production:
   ```env
   GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
   ```

3. Jangan lupa run:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

## Troubleshooting

### Error: "redirect_uri_mismatch"

**Penyebab**: Redirect URI yang dikonfigurasi di Google Cloud Console tidak match dengan yang ada di aplikasi.

**Solusi**:
1. Cek file `.env`, pastikan `GOOGLE_REDIRECT_URI` benar
2. Cek Google Cloud Console > Credentials > OAuth 2.0 Client IDs
3. Pastikan redirect URI di Google Cloud Console sama persis dengan yang di `.env`
4. Jangan lupa run `php artisan config:clear`

### Error: "invalid_client"

**Penyebab**: Client ID atau Client Secret salah.

**Solusi**:
1. Cek kembali `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env`
2. Pastikan tidak ada spasi atau karakter tambahan
3. Run `php artisan config:clear`

### Error: "Access blocked: This app's request is invalid"

**Penyebab**: OAuth consent screen belum dikonfigurasi dengan benar.

**Solusi**:
1. Kembali ke Google Cloud Console > OAuth consent screen
2. Pastikan semua informasi sudah diisi dengan benar
3. Pastikan scope email dan profile sudah ditambahkan

## Security Notes

- **JANGAN** commit file `.env` ke Git repository
- **JANGAN** share Client Secret ke public
- Untuk production, pertimbangkan untuk verify app di Google (untuk menghilangkan warning "This app isn't verified")
- Gunakan HTTPS di production

## Support

Jika mengalami masalah, cek:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console untuk error JavaScript
3. Google Cloud Console > APIs & Services > Credentials untuk memastikan konfigurasi benar
