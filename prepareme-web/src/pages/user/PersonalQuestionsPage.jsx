import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';
import Pagination from '../../components/common/Pagination';
import ConfirmModal from '../../components/common/ConfirmModal';
import AlertMessage from '../../components/common/AlertMessage';

const PersonalQuestionsPage = () => {
  const [questions, setQuestions] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState('active');
  const [alert, setAlert] = useState(null);

  // Delete modal state
  const [deleteId, setDeleteId] = useState(null);
  const [deleting, setDeleting] = useState(false);

  const fetchQuestions = async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (search) params.append('search', search);
      if (status) params.append('status', status);
      params.append('page', page);

      const response = await apiClient.get(`/my/questions?${params.toString()}`);
      setQuestions(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load personal questions', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchQuestions(1);
  }, [status]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchQuestions(1);
  };

  const handleDeleteConfirm = async () => {
    if (!deleteId) return;
    setDeleting(true);
    try {
      await apiClient.delete(`/my/questions/${deleteId}`);
      setAlert({ type: 'success', message: 'প্রশ্নটি সফলভাবে ডিলিট করা হয়েছে।' });
      setDeleteId(null);
      fetchQuestions(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'ডিলিট করতে সমস্যা হয়েছে।' });
    } finally {
      setDeleting(false);
    }
  };

  const handleArchiveToggle = async (question) => {
    try {
      await apiClient.post(`/my/questions/${question.id}/archive`);
      setAlert({
        type: 'info',
        message: question.status === 'archived' ? 'প্রশ্নটি সক্রিয় করা হয়েছে।' : 'প্রশ্নটি আর্কাইভ করা হয়েছে।',
      });
      fetchQuestions(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে।' });
    }
  };

  return (
    <div className="container py-5">
      {/* Header */}
      <div className="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <span className="badge bg-primary px-3 py-2 rounded-pill bangla-text mb-2">
            ব্যক্তিগত ডায়েরি
          </span>
          <h2 className="fw-bold text-dark bangla-text mb-0">আমার নিজস্ব প্রশ্নব্যাংক ও নোটবুক</h2>
          <p className="text-muted bangla-text small mb-0 mt-1">
            বই বা পরীক্ষার উৎস থেকে আপনার নিজের সংগ্রহ করা প্রশ্নোত্তর (সম্পূর্ণ নিরাপদ ও ব্যক্তিগত)
          </p>
        </div>
        <div className="d-flex gap-2">
          <Link to="/my/ocr" className="btn btn-outline-success bangla-text rounded-pill px-3">
            <i className="bi bi-camera me-1"></i> বই স্ক্যান করে যোগ করুন
          </Link>
          <Link to="/my/questions/create" className="btn btn-primary bangla-text rounded-pill px-4">
            <i className="bi bi-plus-lg me-1"></i> নতুন প্রশ্ন লিখুন
          </Link>
        </div>
      </div>

      <AlertMessage
        type={alert?.type}
        message={alert?.message}
        onClose={() => setAlert(null)}
      />

      {/* Filter and Search Bar */}
      <div className="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div className="row g-3 align-items-center">
          <div className="col-md-4">
            <div className="btn-group w-100" role="group">
              <button
                type="button"
                className={`btn bangla-text ${status === 'active' ? 'btn-primary' : 'btn-outline-secondary'}`}
                onClick={() => setStatus('active')}
              >
                সক্রিয় প্রশ্নসমূহ
              </button>
              <button
                type="button"
                className={`btn bangla-text ${status === 'archived' ? 'btn-primary' : 'btn-outline-secondary'}`}
                onClick={() => setStatus('archived')}
              >
                আর্কাইভ
              </button>
            </div>
          </div>

          <div className="col-md-8">
            <form onSubmit={handleSearchSubmit}>
              <div className="input-group">
                <input
                  type="text"
                  className="form-control bangla-text"
                  placeholder="প্রশ্ন, উত্তর বা বইয়ের নাম লিখে খুঁজুন..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                />
                <button type="submit" className="btn btn-primary bangla-text px-4">
                  <i className="bi bi-search me-1"></i> খুঁজুন
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {/* Questions List */}
      {loading ? (
        <LoadingSpinner text="নোটবুক লোড হচ্ছে..." />
      ) : questions.length === 0 ? (
        <EmptyState
          icon="bi-journal-x"
          title="কোন প্রশ্ন পাওয়া যায়নি"
          message="আপনার ব্যক্তিগত নোটবুকে এখনও কোনো প্রশ্ন যোগ করা হয়নি।"
          actionText="প্রথম প্রশ্নটি তৈরি করুন"
          actionLink="/my/questions/create"
        />
      ) : (
        <div className="d-flex flex-column gap-4">
          {questions.map((q) => (
            <div key={q.id} className="card border-0 shadow-sm rounded-4 p-4 bg-white">
              <div className="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                <div className="d-flex align-items-center gap-2 flex-wrap">
                  {q.subject && (
                    <span className="badge bg-primary-subtle text-primary bangla-text">
                      {q.subject.name}
                    </span>
                  )}
                  {q.topic && (
                    <span className="badge bg-light text-secondary border bangla-text">
                      {q.topic.name}
                    </span>
                  )}
                  {q.source_title && (
                    <span className="badge bg-light text-dark border bangla-text">
                      <i className="bi bi-book me-1 text-primary"></i>
                      {q.source_title} {q.source_page ? `(পৃষ্ঠা: ${q.source_page})` : ''}
                    </span>
                  )}
                  {q.ocr_document_id && (
                    <span className="badge bg-info-subtle text-info bangla-text">
                      <i className="bi bi-camera me-1"></i> OCR স্ক্যান থেকে
                    </span>
                  )}
                </div>

                {/* Actions */}
                <div className="d-flex gap-2">
                  <button
                    type="button"
                    className="btn btn-sm btn-outline-secondary"
                    title={q.status === 'archived' ? 'সক্রিয় করুন' : 'আর্কাইভ করুন'}
                    onClick={() => handleArchiveToggle(q)}
                  >
                    <i className={`bi ${q.status === 'archived' ? 'bi-box-arrow-up' : 'bi-archive'}`}></i>
                  </button>
                  <Link
                    to={`/my/questions/${q.id}/edit`}
                    className="btn btn-sm btn-outline-primary"
                    title="সম্পাদনা করুন"
                  >
                    <i className="bi bi-pencil-square"></i>
                  </Link>
                  <button
                    type="button"
                    className="btn btn-sm btn-outline-danger"
                    title="মুছে ফেলুন"
                    onClick={() => setDeleteId(q.id)}
                  >
                    <i className="bi bi-trash"></i>
                  </button>
                </div>
              </div>

              <h5 className="fw-bold text-dark bangla-text mb-3" style={{ lineHeight: '1.7' }}>
                {q.question}
              </h5>

              <div className="p-3 bg-light rounded-3 mb-3">
                <span className="text-secondary small fw-bold bangla-text d-block mb-1">উত্তর:</span>
                <p className="mb-0 fw-semibold text-success bangla-text" style={{ whiteSpace: 'pre-line' }}>
                  {q.answer}
                </p>
              </div>

              {q.explanation && (
                <div className="text-secondary small bangla-text mb-3" style={{ lineHeight: '1.7', whiteSpace: 'pre-line' }}>
                  <strong>ব্যক্তিগত নোট বা ব্যাখ্যা:</strong> {q.explanation}
                </div>
              )}

              {/* Tags */}
              {q.tags && q.tags.length > 0 && (
                <div className="d-flex gap-1 flex-wrap pt-2 border-top">
                  {q.tags.map((tag) => (
                    <span key={tag.id} className="badge bg-secondary-subtle text-secondary small bangla-text">
                      #{tag.name}
                    </span>
                  ))}
                </div>
              )}
            </div>
          ))}

          <Pagination
            meta={meta}
            onPageChange={(p) => fetchQuestions(p)}
          />
        </div>
      )}

      {/* Confirm Delete Modal */}
      <ConfirmModal
        isOpen={!!deleteId}
        title="প্রশ্ন মুছে ফেলুন"
        message="আপনি কি নিশ্চিতভাবে এই প্রশ্নটি আপনার ব্যক্তিগত নোটবুক থেকে মুছে ফেলতে চান?"
        confirmText="হ্যাঁ, মুছে ফেলুন"
        confirmVariant="danger"
        loading={deleting}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteId(null)}
      />
    </div>
  );
};

export default PersonalQuestionsPage;
