import React, { useState } from "react";

function DocumentUploader() {
  const [file, setFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
    setMessage("");
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!file) return;

    const token = localStorage.getItem("token");
    if (!token) {
      setMessage("ERROR: Anda belum login. Silakan login ulang.");
      return;
    }

    setLoading(true);
    setMessage("Mengunggah dan memproses OCR...");

    const formData = new FormData();

    formData.append("file", file);

    try {
      const response = await fetch(
        "http://127.0.0.1:8000/api/v1/documents/upload",
        {
          method: "POST",
          headers: {
            Authorization: `Bearer ${token}`,
            // Accept: "application/json",
          },
          body: formData,
        }
      );

      const text = await response.text();

    //   console.log("RAW Laravel Response:", text);

      const data = JSON.parse(text);

      if (response.ok) {
        const extractedInfo =
          data?.ocr_data?.ocr_data?.extracted_nip || "Data NIP tidak ditemukan";

        setMessage(`SUCCESS: ${data.message} (NIP: ${extractedInfo})`);
      } else {
        setMessage(`ERROR: ${data.message || "Terjadi kesalahan pada server"}`);
      }
    } catch (error) {
      setMessage(`Gagal terhubung ke Laravel API: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md p-6 bg-white shadow-lg rounded-lg">
      <h2 className="text-xl font-semibold mb-4 text-gray-800">
        Upload Dokumen & OCR Test
      </h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="file"
          onChange={handleFileChange}
          className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
          accept=".pdf,.png,.jpeg,.jpg"
          required
        />
        <button
          type="submit"
          disabled={loading || !file}
          className={`w-full py-2 px-4 rounded-md font-medium text-white ${
            loading ? "bg-gray-400" : "bg-blue-600 hover:bg-blue-700"
          }`}
        >
          {loading ? "Processing..." : "Upload & Verify"}
        </button>
      </form>
      {message && (
        <p
          className={`mt-4 p-3 rounded-md text-sm ${
            message.startsWith("ERROR")
              ? "bg-red-100 text-red-700"
              : "bg-green-100 text-green-700"
          }`}
        >
          {message}
        </p>
      )}
    </div>
  );
}

export default DocumentUploader;
