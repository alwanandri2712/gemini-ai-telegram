# Bot Telegram Gemini AI

Bot Telegram yang terintegrasi dengan Google Gemini AI dan OpenWeatherMap API untuk memberikan respons AI dan informasi cuaca.

## Fitur

- Perintah `/ai`: Dapatkan respons bertenaga AI menggunakan Google Gemini API
- Perintah `/cuaca`: Dapatkan informasi cuaca untuk lokasi tertentu

## Struktur Project

```
├── config/
│   └── config.php         # File konfigurasi untuk API keys
├── src/
│   ├── api/
│   │   ├── GeminiAPI.php  # Integrasi Google Gemini API
│   │   ├── TelegramBot.php # Integrasi Telegram Bot API
│   │   └── WeatherAPI.php # Integrasi OpenWeatherMap API
│   └── utils/
│       └── Formatter.php  # Utilitas formatting teks
├── autoload.php           # Class autoloader
├── webhook.php            # Handler webhook utama
└── README.md              # Dokumentasi project
```

## Persyaratan

- PHP 7.4 atau lebih tinggi
- Web server dengan dukungan HTTPS (diperlukan untuk webhook Telegram)
- Akun Telegram
- Google Gemini API key
- OpenWeatherMap API key

## Panduan Setup Lengkap

### 1. Membuat Bot Telegram

1. Buka Telegram dan cari `@BotFather`
2. Mulai chat dengan BotFather dan kirim perintah `/newbot`
3. Ikuti instruksi untuk membuat bot baru:
   - Berikan nama untuk bot Anda
   - Berikan username untuk bot Anda (harus diakhiri dengan 'bot')
4. BotFather akan memberikan token. Simpan token ini karena Anda akan membutuhkannya nanti.

### 2. Mendapatkan API Keys

