# ai-service/main.py

from fastapi import FastAPI, UploadFile, File, HTTPException
import pytesseract
from PIL import Image
import io
import os
from pdf2image import convert_from_bytes
from typing import Dict, Any

app = FastAPI(title="Smart ASN AI Service")

# Cek apakah Tesseract terinstal (Wajib untuk OCR)
try:
    pytesseract.get_tesseract_version()
except pytesseract.TesseractNotFoundError:
    print("WARNING: Tesseract is not installed or not in PATH. OCR will fail.")

@app.get("/")
def health_check():
    return {"status": "AI service is operational", "model_version": "v1.0"}

# Fungsi inti untuk menjalankan Tesseract pada objek Image PIL
def run_ocr(image: Image.Image) -> str:
    """Melakukan proses Tesseract pada objek PIL Image."""
    return pytesseract.image_to_string(image, lang='ind')

# MODUL C: OCR DOCUMENT PARSING
@app.post("/api/v1/ocr/process", response_model=Dict[str, Any])
async def process_ocr(file: UploadFile = File(...)):
    """
    Menerima file gambar atau PDF dan mengembalikan hasil OCR
    """
    if not file.content_type.startswith(('image/', 'application/pdf')):
        raise HTTPException(status_code=400, detail="File harus berupa Gambar atau PDF")

    try:
        # 1. Membaca data file
        file_bytes = await file.read()
        
        ocr_text = ""

        if file.content_type == 'application/pdf':
            # --- LOGIKA PENANGANAN PDF ---
            try:
                # Mengkonversi PDF ke list gambar PIL
                # Halaman pertama dianggap cukup untuk keperluan tes ini
                images = convert_from_bytes(file_bytes, first_page=1, last_page=1)
                
                if not images:
                     raise ValueError("PDF kosong atau tidak dapat diakses.")
                
                # Proses OCR pada halaman pertama
                ocr_text = run_ocr(images[0])
                
            except Exception as pdf_error:
                 # Jika Poppler tidak terinstal atau PDF rusak
                 raise HTTPException(
                     status_code=500, 
                     detail=f"Konversi PDF/Poppler gagal. Pastikan Poppler terinstal. Error: {str(pdf_error)}"
                 )
            # --- AKHIR LOGIKA PDF ---
            
        elif file.content_type.startswith('image/'):
            # --- LOGIKA PENANGANAN GAMBAR ---
            # Mengubah data mentah menjadi objek gambar PIL
            image = Image.open(io.BytesIO(file_bytes))
            ocr_text = run_ocr(image)
            # --- AKHIR LOGIKA GAMBAR ---
        
        # 2. Simulasi ekstraksi data penting (misal NIP)
        # NIP harus dicari dari 'ocr_text' yang telah didapatkan
        nip_extracted = "198001012000121001" # Logic NLP/Regex aktual untuk mencari NIP di 'ocr_text'

        return {
            "filename": file.filename,
            "raw_ocr_text": ocr_text,
            "ocr_data": {"extracted_nip": nip_extracted},
            "message": "Pemrosesan OCR Berhasil",
            "status": "success"
        }
    
    except Exception as e:
        # Tangani error umum (misal memory/pytesseract)
        raise HTTPException(status_code=500, detail=f"OCR Processing failed: {str(e)}")

# MODUL G: AI JOB MATCH
@app.post("/api/v1/ai/jobmatch", response_model=Dict[str, Any])
def job_match(data: dict):
    # Logika Machine Learning / Model Kompatibilitas di sini
    asn_id = data.get('asn_id')
    position_id = data.get('position_id')
    
    # Placeholder Logic
    score = 78.5
    explanation = f"ASN {asn_id} sangat cocok karena memiliki skor kompetensi {score} di atas rata-rata jabatan {position_id}."
    
    return {"job_match_score": score, "explanation": explanation}