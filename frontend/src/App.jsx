import React from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import Login from "./Login";
import DocumentUploader from "./Uploader";

// Komponen sederhana untuk memproteksi rute
const ProtectedRoute = ({ children }) => {
  const token = localStorage.getItem("token");
  if (!token) {
    return <Navigate to="/" replace />;
  }
  return children;
};

function App() {
  return (
    <Router>
      <Routes>
        {/* Halaman Login (Default) */}
        <Route path="/" element={<Login />} />

        {/* Halaman Dashboard (Terproteksi) */}
        <Route
          path="/dashboard"
          element={
            <ProtectedRoute>
              <div className="min-h-screen bg-gray-50 p-10">
                <div className="flex justify-between items-center mb-8">
                  <h1 className="text-3xl font-bold text-gray-800">
                    Dashboard ASN
                  </h1>
                  <button
                    onClick={() => {
                      localStorage.clear();
                      window.location.href = "/";
                    }}
                    className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                  >
                    Logout
                  </button>
                </div>

                {/* Tampilkan Uploader di sini */}
                <DocumentUploader />
              </div>
            </ProtectedRoute>
          }
        />
      </Routes>
    </Router>
  );
}

export default App;
