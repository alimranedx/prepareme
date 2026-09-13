import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import Pagination from '../../components/common/Pagination';

const OcrUploadPage = () => {
  const navigate = useNavigate();

  const [file, setFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null);
  const [language, setLanguage] = useState('ben+eng');
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState(null);

  // Past OCR documents history
  const [pastDocs, setPastDocs] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loadingHistory, setLoadingHistory] = useState(true);

  const fetchHistory = async (page = 1) => {
    setLoadingHistory(true);
    try {
      const response = await apiClient.get(`/my/ocr-documents?page=${page}`);
      setPastDocs(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load OCR history', err);
    } finally {
      setLoadingHistory(false);
    }
  };

  useEffect(() => {
    fetchHistory(1);
  }, []);

  const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];
    if (selectedFile) {
      // Validate file size on client (10MB)
      if (selectedFile.size > 10 * 1024 * 1024) {
        setError('ছবির আকার সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে।');
        return;
      }
      setFile(selectedFile);
      setPreviewUrl(URL.createObjectURL(selectedFile));
      setError(null);
      setValidationErrors(null);
    }
  };

  const handleUploadSubmit = async (e) => {
    e.preventDefault();
    if (!file) {
      setError('অনুগ্রহ করে বইয়ের পাতার ছবি নির্বাচন করুন।');
      return;
    }

    setUploading(true);
    setError(null);
    setValidationErrors(null);

    const formData = new FormData();
    formData.append('image', file);
    formData.append('language', language);

    try {
      const response = await apiClient.post('/my/ocr-documents', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      const newDoc = response.data.data;
      navigate(`/my/ocr/${newDoc.id}`);
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'ছবি আপলোড করতে সমস্যা হয়েছে।');
      }
    } finally {
      setUploading(false);
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case 'completed':
        return <span className="badge bg-success bangla-text">সম্পন্ন</span>;
      case 'processing':
        return <span className="badge bg-warning text-dark bangla-text">প্রসেসিং হচ্ছে...</span>;
      case 'failed':
        return <span className="badge bg-danger bangla-text">ব্যর্থ হয়েছে</span>;
      default:
        return <span className="badge bg-secondary bangla-text">আপলোড সম্পন্ন</span>;
    }
  };

  return (
    <div className="container py-5">
      {/* Header */}
      <div className="mb-4">
        <span className="badge bg-primary px-3 py-2 rounded-pill bangla-text mb-2">
          OCR স্টুডিও
        </span>
        <h2 className="fw-bold text-dark bangla-text mb-0">বইয়ের ছবি থেকে টেক্সট কনভার্টার</h2>
        <p className="text-muted bangla-text small mb-0 mt-1">
          বইয়ের যেকোনো পাতার ছবি তুলে আপলোড করুন। স্বয়ংক্রিয়ভাবে টেক্সট এক্সট্রাক্ট করে আপনার নিজস্ব প্রশ্ন হিসেবে নোটবুকে যোগ করুন।
        </p>
      </div>

      <div className="row g-4">
        {/* Upload Form Column */}
        <div className="col-lg-6">
          <div className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
            <h4 className="fw-bold text-dark bangla-text mb-3">
              <i className="bi bi-cloud-arrow-up-fill text-primary me-2"></i> নতুন ছবি আপলোড করুন
            </h4>

            <AlertMessage type="danger" message={error} errors={validationErrors} onClose={() => setError(null)} />

            <form onSubmit={handleUploadSubmit}>
              {/* File Input */}
              <div className="mb-4">
                <label className="form-label bangla-text fw-semibold">বই বা ডকুমেন্টের ছবি নির্বাচন করুন</label>
                <input
                  type="file"
                  className="form-control form-control-lg rounded-3"
                  accept="image/jpeg,image/png,image/jpg,image/webp"
                  onChange={handleFileChange}
                  required
                />
                <div className="form-text small bangla-text">
                  সমর্থিত ফরম্যাট: JPEG, PNG, JPG, WebP (সর্বোচ্চ ১০MB)
                </div>
              </div>

              {/* Preview Box */}
              {previewUrl && (
                <div className="mb-4 text-center">
                  <div className="ocr-preview-box rounded-3 overflow-hidden">
                    <img
                      src={previewUrl}
                      alt="Uploaded preview"
                      className="img-fluid rounded"
                      style={{ maxHeight: '260px', objectFit: 'contain' }}
                    />
                  </div>
                  <p className="text-muted small bangla-text mt-1">{file?.name} ({(file?.size / 1024).toFixed(1)} KB)</p>
                </div>
              )}

              {/* Language Selection */}
              <div className="mb-4">
                <label className="form-label bangla-text fw-semibold">ভাষার ধরন (OCR Language)</label>
                <select
                  className="form-select form-select-lg rounded-3 bangla-text"
                  value={language}
                  onChange={(e) => setLanguage(e.target.value)}
                >
                  <option value="ben+eng">বাংলা ও ইংরেজি মিশ্রিত (Mixed Bangla & English)</option>
                  <option value="ben">শুধুমাত্র বাংলা (Bengali)</option>
                  <option value="eng">শুধুমাত্র ইংরেজি (English)</option>
                </select>
                <div className="form-text small bangla-text">
                  সাধারণত বিসিএস ও চাকরির বইগুলোতে বাংলা ও ইংরেজি মিশ্রিত থাকে, তাই মিশ্রিত অপশনটি সবচেয়ে ভালো কাজ করে।
                </div>
              </div>

              <button
                type="submit"
                className="btn btn-primary btn-lg w-100 rounded-3 bangla-text fw-bold py-3"
                disabled={uploading || !file}
              >
                {uploading ? (
                  <span>
                    <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                    ছবি আপলোড ও OCR প্রসেসিং হচ্ছে...
                  </span>
                ) : (
                  <span>
                    <i className="bi bi-file-earmark-text me-2"></i> টেক্সট রূপান্তর শুরু করুন
                  </span>
                )}
              </button>
            </form>
          </div>
        </div>

        {/* Guidelines and Tips */}
        <div className="col-lg-6">
          <div className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
            <h5 className="fw-bold text-dark bangla-text mb-3">
              <i className="bi bi-lightbulb-fill text-warning me-2"></i> ভালো ফলাফলের জন্য কিছু টিপস
            </h5>

            <div className="list-group list-group-flush bangla-text small text-secondary">
              <div className="list-group-item px-0 py-3 border-0 d-flex align-items-start gap-2">
                <span className="text-success fs-5"><i className="bi bi-check-circle"></i></span>
                <div>
                  <strong>উজ্জ্বল আলোতে ছবি তুলুন:</strong> ছায়া বা অতিরিক্ত অন্ধকার যেন পাতার ওপর না পড়ে।
                </div>
              </div>

              <div className="list-group-item px-0 py-3 border-0 d-flex align-items-start gap-2">
                <span className="text-success fs-5"><i className="bi bi-check-circle"></i></span>
                <div>
                  <strong>পাতা সমতল রাখুন:</strong> বইয়ের পৃষ্ঠা যতটুকু সম্ভব সোজা রাখুন যাতে লাইনগুলো বাঁকা না হয়।
                </div>
              </div>

              <div className="list-group-item px-0 py-3 border-0 d-flex align-items-start gap-2">
                <span className="text-success fs-5"><i className="bi bi-check-circle"></i></span>
                <div>
                  <strong>স্পষ্ট ফোকাস নিশ্চিত করুন:</strong> ক্যামেরার ফোকাস টেক্সটের ওপর ঠিক রেখে ছবি তুলুন যাতে কোনো লেখা ঘোলা না থাকে।
                </div>
              </div>

              <div className="list-group-item px-0 py-3 border-0 d-flex align-items-start gap-2">
                <span className="text-primary fs-5"><i className="bi bi-shield-check"></i></span>
                <div>
                  <strong>সম্পূর্ণ ব্যক্তিগত ও গোপনীয়:</strong> আপনার আপলোড করা ছবি বা ব্যক্তিগত নোট কোনো সাধারণ ব্যবহারকারী বা পাবলিক পেজে দেখতে পারবে না।
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Past OCR Scans History */}
      <div className="card border-0 shadow-sm rounded-4 p-4 bg-white mt-5">
        <h4 className="fw-bold text-dark bangla-text mb-3">
          <i className="bi bi-clock-history text-secondary me-2"></i> সাম্প্রতিক OCR স্ক্যানসমূহ
        </h4>

        {loadingHistory ? (
          <LoadingSpinner text="স্ক্যান ইতিহাস লোড হচ্ছে..." />
        ) : pastDocs.length === 0 ? (
          <p className="text-muted small bangla-text my-3">
            আপনি এখনও কোনো ডকুমেন্টে OCR চালাননি।
          </p>
        ) : (
          <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 bangla-text">
              <thead className="table-light">
                <tr>
                  <th>ফাইলের নাম</th>
                  <th>ভাষা</th>
                  <th>আকার</th>
                  <th>স্ট্যাটাস</th>
                  <th>আপলোডের সময়</th>
                  <th className="text-end">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                {pastDocs.map((doc) => (
                  <tr key={doc.id}>
                    <td className="fw-semibold text-dark">
                      <i className="bi bi-file-earmark-image me-2 text-primary"></i>
                      {doc.original_file_name}
                    </td>
                    <td>
                      <span className="badge bg-light text-secondary border">
                        {doc.language === 'ben+eng' ? 'বাংলা+ইংরেজি' : doc.language === 'ben' ? 'বাংলা' : 'ইংরেজি'}
                      </span>
                    </td>
                    <td className="small text-muted">{(doc.file_size / 1024).toFixed(1)} KB</td>
                    <td>{getStatusBadge(doc.status)}</td>
                    <td className="small text-muted">
                      {new Date(doc.created_at).toLocaleDateString('bn-BD')}
                    </td>
                    <td className="text-end">
                      <Link
                        to={`/my/ocr/${doc.id}`}
                        className="btn btn-sm btn-outline-primary rounded-pill px-3"
                      >
                        ফলাফল দেখুন <i className="bi bi-arrow-right ms-1"></i>
                      </Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <Pagination meta={meta} onPageChange={(p) => fetchHistory(p)} />
      </div>
    </div>
  );
};

export default OcrUploadPage;
