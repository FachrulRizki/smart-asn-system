<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    // Modul C: Endpoint untuk mengunggah dokumen dan memicu OCR
    public function uploadAndVerify(Request $request)
    {
        // 1. Validasi Input (Pastikan file ada dan tipenya benar)
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,pdf|max:10240', // 10MB
            // 'asn_id' => 'required|uuid', // FK ke profil ASN
        ]);

        $file = $request->file('file');

        // --- 2. PANGGIL FASTAPI UNTUK OCR ---

        try {
            // Kirim file (multipart) ke FastAPI
            $response = Http::timeout(300) // Timeout 5 menit untuk proses OCR yang mungkin lambat
                ->baseUrl(config('app.ai_service_url', 'http://127.0.0.1:5000'))
                ->withHeaders(['Accept' => 'application/json'])
                ->asMultipart()
                ->post('/api/v1/ocr/process', [
                    'file' => [
                        'name' => 'file',
                        'contents' => file_get_contents($file->getRealPath()),
                        'filename' => $file->getClientOriginalName(),
                    ],
                ]);

            if ($response->successful()) {
                $ocrResult = $response->json();

                // --- 3. SIMPAN FILE DAN METADATA KE DB/MINIO ---

                // Simpan file ke MinIO (S3)
                $filePath = Storage::disk('s3')->putFile('documents', $file);

                // Simpan metadata (termasuk hasil OCR) ke tabel documents di Postgres
                DB::table('documents')->insert([
                    // doc_id (PK), asn_id (FK), doc_type, uploaded_by, uploaded_at, dll.
                    'file_path' => $filePath,
                    'ocr_text' => $ocrResult['raw_ocr_text'] ?? 'N/A',
                    'doc_type' => 'ijazah', // Contoh: bisa ditentukan dari user input
                    'uploaded_at' => now(),
                    // ... Kolom lain
                ]);

                return response()->json([
                    'message' => 'Dokumen berhasil diunggah dan OCR berhasil diproses.',
                    'ocr_data' => $ocrResult
                ], 201);
            }

            // Jika FastAPI gagal (error 500 dari AI Service)
            return response()->json([
                'message' => 'Gagal memproses OCR oleh AI Service.',
                'ai_error' => $response->json()
            ], 500);
        } catch (\Exception $e) {
            // Jika koneksi ke FastAPI gagal (timeout, network error)
            return response()->json([
                'message' => 'Gagal terhubung ke AI Service.',
                'error' => $e->getMessage()
            ], 503);
        }
    }
}
