import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import ConfirmModal from '../../components/common/ConfirmModal';

const OcrViewerPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const [document, setDocument] = useState(null);
  const [result, setResult] = useState(null);
  const [editedText, setEditedText] = useState('');
  const [loading, setLoading] = useState(true);
  const [retrying, setRetrying] = useState(false);
  const [savingNote, setSavingNote] = useState(false);
  const [toast, setToast] = useState(null);

  // Conversion Modal State
  const [showConvertModal, setShowConvertModal] = useState(false);
  const [convertForm, setConvertForm] = useState({
    subject_id: '',
    topic_id: '',
    question: '',
    answer: '',
    explanation: '',
    source_title: '',
    source_page: '',
  });
  const [subjects, setSubjects] = useState([]);
  const [topics, setTopics] = useState([]);

  // Delete modal state
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [deleting, setDeleting] = useState(false);

  // Load document
  const fetchDoc = async () => {
    try {
      const response = await apiClient.get(`/my/ocr-documents/${id}`);
      const doc = response.data.data;
      setDocument(doc);
      if (doc.latest_result) {
        setResult(doc.latest_result);
        setEditedText(doc.latest_result.effective_text || doc.latest_result.raw_text || '');
        setConvertForm((prev) => ({
          ...prev,
          source_title: doc.original_file_name || '',
          answer: doc.latest_result.effective_text || '',
        }));
      }
      return doc;
    } catch (err) {
      console.error('Error fetching OCR doc', err);
      setToast({ type: 'danger', message: 'ডকুমেন্টটি লোড করা সম্ভব হয়নি।' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDoc();
  }, [id]);

  // Load subjects for conversion modal
  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {}
    };
    fetchSubjects();
  }, []);

  // Poll status while processing
  useEffect(() => {
    if (!document) return;
    if (document.status === 'processing' || document.status === 'uploaded') {
      const timer = setInterval(async () => {
        const updated = await fetchDoc();
        if (updated && (updated.status === 'completed' || updated.status === 'failed')) {
          clearInterval(timer);
        }
      }, 3000);
      return () => clearInterval(timer);
    }
  }, [document?.status]);

  const handleRetry = async () => {
    setRetrying(true);
    try {
      await apiClient.post(`/my/ocr-documents/${id}/retry`);
      setToast({ type: 'info', message: 'OCR পুনরায় প্রসেসিং শুরু হয়েছে।' });
      fetchDoc();
    } catch (err) {
      setToast({ type: 'danger', message: 'পুনরায় চেষ্টা করতে সমস্যা হয়েছে।' });
    } finally {
      setRetrying(false);
    }
  };

  const handleSubjectSelect = async (e) => {
    const subId = e.target.value;
    setConvertForm((prev) => ({ ...prev, subject_id: subId, topic_id: '' }));
    if (!subId) {
      setTopics([]);
      return;
    }
    try {
      const res = await apiClient.get(`/subjects/${subId}/topics`);
      setTopics(res.data.data || []);
    } catch (err) {}
  };

  const handleSaveAsQuestion = async (e) => {
    e.preventDefault();
    setSavingNote(true);
    try {
      await apiClient.post(`/my/ocr-documents/${id}/save-as-question`, {
        ...convertForm,
        corrected_text: editedText,
        subject_id: convertForm.subject_id || null,
        topic_id: convertForm.topic_id || null,
      });
      setShowConvertModal(false);
      setToast({ type: 'success', message: 'প্রশ্নটি সফলভাবে আপনার নিজস্ব নোটবুকে সংরক্ষিত হয়েছে!' });
      setTimeout(() => {
        navigate('/my/questions');
      }, 1500);
    } catch (err) {
      setToast({ type: 'danger', message: err.response?.data?.message || 'সংরক্ষণ ব্যর্থ হয়েছে।' });
    } finally {
      setSavingNote(false);
    }
  };

  const handleDeleteConfirm = async () => {
    setDeleting(true);
    try {
      await apiClient.delete(`/my/ocr-documents/${id}`);
      navigate('/my/ocr');
    } catch (err) {
      setToast({ type: 'danger', message: 'ডকুমেন্ট ডিলিট করতে সমস্যা হয়েছে।' });
    } finally {
      setDeleting(false);
    }
  };

  if (loading) return <LoadingSpinner fullPage text="OCR ডকুমেন্ট লোড হচ্ছে..." />;
  if (!document) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger bangla-text mx-auto" style={{ maxWidth: '500px' }}>
          ডকুমেন্টটি পাওয়া যায়নি।
        </div>
        <Link to="/my/ocr" className="btn btn-primary bangla-text rounded-pill">
          OCR স্টুডিওতে ফিরে যান
        </Link>
      </div>
    );
  }

  const isCompleted = document.status === 'completed';
  const isProcessing = document.status === 'processing' || document.status === 'uploaded';
  const isFailed = document.status === 'failed';

  return (
    <div className="container py-5">
      {/* Breadcrumb */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text">
          <li className="breadcrumb-item"><Link to="/dashboard" className="text-decoration-none">ড্যাশবোর্ড</Link></li>
          <li className="breadcrumb-item"><Link to="/my/ocr" className="text-decoration-none">OCR স্টুডিও</Link></li>
          <li className="breadcrumb-item active" aria-current="page">{document.original_file_name}</li>
        </ol>
      </nav>

      <AlertMessage type={toast?.type} message={toast?.message} onClose={() => setToast(null)} />

      {/* Status Banner */}
      <div className="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div className="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h4 className="fw-bold text-dark bangla-text mb-1 d-flex align-items-center gap-2">
              <i className="bi bi-file-earmark-text text-primary"></i>
              {document.original_file_name}
            </h4>
            <div className="text-muted small bangla-text">
              আপলোড: {new Date(document.created_at).toLocaleTimeString('bn-BD', { hour: '2-digit', minute: '2-digit' })} • আকার: {(document.file_size / 1024).toFixed(1)} KB • ভাষা:{' '}
              {document.language === 'ben+eng' ? 'বাংলা ও ইংরেজি' : document.language === 'ben' ? 'বাংলা' : 'ইংরেজি'}
            </div>
          </div>

          <div className="d-flex align-items-center gap-2">
            {isProcessing && (
              <span className="badge bg-warning text-dark bangla-text px-3 py-2 rounded-pill fs-6">
                <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                OCR টেক্সট এক্সট্রাকশন চলছে...
              </span>
            )}
            {isCompleted && (
              <span className="badge bg-success bangla-text px-3 py-2 rounded-pill fs-6">
                <i className="bi bi-check-circle-fill me-1"></i> রূপান্তর সম্পন্ন
              </span>
            )}
            {isFailed && (
              <span className="badge bg-danger bangla-text px-3 py-2 rounded-pill fs-6">
                <i className="bi bi-exclamation-triangle-fill me-1"></i> প্রসেসিং ব্যর্থ
              </span>
            )}

            <button
              className="btn btn-outline-danger btn-sm rounded-pill px-3"
              onClick={() => setShowDeleteModal(true)}
              title="ডকুমেন্টটি মুছে ফেলুন"
            >
              <i className="bi bi-trash me-1"></i> মুছুন
            </button>
          </div>
        </div>

        {isFailed && (
          <div className="alert alert-danger bangla-text mt-3 mb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
              <i className="bi bi-exclamation-circle-fill me-2"></i>
              {document.error_message || 'ছবি থেকে টেক্সট এক্সট্রাক্ট করা সম্ভব হয়নি।'}
            </div>
            <button
              className="btn btn-danger btn-sm rounded-pill px-3"
              onClick={handleRetry}
              disabled={retrying}
            >
              {retrying ? 'প্রসেসিং হচ্ছে...' : 'পুনরায় চেষ্টা করুন (Retry)'}
            </button>
          </div>
        )}
      </div>

      {/* Editor & Conversion Area */}
      {isCompleted && result && (
        <div className="row g-4">
          {/* Editable OCR Text Column */}
          <div className="col-lg-8">
            <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
              <div className="d-flex justify-content-between align-items-center mb-3">
                <h5 className="fw-bold bangla-text text-dark mb-0">
                  <i className="bi bi-pencil-square text-primary me-2"></i> এক্সট্রাক্ট করা টেক্সট এডিটর
                </h5>
                <span className="badge bg-light text-secondary border small bangla-text">
                  প্রোভাইডার: {result.provider || 'Dev Fallback'} (কনফিডেন্স: {result.confidence || 95}%)
                </span>
              </div>

              <p className="text-muted small bangla-text mb-3">
                OCR এর পর কোনো বানান বা লাইন ভুল থাকলে তা নিচে সরাসরি সম্পাদনা (Edit) করুন। সম্পাদনা শেষে ডানদিকের বাটন দিয়ে প্রশ্ন হিসেবে সংরক্ষণ করুন।
              </p>

              <textarea
                className="form-control ocr-textarea bangla-text p-3 rounded-3 flex-grow-1 mb-3"
                rows="14"
                value={editedText}
                onChange={(e) => setEditedText(e.target.value)}
              ></textarea>

              <div className="d-flex justify-content-end">
                <button
                  type="button"
                  className="btn btn-success bangla-text fw-bold px-4 py-2 rounded-pill shadow-sm"
                  onClick={() => setShowConvertModal(true)}
                >
                  <i className="bi bi-journal-plus me-2"></i> নোটবুকে প্রশ্ন হিসেবে রূপান্তর করুন
                </button>
              </div>
            </div>
          </div>

          {/* Source Raw Text Comparison Sidebar */}
          <div className="col-lg-4">
            <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
              <h6 className="fw-bold bangla-text text-secondary mb-3">
                <i className="bi bi-file-text me-1"></i> মূল আন-এডিটেড টেক্সট (Raw)
              </h6>
              <div
                className="p-3 bg-light rounded-3 small text-muted bangla-text overflow-auto"
                style={{ maxHeight: '420px', whiteSpace: 'pre-line', lineHeight: '1.7' }}
              >
                {result.raw_text}
              </div>
              <div className="alert alert-info bangla-text small mt-3 mb-0">
                <i className="bi bi-shield-check me-1"></i> আপনার আসল টেক্সট ও ছবি সংরক্ষিত থাকবে।
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Convert to Question Modal */}
      {showConvertModal && (
        <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(15, 23, 42, 0.7)' }}>
          <div className="modal-dialog modal-dialog-centered modal-lg">
            <div className="modal-content border-0 shadow-lg rounded-4">
              <div className="modal-header border-0 pb-0">
                <h5 className="modal-title fw-bold text-dark bangla-text">
                  <i className="bi bi-journal-plus text-primary me-2"></i>
                  নোটবুকে ব্যক্তিগত প্রশ্ন হিসেবে সংরক্ষণ
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setShowConvertModal(false)}
                ></button>
              </div>

              <form onSubmit={handleSaveAsQuestion}>
                <div className="modal-body py-4">
                  <div className="row g-3 mb-3">
                    <div className="col-md-6">
                      <label className="form-label bangla-text fw-semibold small">বিষয় নির্বাচন করুন</label>
                      <select
                        className="form-select bangla-text"
                        value={convertForm.subject_id}
                        onChange={handleSubjectSelect}
                      >
                        <option value="">বিষয় নির্বাচন করুন...</option>
                        {subjects.map((sub) => (
                          <option key={sub.id} value={sub.id}>{sub.name}</option>
                        ))}
                      </select>
                    </div>

                    <div className="col-md-6">
                      <label className="form-label bangla-text fw-semibold small">টপিক বা অধ্যায়</label>
                      <select
                        className="form-select bangla-text"
                        value={convertForm.topic_id}
                        onChange={(e) => setConvertForm((prev) => ({ ...prev, topic_id: e.target.value }))}
                        disabled={!convertForm.subject_id}
                      >
                        <option value="">টপিক নির্বাচন করুন...</option>
                        {topics.map((t) => (
                          <option key={t.id} value={t.id}>{t.name}</option>
                        ))}
                      </select>
                    </div>
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">প্রশ্ন শিরোনাম বা বিষয়বস্তু <span className="text-danger">*</span></label>
                    <input
                      type="text"
                      className="form-control bangla-text"
                      placeholder="উদাঃ চর্যাপদের কবি ও রচনাকাল সংক্রান্ত তথ্য"
                      value={convertForm.question}
                      onChange={(e) => setConvertForm((prev) => ({ ...prev, question: e.target.value }))}
                      required
                    />
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">উত্তর বা এক্সট্রাক্ট করা বিবরণ <span className="text-danger">*</span></label>
                    <textarea
                      className="form-control bangla-text"
                      rows="5"
                      value={convertForm.answer}
                      onChange={(e) => setConvertForm((prev) => ({ ...prev, answer: e.target.value }))}
                      required
                    ></textarea>
                  </div>

                  <div className="row g-3">
                    <div className="col-md-7">
                      <label className="form-label bangla-text fw-semibold small">বইয়ের নাম (রেফারেন্স)</label>
                      <input
                        type="text"
                        className="form-control bangla-text"
                        placeholder="বইয়ের নাম লিখুন..."
                        value={convertForm.source_title}
                        onChange={(e) => setConvertForm((prev) => ({ ...prev, source_title: e.target.value }))}
                      />
                    </div>
                    <div className="col-md-5">
                      <label className="form-label bangla-text fw-semibold small">পৃষ্ঠা নম্বর</label>
                      <input
                        type="text"
                        className="form-control bangla-text"
                        placeholder="উদাঃ পৃষ্ঠা ৭৪"
                        value={convertForm.source_page}
                        onChange={(e) => setConvertForm((prev) => ({ ...prev, source_page: e.target.value }))}
                      />
                    </div>
                  </div>
                </div>

                <div className="modal-footer border-0 pt-0">
                  <button
                    type="button"
                    className="btn btn-light bangla-text px-4 rounded-pill"
                    onClick={() => setShowConvertModal(false)}
                    disabled={savingNote}
                  >
                    বাতিল
                  </button>
                  <button
                    type="submit"
                    className="btn btn-success bangla-text fw-bold px-4 rounded-pill"
                    disabled={savingNote}
                  >
                    {savingNote ? 'সংরক্ষণ হচ্ছে...' : 'নোটবুকে সেভ করুন'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      <ConfirmModal
        isOpen={showDeleteModal}
        title="OCR ডকুমেন্ট মুছবেন?"
        message="আপনি কি নিশ্চিতভাবে এই আপলোড করা স্ক্যান ডকুমেন্টটি মুছে ফেলতে চান?"
        confirmText="মুছে ফেলুন"
        confirmVariant="danger"
        loading={deleting}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setShowDeleteModal(false)}
      />
    </div>
  );
};

export default OcrViewerPage;
