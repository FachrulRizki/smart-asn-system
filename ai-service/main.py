# ai-service/main.py
from fastapi.middleware.cors import CORSMiddleware
from fastapi import FastAPI, UploadFile, File, HTTPException
import pytesseract
from PIL import Image
import io
import os
from pdf2image import convert_from_bytes
from typing import Dict, Any
from dotenv import load_dotenv

# Load .env Laravel
load_dotenv(dotenv_path="../smart-asn-system/backend/.env")

FRONTEND_URL = os.getenv("FRONTEND_URL", "*")
CORS_MODE = os.getenv("AI_SERVICE_CORS_MODE", "dev")

print("=== CORS MODE:", CORS_MODE, "===")
print("=== FRONTEND URL:", FRONTEND_URL, "===")

# CORS RULE DEV
DEV_CORS = {
    "allow_origins": ["*"],
    "allow_credentials": True,
    "allow_methods": ["*"],
    "allow_headers": ["*"],
}

# CORS RULE PROD
PROD_CORS = {
    "allow_origins": [FRONTEND_URL],
    "allow_credentials": True,
    "allow_methods": ["GET", "POST"],
    "allow_headers": ["Content-Type", "Authorization"],
}

# ------------------------------------------------------------
# 1) BUAT SEBELUM MIDDLEWARE
# ------------------------------------------------------------
app = FastAPI(title="Smart ASN AI Service")

# Pilih config
CORS_CFG = DEV_CORS if CORS_MODE == "dev" else PROD_CORS

# ------------------------------------------------------------
# 2) BARU ADD MIDDLEWARE KE app
# ------------------------------------------------------------
app.add_middleware(
    CORSMiddleware,
    allow_origins=CORS_CFG["allow_origins"],
    allow_credentials=CORS_CFG["allow_credentials"],
    allow_methods=CORS_CFG["allow_methods"],
    allow_headers=CORS_CFG["allow_headers"],
)

# ===========================
# KONFIGURASI MACOS
# ===========================
pytesseract.tesseract_cmd = "/opt/homebrew/bin/tesseract"
os.environ["TESSDATA_PREFIX"] = "/opt/homebrew/Cellar/tesseract/5.5.1_1/share/tessdata/"
POPPLER_PATH = "/opt/homebrew/Cellar/poppler/25.12.0/bin"


@app.get("/")
def health_check():
    return {"status": "AI service is operational", "model_version": "v1.0"}


def run_ocr(image: Image.Image) -> str:
    try:
        return pytesseract.image_to_string(image, lang="ind")
    except Exception as e:
        raise Exception(f"OCR gagal: {str(e)}")


@app.post("/api/v1/ocr/process", response_model=Dict[str, Any])
async def process_ocr(file: UploadFile = File(...)):
    if not file.content_type.startswith(("image/", "application/pdf")):
        raise HTTPException(status_code=400, detail="File harus berupa gambar atau PDF")

    file_bytes = await file.read()

    try:
        if file.content_type == "application/pdf":
            images = convert_from_bytes(
                file_bytes,
                poppler_path=POPPLER_PATH,
                fmt="png"
            )
            if not images:
                raise ValueError("PDF kosong / tidak bisa diproses")
            ocr_text = run_ocr(images[0])
        else:
            image = Image.open(io.BytesIO(file_bytes))
            ocr_text = run_ocr(image)

        return {
            "filename": file.filename,
            "raw_ocr_text": ocr_text,
            "message": "Pemrosesan OCR berhasil",
            "status": "success",
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"OCR Processing Failed: {str(e)}")


@app.post("/api/v1/ai/jobmatch", response_model=Dict[str, Any])
def job_match(data: dict):
    score = 78.5
    return {
        "job_match_score": score,
        "explanation": (
            f"ASN {data.get('asn_id')} cocok karena skor {score} "
            f"di atas rata-rata jabatan {data.get('position_id')}."
        ),
    }
