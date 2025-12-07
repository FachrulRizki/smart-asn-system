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

    setLoading(true);
    setMessage("Mengunggah dan memproses OCR...");

    const formData = new FormData();
    formData.append("file", file);
    // formData.append('asn_id', '123-456-789'); // Kirim ID ASN

    try {
      const response = await fetch(
        "http://127.0.0.1:5000/api/v1/ocr/process",
        {
          method: "POST",
          body: formData,
        }
      );

      const data = await response.json();

      if (response.ok) {
        setMessage(
          `SUCCESS: ${data.message}`
        );
        console.log("Full OCR Data:", data.ocr_data);
      } else {
        setMessage(
          `ERROR: ${data.message} - ${data.error || data.ai_error.message}`
        );
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
