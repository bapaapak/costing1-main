# Ringkasan Eksekutif Aplikasi Costing System

## Executive Summary
Costing System adalah platform digital internal untuk mengelola proses costing manufaktur secara terintegrasi, mulai dari pengelolaan dokumen project, input komponen biaya, validasi data pendukung, hingga pelaporan COGM.

Aplikasi ini dibangun untuk meningkatkan akurasi biaya, mempercepat siklus analisis costing, dan memperkuat kontrol proses melalui data terpusat, audit trail, serta kontrol akses berbasis peran.

## Tema Strategis Aplikasi
**Digital Operational Excellence untuk Proses Costing Manufaktur**

Tema ini diwujudkan melalui tiga pilar utama:
1. Integrasi data biaya dalam satu sistem terpusat.
2. Standardisasi perhitungan dan format angka biaya.
3. Tata kelola proses yang dapat ditelusuri dan diaudit.

## Definisi Masalah Bisnis
Sebelum sistem ini berjalan, organisasi menghadapi persoalan utama berikut:

1. Data costing tersebar dan tidak sinkron.
- Data part, kurs, cycle time, dan komponen biaya berada pada sumber yang berbeda.
- Dampak: tinggi risiko inkonsistensi, duplikasi, dan salah referensi.

2. Proses costing lambat dan rentan human error.
- Input angka manual dan proses rekap berulang meningkatkan kesalahan.
- Dampak: keterlambatan keputusan pricing dan rework.

3. Kontrol revisi project belum optimal.
- Perubahan dokumen project/revisi sulit dipantau secara end-to-end.
- Dampak: potensi mismatch antara versi dokumen dan hasil costing.

4. Komponen tanpa harga mengganggu validitas hasil.
- Part unpriced dapat terbawa ke kalkulasi COGM.
- Dampak: hasil costing tidak siap dijadikan dasar keputusan bisnis.

5. Jejak perubahan belum kuat.
- Sulit menelusuri siapa mengubah apa, kapan, dan mengapa.
- Dampak: investigasi deviasi angka memakan waktu tinggi.

## Analisis Manajerial
### Akar Masalah
- Ketergantungan pada proses manual lintas fungsi.
- Tidak adanya single source of truth untuk master data biaya.
- Belum ada mekanisme kontrol status yang disiplin pada alur dokumen dan costing.

### Risiko Utama Jika Tidak Ditangani
1. Kesalahan estimasi biaya (under/over cost).
2. Turunnya kecepatan respon terhadap kebutuhan quotation.
3. Kualitas keputusan bisnis menurun karena data tidak reliabel.
4. Tingginya biaya koordinasi dan koreksi antar tim.

## Challenge dan Solusi di Aplikasi
### 1) Challenge: Integrasi Data Master
**Solusi:** Modul database terpusat untuk part, wire, customer, product, plant, cycle time, rate dan kurs.

### 2) Challenge: Konsistensi Perhitungan COGM
**Solusi:** Form costing terstruktur dengan komponen biaya yang jelas (material, process, overhead, administrasi) dan agregasi COGM otomatis.

### 3) Challenge: Kontrol Revisi Dokumen Project
**Solusi:** Tracking dokumen dan status revisi yang terhubung langsung ke data costing.

### 4) Challenge: Part Belum Berharga (Unpriced Parts)
**Solusi:** Modul monitoring dan penyelesaian unpriced parts agar pricing gap dapat ditutup sebelum finalisasi.

### 5) Challenge: Akuntabilitas Perubahan Data
**Solusi:** Audit trail dan kontrol akses berbasis role/permission untuk governance yang lebih kuat.

## Dampak Bisnis yang Diharapkan
1. Meningkatkan akurasi hasil costing.
2. Mempercepat waktu siklus dari dokumen masuk hingga costing siap review.
3. Mengurangi rework akibat kesalahan input dan mismatch data.
4. Meningkatkan transparansi proses lintas fungsi.
5. Memperkuat kesiapan data untuk keputusan pricing dan evaluasi profitabilitas.

## KPI Rekomendasi untuk Monitoring
1. Turnaround time proses costing per project/revisi.
2. Persentase data costing dengan part unpriced.
3. Jumlah koreksi costing pasca-review.
4. Tingkat kepatuhan update master data (rate, part, cycle time).
5. Jumlah temuan audit trail yang kritikal per periode.

## Kesimpulan Eksekutif
Aplikasi Costing System bukan hanya alat input biaya, tetapi fondasi tata kelola costing manufaktur yang lebih cepat, lebih akurat, dan lebih terkontrol.

Dengan pendekatan data terpusat dan workflow terstandar, aplikasi ini secara langsung mendukung kualitas keputusan bisnis pada area costing, pricing, dan profitabilitas.