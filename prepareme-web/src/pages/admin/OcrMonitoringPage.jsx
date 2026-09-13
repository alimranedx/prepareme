import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import Pagination from '../../components/common/Pagination';

const OcrMonitoringPage = () => {
  const [documents, setDocuments] = useState([]);
  const [paginationMeta, setPaginationMeta] = useState(null);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [alert, setAlert] = useState(null);

  // Filters
  const [selectedStatus, setSelectedStatus] = useState('');
  const [selectedLanguage, setSelectedLanguage] = useState('');

  // Inspection Modal
  const [selectedDoc, setSelectedDoc] = useState(null);
  const [inspectLoading, setInspectLoading] = useState(false);
  const [showModal, setShowModal] = useState(false);

  const fetchDocuments = async (targetPage = page) => {
    setLoading(true);
    try {
      const params = { page: targetPage };
      if (selectedStatus) params.status = selectedStatus;
      if (selectedLanguage) params.language = selectedLanguage;

      const res = await apiClient.get('/admin/ocr-documents', { params });
      setDocuments(res.data.data || []);
      setPaginationMeta(res.data.meta || null);
    } catch (err) {
      console.error('Failed to load OCR documents', err);
      setAlert({ type: 'danger', message: 'OCR ডকুমেন্ট তালিকা লোড করতে ব্যর্থ হয়েছে।' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDocuments(1);
    setPage(1);
  }, [selectedStatus, selectedLanguage]);

  const handlePageChange = (newPage) => {
    setPage(newPage);
    fetchDocuments(newPage);
  };

  const handleInspect = async (docId) => {
    setInspectLoading(true);
    setShowModal(true);
    try {
      const res = await apiClient.get(`/admin/ocr-documents/${docId}`);
      setSelectedDoc(res.data);
    } catch (err) {
      console.error('Failed to fetch doc details', err);
      setAlert({ type: 'danger', message: 'ডকুমেন্টের বিস্তারিত তথ্য পাওয়া যায়নি।' });
      setShowModal(false);
    } finally {
      setInspectLoading(false);
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case 'completed':
        return (
          <span className="badge bg-success-subtle text-success border border-success-subtle">
            <i className="bi bi-check-circle me-1"></i>সম্পন্ন (Completed)
          </span>
        );
      case 'processing':
        return (
          <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
            <span className="spinner-border spinner-border-sm me-1" style={{ width: '0.75rem', height: '0.75rem' }}></span>
            প্রক্রিয়াধীন
          </span>
        );
      case 'pending':
        return (
          <span className="badge bg-info-subtle text-info-emphasis border border-info-subtle">
            <i className="bi bi-hourglass me-1"></i>অপেক্ষমাণ (Pending)
          </span>
        );
      case 'failed':
        return (
          <span className="badge bg-danger-subtle text-danger border border-danger-subtle">
            <i className="bi bi-exclamation-triangle me-1"></i>ব্যর্থ (Failed)
          </span>
        );
      default:
        return <span className="badge bg-light text-dark">{status}</span>;
    }
  };

  const formatBytes = (bytes) => {
    if (!bytes || bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
  };

  return (
    <div>
      <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
          <h1 className="h3 fw-bold text-slate-800 mb-1">OCR অডিট ও মনিটরিং</h1>
          <p className="text-muted mb-0">
            ব্যবহারকারীদের আপলোডকৃত পাঠ্যবই পাতার OCR রূপান্তর পাইপলাইন, কনফিডেন্স স্কোর ও ইঞ্জিন লগ পর্যবেক্ষণ করুন।
          </p>
        </div>
        <button className="btn btn-outline-primary shadow-sm" onClick={() => fetchDocuments(page)}>
          <i className="bi bi-arrow-clockwise me-1"></i> রিফ্রেশ করুন
        </button>
      </div>

      {alert && <AlertMessage type={alert.type} message={alert.message} onClose={() => setAlert(null)} />}

      {/* Filter Bar */}
      <div className="card shadow-sm border-0 mb-4">
        <div className="card-body p-3">
          <div className="row g-2 align-items-center">
            <div className="col-12 col-md-4">
              <select
                className="form-select"
                value={selectedStatus}
                onChange={(e) => setSelectedStatus(e.target.value)}
              >
                <option value="">সকল স্ট্যাটাস</option>
                <option value="completed">সম্পন্ন (Completed)</option>
                <option value="processing">প্রক্রিয়াধীন (Processing)</option>
                <option value="pending">অপেক্ষমাণ (Pending)</option>
                <option value="failed">ব্যর্থ (Failed)</option>
              </select>
            </div>

            <div className="col-12 col-md-4">
              <select
                className="form-select"
                value={selectedLanguage}
                onChange={(e) => setSelectedLanguage(e.target.value)}
              >
                <option value="">সকল ভাষা (Language)</option>
                <option value="ben">বাংলা (Bengali)</option>
                <option value="eng">ইংরেজি (English)</option>
                <option value="ben+eng">উভয় (Bangla + English)</option>
              </select>
            </div>

            <div className="col-12 col-md-4 text-md-end">
              <button
                className="btn btn-outline-secondary w-100"
                onClick={() => {
                  setSelectedStatus('');
                  setSelectedLanguage('');
                }}
              >
                <i className="bi bi-arrow-counterclockwise me-1"></i> ফিল্টার রিসেট
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Documents Table */}
      <div className="card shadow-sm border-0">
        <div className="card-body p-0">
          {loading ? (
            <div className="p-5 text-center">
              <LoadingSpinner message="OCR অডিট লগ লোড হচ্ছে..." />
            </div>
          ) : documents.length === 0 ? (
            <div className="p-5 text-center text-muted">
              <i className="bi bi-file-earmark-text display-4 text-muted opacity-50 mb-3 d-block"></i>
              <h5>কোন OCR রেকর্ড পাওয়া যায়নি</h5>
              <p className="mb-0">ব্যবহারকারী যখন স্টাডি নোটের জন্য বইয়ের পাতা ডিজিটাইজ করবেন, তখন তা এখানে অডিট হবে।</p>
            </div>
          ) : (
            <div className="table-responsive">
              <table className="table table-hover align-middle mb-0">
                <thead className="table-light">
                  <tr>
                    <th style={{ width: '60px' }} className="text-center">#</th>
                    <th>ফাইল ও ব্যবহারকারী</th>
                    <th style={{ width: '120px' }}>ভাষা</th>
                    <th style={{ width: '130px' }}>ইঞ্জিন / স্কোর</th>
                    <th style={{ width: '140px' }}>স্ট্যাটাস</th>
                    <th style={{ width: '160px' }}>আপলোডের তারিখ</th>
                    <th style={{ width: '100px' }} className="text-end">অ্যাকশন</th>
                  </tr>
                </thead>
                <tbody>
                  {documents.map((doc, idx) => (
                    <tr key={doc.id}>
                      <td className="text-center text-muted small">
                        {paginationMeta ? (paginationMeta.current_page - 1) * 20 + idx + 1 : idx + 1}
                      </td>
                      <td>
                        <div className="fw-semibold text-slate-800 mb-1">
                          <i className="bi bi-image me-1 text-primary"></i>
                          {doc.original_filename || 'image.jpg'}
                          <span className="badge bg-light text-muted ms-2 border">
                            {formatBytes(doc.file_size)}
                          </span>
                        </div>
                        <div className="small text-muted">
                          ব্যবহারকারী:{' '}
                          <span className="text-dark fw-medium">
                            {doc.user ? `${doc.user.name} (${doc.user.email})` : `User #${doc.user_id}`}
                          </span>
                        </div>
                      </td>
                      <td>
                        <span className="badge bg-light text-dark border text-uppercase">
                          {doc.language || 'ben+eng'}
                        </span>
                      </td>
                      <td>
                        {doc.latest_result ? (
                          <div>
                            <span className="badge bg-primary-subtle text-primary border border-primary-subtle">
                              {doc.latest_result.engine || 'default'}
                            </span>
                            {doc.latest_result.confidence && (
                              <div className="small text-muted mt-1">
                                স্কোর: {Math.round(doc.latest_result.confidence)}%
                              </div>
                            )}
                          </div>
                        ) : (
                          <span className="text-muted small">—</span>
                        )}
                      </td>
                      <td>{getStatusBadge(doc.status)}</td>
                      <td className="small text-muted">
                        {new Date(doc.created_at).toLocaleString('bn-BD', {
                          year: 'numeric',
                          month: 'short',
                          day: 'numeric',
                          hour: '2-digit',
                          minute: '2-digit',
                        })}
                      </td>
                      <td className="text-end">
                        <button
                          className="btn btn-sm btn-outline-primary"
                          onClick={() => handleInspect(doc.id)}
                          title="বিস্তারিত তথ্য দেখুন"
                        >
                          <i className="bi bi-eye me-1"></i> বিবরণ
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>

        {paginationMeta && paginationMeta.last_page > 1 && (
          <div className="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
            <span className="small text-muted">
              মোট {paginationMeta.total} টি ডকুমেন্টের মধ্যে {paginationMeta.from}-{paginationMeta.to} দেখানো হচ্ছে
            </span>
            <Pagination
              currentPage={paginationMeta.current_page}
              lastPage={paginationMeta.last_page}
              onPageChange={handlePageChange}
            />
          </div>
        )}
      </div>

      {/* Inspect Modal */}
      {showModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }} tabIndex="-1">
          <div className="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title fw-bold">
                  <i className="bi bi-search me-2"></i>
                  OCR ডকুমেন্ট বিস্তারিত অডিট
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setShowModal(false)}
                ></button>
              </div>

              <div className="modal-body">
                {inspectLoading ? (
                  <div className="py-5 text-center">
                    <LoadingSpinner message="ডকুমেন্টের মেটাডাটা ও এক্সট্রাকশন লোড হচ্ছে..." />
                  </div>
                ) : selectedDoc ? (
                  <div>
                    {/* User & Document Meta Header */}
                    <div className="card bg-light border-0 mb-3">
                      <div className="card-body p-3">
                        <div className="row g-2 small">
                          <div className="col-md-6">
                            <strong>ডকুমেন্ট আইডি:</strong> #{selectedDoc.data?.id}
                          </div>
                          <div className="col-md-6">
                            <strong>ফাইলের নাম:</strong> {selectedDoc.data?.original_filename}
                          </div>
                          <div className="col-md-6">
                            <strong>ব্যবহারকারী:</strong> {selectedDoc.user?.name} ({selectedDoc.user?.email})
                          </div>
                          <div className="col-md-6">
                            <strong>ভাষা প্যারামিটার:</strong> {selectedDoc.data?.language}
                          </div>
                          <div className="col-md-6">
                            <strong>স্ট্যাটাস:</strong> {selectedDoc.data?.status}
                          </div>
                          <div className="col-md-6">
                            <strong>সাইজ:</strong> {formatBytes(selectedDoc.data?.file_size)}
                          </div>
                        </div>
                      </div>
                    </div>

                    {/* Error message if failed */}
                    {selectedDoc.data?.error_message && (
                      <div className="alert alert-danger mb-3">
                        <h6 className="alert-heading fw-bold mb-1">
                          <i className="bi bi-exclamation-triangle-fill me-1"></i> রূপান্তর ব্যর্থতার কারণ:
                        </h6>
                        <pre className="mb-0 small text-danger" style={{ whiteSpace: 'pre-wrap' }}>
                          {selectedDoc.data.error_message}
                        </pre>
                      </div>
                    )}

                    {/* Results / Extracted Text */}
                    {selectedDoc.data?.results && selectedDoc.data.results.length > 0 ? (
                      <div>
                        <h6 className="fw-bold text-slate-800 mb-2">
                          <i className="bi bi-cpu me-1 text-primary"></i> এক্সট্রাক্টেড টেক্সট ফলাফল ({selectedDoc.data.results.length} টি রান):
                        </h6>
                        {selectedDoc.data.results.map((res, i) => (
                          <div key={res.id || i} className="border rounded p-3 mb-3 bg-white">
                            <div className="d-flex justify-content-between align-items-center mb-2 small text-muted border-bottom pb-2">
                              <span>
                                <strong>ইঞ্জিন:</strong> {res.engine || 'N/A'} | <strong>কনফিডেন্স:</strong>{' '}
                                {res.confidence ? `${Math.round(res.confidence)}%` : 'N/A'}
                              </span>
                              <span>
                                <strong>প্রসেসিং টাইম:</strong>{' '}
                                {res.processing_time_ms ? `${res.processing_time_ms} ms` : 'N/A'}
                              </span>
                            </div>
                            <div
                              className="bg-light p-3 rounded font-monospace small bng-font"
                              style={{ maxHeight: '250px', overflowY: 'auto', whiteSpace: 'pre-wrap' }}
                            >
                              {res.raw_text || 'কোন টেক্সট পাওয়া যায়নি।'}
                            </div>
                          </div>
                        ))}
                      </div>
                    ) : (
                      <div className="alert alert-secondary text-center small mb-0">
                        এখনো কোন সফল টেক্সট নিষ্কাশন পাওয়া যায়নি।
                      </div>
                    )}
                  </div>
                ) : null}
              </div>

              <div className="modal-footer bg-light">
                <button
                  type="button"
                  className="btn btn-secondary"
                  onClick={() => setShowModal(false)}
                >
                  বন্ধ করুন
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default OcrMonitoringPage;
