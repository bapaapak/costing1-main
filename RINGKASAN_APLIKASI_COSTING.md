# Ringkasan Aplikasi Costing System

## 1. Summary Aplikasi
Aplikasi ini adalah sistem web internal untuk mengelola proses costing manufaktur end-to-end, dari penerimaan dokumen project sampai output laporan COGM dan database referensi biaya.

Sistem mengintegrasikan:
- input dan update data costing per assy/model,
- perhitungan biaya material, process, overhead, dan COGM,
- pengelolaan master data (part, wire, customer, product, plant, cycle time, rate & kurs),
- tracking dokumen project dan status revisi,
- monitoring part tanpa harga (unpriced parts),
- audit trail dan kontrol akses berbasis permission.

## 2. Tema Utama Aplikasi
Tema aplikasi ini adalah:

**"Digitalisasi dan standarisasi proses costing manufaktur berbasis data terpusat, terukur, dan dapat diaudit."**

Tema turunannya:
- Akurasi biaya: memastikan komponen biaya dihitung konsisten.
- Kecepatan proses: mempersingkat alur dari dokumen masuk ke costing siap review.
- Traceability: setiap perubahan data penting dapat ditelusuri.
- Governance: akses pengguna dibatasi sesuai peran dan kewenangan.

## 3. Definisi Masalah Yang Mendorong Aplikasi Ini Dibuat
Sebelum ada sistem terpusat seperti ini, proses costing manufaktur umumnya menghadapi masalah berikut:

1. Data tersebar di banyak file/manual sheet
- Data part, kurs, cycle time, dan komponen biaya berada di sumber berbeda.
- Risiko duplikasi, versi data tidak sinkron, dan salah referensi.

2. Proses costing lambat dan rawan human error
- Input angka manual berulang menyebabkan typo dan inkonsistensi format.
- Revisi project sulit ditangani ketika dokumen berubah.

3. Kurang visibilitas status project dan dokumen
- Sulit memonitor dokumen mana yang sudah siap costing, pending pricing, atau sudah submitted.
- Risiko bottleneck antar fungsi (engineering, costing, marketing).

4. Sulit mengontrol part yang belum punya harga
- Komponen tanpa harga dapat lolos ke kalkulasi akhir.
- Mengganggu validitas COGM dan keputusan bisnis.

5. Sulit audit perubahan data
- Tanpa jejak perubahan yang jelas, evaluasi penyebab selisih costing menjadi sulit.

## 4. Analisis Masalah
### 4.1 Analisis Proses
Alur bisnis costing terdiri dari banyak dependency lintas domain: dokumen project, part/material, kurs, cycle time, dan validasi biaya.
Jika salah satu dependency tidak valid atau belum lengkap, output COGM bisa menyesatkan.

### 4.2 Analisis Data
Titik kritis berada pada:
- kualitas data master (part, wire, kurs, customer, product),
- konsistensi format numerik (desimal, ribuan, currency),
- integritas relasi antar tabel (costing, revision, material breakdown, unpriced parts).

### 4.3 Analisis Risiko Operasional
Risiko utama yang ingin ditekan aplikasi ini:
- under/over-estimation biaya karena data tidak valid,
- keterlambatan release quotation/keputusan pricing,
- konflik antar versi revisi dokumen,
- kesulitan investigasi saat terjadi deviasi angka.

## 5. Challenge Utama Aplikasi dan Solusi Yang Diterapkan
### Challenge 1: Sinkronisasi banyak master data
**Masalah:** sumber data biaya tersebar dan berubah cepat.

**Solusi di aplikasi:**
- modul Database terpusat (Part, Wire, Costing, Material Cost, Customer, Product, Plant, Cycle Time, Rate & Kurs),
- CRUD terstruktur dan relasi model yang jelas,
- filtering dan pencarian untuk kontrol kualitas data.

### Challenge 2: Menjaga akurasi COGM saat revisi dokumen
**Masalah:** revisi project dapat mengubah baseline costing.

**Solusi di aplikasi:**
- modul Project/Tracking Documents dengan status revisi,
- relasi ke data costing per revision,
- form costing yang menampilkan komponen biaya secara terstruktur.

### Challenge 3: Part tanpa harga (unpriced parts)
**Masalah:** part tanpa harga menyebabkan hasil costing tidak siap dipakai keputusan.

**Solusi di aplikasi:**
- modul Unpriced Parts untuk identifikasi dan tindak lanjut,
- mekanisme update dan resolusi harga,
- integrasi ke alur costing agar issue pricing tidak tersembunyi.

### Challenge 4: Konsistensi format numerik dan currency
**Masalah:** beda format angka dapat menimbulkan salah baca/salah simpan.

**Solusi di aplikasi:**
- normalisasi parsing input numerik,
- pemformatan angka terstandar (format Indonesia) di UI,
- perhitungan ulang komponen COGM secara sistematis di form dan laporan.

### Challenge 5: Auditabilitas dan kontrol akses
**Masalah:** perubahan data kritis perlu dapat ditelusuri dan dibatasi.

**Solusi di aplikasi:**
- Audit Trail untuk jejak aktivitas,
- role/permission middleware untuk pembatasan akses fitur,
- pemisahan area operasional, data master, laporan, dan administrasi.

## 6. Nilai Bisnis Yang Dihasilkan
Dengan solusi di atas, aplikasi memberi dampak:
- proses costing lebih cepat dan konsisten,
- kualitas keputusan harga/quotation meningkat,
- risiko error manual berkurang,
- kolaborasi lintas fungsi lebih tertata,
- histori perubahan lebih mudah diaudit.

## 7. Kesimpulan
Aplikasi Costing System ini dibangun untuk menyelesaikan masalah inti manufaktur pada area biaya: data yang tersebar, perhitungan yang rawan error, dan alur revisi yang kompleks.

Secara tema, aplikasi berfokus pada **operational excellence untuk costing manufaktur** melalui digitalisasi proses, standardisasi data, dan governance yang kuat.
