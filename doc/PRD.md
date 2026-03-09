Product Requirements Document (PRD)

Project Name: DevShort - Advanced URL Shortener & Link Management
Document Version: 1.0
Date: 10 Maret 2026

1. Ringkasan Eksekutif (Executive Summary)

DevShort adalah aplikasi web berbasis SaaS (Software as a Service) yang memungkinkan pengguna untuk memendekkan URL panjang, menggunakan custom alias, menerapkan custom branded domain, dan melacak analitik tautan. Produk ini menargetkan pemasar digital, pengembang, dan perusahaan yang membutuhkan kontrol penuh atas identitas tautan mereka dengan model monetisasi berlangganan (Freemium hingga Enterprise).

2. Tujuan Produk (Product Goals)

Menyediakan alat pemendek tautan yang cepat dan mudah digunakan.

Memberikan fitur branding tingkat lanjut (Custom Domain & QR Code).

Menyediakan analitik komprehensif untuk melacak performa tautan.

Membangun aliran pendapatan berkelanjutan melalui model Subscription/SaaS.

3. Peran Pengguna (User Roles)

Guest (Pengunjung): Dapat melihat landing page dan membuat short link dasar tanpa analitik.

Free User: Pengguna terdaftar dengan akses ke dashboard dasar, limit pembuatan link, dan analitik standar.

Pro/Enterprise User: Pengguna berbayar dengan akses ke fitur premium (Custom Domain, Password Protection, Link Expiration, Full Analytics, API Access).

Super Admin: Administrator platform yang memiliki akses penuh ke sistem backend, manajemen pengguna, dan pengaturan subsription/keuangan.

4. Fitur Utama Pengguna (End-User Features)

4.1. Link Management

Shorten URL: Input URL panjang menjadi URL pendek (misal: devsh.rt/xyz).

Custom Alias: Pengguna dapat menentukan akhiran link (misal: devsh.rt/promoku).

Link Expiration: Mengatur tanggal/waktu kapan link tidak lagi dapat diakses.

Password Protection: Link hanya bisa diakses setelah memasukkan password yang benar.

Link Preview: Kustomisasi meta tag (Title, Description, Image) untuk preview di media sosial.

4.2. Branding & Distribution

Custom Domain: Pengguna dapat mengarahkan domain mereka sendiri (misal: link.brandmu.com) ke server DevShort via CNAME.

QR Code Generator: Otomatis membuat QR Code untuk setiap link yang dapat diunduh (PNG/SVG) dan dikustomisasi warnanya.

4.3. Analytics & Dashboard

Click Tracking: Jumlah total klik dan unique clicks.

Referrers: Melacak sumber trafik (Facebook, Twitter, Direct, dll).

Geolokasi & Device: Melacak negara asal dan jenis perangkat (Mobile/Desktop, OS, Browser).

5. 🌟 SOROTAN: Fitur Super Admin & Manajemen Subscription

Bagian ini dikhususkan untuk Super Admin yang mengelola operasional bisnis dan teknis platform DevShort.

5.1. Manajemen Paket Berlangganan (Subscription Engine)

Super Admin memiliki kontrol penuh atas skema harga dan batasan fitur tanpa perlu hard-code ulang aplikasi.

CRUD Pricing Plans: Membuat, mengedit, atau menghapus paket (contoh: Free, Basic, Pro, Enterprise).

Feature Gating & Quota Management: - Mengatur limit jumlah short link per bulan untuk tiap paket.

Mengatur limit jumlah custom domain.

Mengaktifkan/menonaktifkan fitur spesifik per paket (misal: toggle Password Protection hanya untuk paket Pro ke atas).

Pricing Configuration: Mengatur harga bulanan/tahunan, diskon, dan masa uji coba (trial period).

5.2. Manajemen Transaksi & Keuangan

Dashboard Pendapatan (MRR/ARR): Metrik real-time untuk Monthly Recurring Revenue, Churn Rate, dan Customer Lifetime Value (CLTV).

Payment Gateway Monitoring: Integrasi status langsung dengan gerbang pembayaran (misal: Stripe, Midtrans, atau Xendit).

Invoice & Billing History: Melihat riwayat pembayaran semua pengguna, menerbitkan ulang invoice, atau melakukan Refund secara manual jika ada komplain.

5.3. Manajemen Pengguna (User Management)

Manual Override: Super Admin dapat secara manual meng-upgrade, men-downgrade, atau memperpanjang masa aktif paket pengguna tertentu (berguna untuk support atau promosi).

User Moderation: Suspend atau Banned pengguna yang melanggar ToS (misalnya mendistribusikan link malware/phishing).

Impersonation: Kemampuan untuk "Login sebagai" pengguna tertentu untuk tujuan troubleshooting atau customer support.

5.4. Manajemen Sistem & Infrastruktur

Domain Verification: Memantau status SSL dan verifikasi CNAME dari Custom Domain pengguna.

Global Blacklist: Menambahkan kata-kata terlarang untuk custom alias (misal: nama brand besar, kata kotor) dan memblokir URL tujuan yang berbahaya.

API Rate Limiting: Memantau penggunaan API oleh pengguna Enterprise dan menyesuaikan limit batas request.

6. Kebutuhan Non-Fungsional (Non-Functional Requirements)

Performa: Redirect URL harus terjadi di bawah 200ms (low latency). Membutuhkan caching layer (misal: Redis).

Ketersediaan (Availability): Uptime 99.9% sangat krusial agar link pengguna tidak mati.

Keamanan: - Enkripsi password untuk Protected Links.

Perlindungan terhadap serangan DDoS (sangat rentan pada layanan URL shortener).

Sanitasi input untuk mencegah XSS dan SQL Injection.

7. Teknologi yang Direkomendasikan (Tech Stack)

Meskipun prototipe saat ini menggunakan HTML/Tailwind/Vanilla JS, arsitektur penuh direkomendasikan sebagai berikut:

Frontend: React.js / Next.js, TailwindCSS (Sesuai desain prototipe).

Backend: Node.js (Express/NestJS) atau Go (untuk performa redirect yang sangat cepat).

Database: PostgreSQL (Relational data & users) + Redis (Caching untuk fast-redirect link).

Infrastruktur: AWS / Google Cloud, Docker, Cloudflare (untuk proteksi DDoS dan SSL Custom Domain).

8. Fase Rilis (Milestones)

Fase 1 (MVP): URL Shortening dasar, Dashboard pengguna, Analitik klik dasar, Login/Register.

Fase 2: QR Code, Custom Alias, Password & Expiration.

Fase 3: Integrasi Payment Gateway, Subscription System, Super Admin Panel.

Fase 4: Custom Domain Routing, Advanced Analytics, API Publik.