#### Google Gemini API Key
1. Kunjungi [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Masuk dengan akun Google Anda
3. Buat API key baru
4. Salin API key tersebut

#### OpenWeatherMap API Key
1. Kunjungi [OpenWeatherMap](https://openweathermap.org/)
2. Buat akun atau masuk
3. Pergi ke profil Anda dan navigasi ke bagian API keys
4. Generate API key baru

### 3. Konfigurasi Bot

1. Clone atau download repository ini ke web server Anda
2. Update API keys di `config/config.php`:

```php
return [
    'gemini' => [
        'api_key' => 'YOUR_GEMINI_API_KEY',
        'model' => 'gemini-1.5-flash-latest'
    ],
    'telegram' => [
        'bot_token' => 'YOUR_TELEGRAM_BOT_TOKEN'
    ],
    'weather' => [
        'api_key' => 'YOUR_OPENWEATHERMAP_API_KEY',
        'base_url' => 'https://api.openweathermap.org/data/2.5/weather'
    ]
];
```

### 4. Opsi Deployment

#### Opsi A: Hosting di VPS atau Shared Hosting

1. **Shared Hosting**:
   - Upload semua file ke direktori public (biasanya `public_html` atau `www`) di hosting Anda
   - Pastikan hosting mendukung PHP 7.4+ dan ekstensi cURL
   - Webhook akan berjalan otomatis ketika ada request dari Telegram

2. **VPS (Virtual Private Server)**:
   - Upload semua file ke direktori web server Anda (misalnya `/var/www/html/` untuk Apache)
   - Pastikan web server (Apache/Nginx) sudah terkonfigurasi dengan benar
   - Pastikan PHP dan ekstensi yang diperlukan sudah terinstal
   - Webhook akan berjalan sebagai bagian dari web server

#### Opsi B: Menjalankan di Localhost (Development)

1. **Menggunakan XAMPP/MAMP/WAMP**:
   - Letakkan file-file di direktori `htdocs` (XAMPP) atau direktori web server lokal Anda
   - Jalankan web server lokal Anda
   - Gunakan ngrok untuk membuat tunnel HTTPS (lihat di bawah)

2. **Menggunakan PHP Built-in Server** (untuk development):
   - Buka terminal dan arahkan ke direktori project
   - Jalankan perintah: `php -S localhost:8000`
   - Server akan berjalan di `localhost:8000`
   - Gunakan ngrok untuk membuat tunnel HTTPS (lihat di bawah)

### 5. Setup Webhook

#### Menggunakan Web Server dengan HTTPS
1. Upload file-file ke web server dengan dukungan HTTPS
2. Set webhook URL dengan mengunjungi URL ini di browser (ganti dengan nilai Anda):
   ```
   https://api.telegram.org/bot{YOUR_BOT_TOKEN}/setWebhook?url=https://your-domain.com/path-to/webhook.php
   ```
3. Anda akan menerima konfirmasi bahwa webhook berhasil diatur

#### Menggunakan ngrok untuk Development Lokal
1. Download dan install [ngrok](https://ngrok.com/)
2. Jalankan ngrok pada port web server lokal Anda:
   ```
   ngrok http 8000  # Jika menggunakan PHP built-in server
   ```
   atau
   ```
   ngrok http 80    # Jika menggunakan XAMPP/WAMP/MAMP
   ```
3. Salin URL HTTPS yang diberikan oleh ngrok
4. Set webhook URL:
   ```
   https://api.telegram.org/bot{YOUR_BOT_TOKEN}/setWebhook?url=https://your-ngrok-url/webhook.php
   ```

> **Catatan Penting**: 
> - Webhook tidak perlu "dijalankan" secara manual di terminal. Webhook adalah file PHP yang akan dieksekusi setiap kali Telegram mengirimkan update ke URL yang telah Anda daftarkan.
> - Untuk production, sangat disarankan menggunakan VPS atau shared hosting dengan HTTPS.
> - Ngrok hanya untuk development dan testing, jangan digunakan untuk production.

### 6. Test Bot Anda

1. Buka Telegram dan cari bot Anda berdasarkan username
2. Mulai chat dengan bot Anda
3. Kirim perintah `/ai Hello, how are you?`
4. Bot akan merespons dengan pesan yang dihasilkan AI
5. Kirim perintah `/cuaca Jakarta` (atau kota lainnya)
6. Bot akan merespons dengan informasi cuaca untuk kota tersebut

## Contoh Penggunaan

### Perintah AI

```
/ai Apa ibu kota Prancis?
```

Bot akan merespons dengan informasi tentang Paris sebagai ibu kota Prancis.

```
/ai Buatkan puisi pendek tentang teknologi
```

Bot akan menghasilkan puisi tentang teknologi menggunakan Gemini AI.

### Perintah Cuaca

```
/cuaca New York
```

Bot akan memberikan informasi cuaca terkini untuk New York, termasuk suhu dan kondisi.

```
/cuaca Tokyo
```

Bot akan memberikan informasi cuaca terkini untuk Tokyo.

## Troubleshooting

### Bot Tidak Merespons
- Verifikasi bahwa webhook Anda sudah diatur dengan benar
- Periksa log server Anda untuk error PHP
- Pastikan semua API keys valid dan dikonfigurasi dengan benar

### Error API
- Untuk masalah Gemini API, periksa [dokumentasi Google Gemini](https://ai.google.dev/docs)
- Untuk masalah OpenWeatherMap, periksa [dokumentasi OpenWeatherMap](https://openweathermap.org/api)

## Kustomisasi

### Menambahkan Perintah Baru

Untuk menambahkan perintah baru, modifikasi file `webhook.php` dan tambahkan kondisi baru di bagian pemrosesan perintah utama:

```php
if (strpos($message_text, '/ai') === 0) {
    processAiCommand($chat_id, $message_text, $telegram);
} elseif (strpos($message_text, '/cuaca') === 0) {
    processWeatherCommand($chat_id, $message_text, $telegram);
} elseif (strpos($message_text, '/your_new_command') === 0) {
    processYourNewCommand($chat_id, $message_text, $telegram);
} else {
    $telegram->sendMessage($chat_id, "Gunakan perintah /ai diikuti dengan pertanyaan Anda, atau /cuaca untuk informasi cuaca.");
}
```

Kemudian implementasikan fungsi pemrosesan perintah baru.

### Mengubah Model AI

Untuk menggunakan model Gemini yang berbeda, update nama model di `config/config.php`:

```php
'gemini' => [
    'api_key' => 'YOUR_GEMINI_API_KEY',
    'model' => 'gemini-1.5-pro-latest' // Ubah ke model yang Anda inginkan
],
```
- Kirim `/cuaca [nama kota]` untuk mendapatkan informasi cuaca