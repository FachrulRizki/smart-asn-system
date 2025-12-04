# MATA.AI — Talent Management & Merit System Platform

MATA.AI adalah platform inovasi manajemen ASN berbasis **AI** yang dirancang untuk mendukung implementasi **Sistem Merit ASN**, **Seleksi & Penempatan Jabatan Berbasis Kompetensi**, serta **Penilaian Kinerja Modern** yang akuntabel, cepat, dan objektif.

Platform ini cocok untuk BKD/BKPSDM, KemenPAN-RB, Instansi Pemerintah Pusat/Daerah, dan unit kerja yang ingin menerapkan transformasi digital dalam pengelolaan ASN.

---

## 🚀 Fitur Utama

### 1. **Manajemen ASN (Core HR)**

* Profil ASN, riwayat jabatan, unit kerja, dan kompetensi
* Struktur organisasi dinamis
* Talent Profile otomatis

### 2. **Digital Document Management + OCR AI**

* Upload dokumen (ijazah, sertifikat, SK, dll)
* OCR otomatis
* Verifikasi dokumen (AI-assisted)
* Deteksi anomali & integritas dokumen

### 3. **Smart SKP Builder (AI)**

* Generate SKP otomatis berdasarkan jabatan ASN
* Review & approval atasan
* Template indikator kinerja

### 4. **Penilaian Kinerja Modern**

* Rekap capaian kinerja ASN
* Prediksi kinerja (AI forecasting)
* Feedback 360° (opsional)
* Trend analitik

### 5. **AI Job-Matching & Seleksi ASN**

* Scoring kesesuaian ASN dengan jabatan
* Candidate explainability (XAI)
* Screening dokumen otomatis

### 6. **Succession Planning (Talent Pool AI)**

* Rekomendasi suksesor jabatan
* Gap analysis & rekomendasi pengembangan

### 7. **Training & Development**

* Integrasi diklat/LMS
* Rekomendasi pelatihan berbasis kompetensi

### 8. **Dashboard & Analytics**

* Heatmap MATA
* Laporan merit system
* Statistik kinerja & prediksi

---

## 🧱 Arsitektur Sistem

### **Frontend**

* Next.js (React)
* TailwindCSS
* JWT Auth / SSO

### **Backend**

* Laravel / NestJS (REST)
* Websocket (Realtime Notification)
* Redis Queue (OCR + AI tasks)

### **AI Services (Microservices)**

* FastAPI (Python)
* OCR Engine: PaddleOCR / Tesseract
* NLP: IndoBERT / LLM
* ML Model: Job-match, Performance prediction

### **Database & Storage**

* PostgreSQL
* MinIO / S3
* Redis (Cache)
* ElasticSearch / Meilisearch (opsional)

### **DevOps**

* Docker & Kubernetes
* GitHub Actions CI/CD
* Monitoring: Grafana + Prometheus

---

## 📦 Modul Aplikasi

Dokumen ini digabungkan dari:

* Modul Manajemen ASN
* Dokumen & OCR
* SKP Digital
* Kinerja ASN
* Talent Management & Succession
* AI Job Matching
* Dashboard & Analytics
* Audit & Notification

Detail tiap modul tersedia dalam dokumentasi internal.

---

## 🛠️ Instalasi (Development)

### **1. Clone repository**

```
git clone branchnya
```

### **2. Jalankan Docker**

```
docker-compose up -d
```

### **3. Migrasi database**

```
php artisan migrate
```

### **4. Jalankan frontend**

```
cd frontend
npm install
npm run dev
```

### **5. Jalankan AI service**

```
cd ai-services
uvicorn app.main:app --reload
```
---

## 🤝 Kontribusi

Pull request dan issue sangat terbuka.
Gunakan format conventional commits.

---

## 📄 Lisensi

MIT License / Government Internal License.

---

## 📞 Kontak

Untuk kerja sama dan implementasi:

* Email: fachrulrizki08@gmail.com
* WA/Telegram: 0822 8160 7797

---

**MATA.AI — Transformasi Digital Manajemen ASN berbasis AI**
