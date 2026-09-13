import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import ConfirmModal from '../../components/common/ConfirmModal';
import Pagination from '../../components/common/Pagination';

const PublicQuestionManagementPage = () => {
  const [questions, setQuestions] = useState([]);
  const [paginationMeta, setPaginationMeta] = useState(null);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [alert, setAlert] = useState(null);

  // Filter states
  const [subjects, setSubjects] = useState([]);
  const [selectedSubject, setSelectedSubject] = useState('');
  const [selectedDifficulty, setSelectedDifficulty] = useState('');
  const [selectedStatus, setSelectedStatus] = useState('');
  const [selectedType, setSelectedType] = useState('');
  const [searchQuery, setSearchQuery] = useState('');

  // Form Modal State
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [currentId, setCurrentId] = useState(null);
  const [formData, setFormData] = useState({
    subject_id: '',
    topic_id: '',
    study_guide_id: '',
    question: '',
    answer: '',
    explanation: '',
    question_type: 'mcq',
    options: ['', '', '', ''],
    correct_option: 'A',
    difficulty: 'medium',
    status: 'published',
  });
  const [topicsForSubject, setTopicsForSubject] = useState([]);
  const [saving, setSaving] = useState(false);
  const [validationErrors, setValidationErrors] = useState(null);

  // Delete State
  const [deleteId, setDeleteId] = useState(null);
  const [deleting, setDeleting] = useState(false);

  // Fetch subjects for dropdowns
  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const res = await apiClient.get('/subjects');
        setSubjects(res.data.data || []);
      } catch (err) {
        console.error('Failed to fetch subjects', err);
      }
    };
    fetchSubjects();
  }, []);

  // Fetch questions
  const fetchQuestions = async (targetPage = page) => {
    setLoading(true);
    try {
      const params = {
        page: targetPage,
      };
      if (selectedSubject) params.subject_id = selectedSubject;
      if (selectedDifficulty) params.difficulty = selectedDifficulty;
      if (selectedStatus) params.status = selectedStatus;
      if (selectedType) params.question_type = selectedType;
      if (searchQuery.trim()) params.search = searchQuery.trim();

      const response = await apiClient.get('/admin/public-questions', { params });
      setQuestions(response.data.data || []);
      setPaginationMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load public questions', err);
      setAlert({ type: 'danger', message: 'প্রশ্ন লোড করতে ব্যর্থ হয়েছে।' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchQuestions(1);
    setPage(1);
  }, [selectedSubject, selectedDifficulty, selectedStatus, selectedType]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchQuestions(1);
    setPage(1);
  };

  const handlePageChange = (newPage) => {
    setPage(newPage);
    fetchQuestions(newPage);
  };

  // Load topics when modal subject changes
  const handleModalSubjectChange = async (subjectId) => {
    setFormData((prev) => ({ ...prev, subject_id: subjectId, topic_id: '' }));
    if (!subjectId) {
      setTopicsForSubject([]);
      return;
    }
    try {
      const res = await apiClient.get(`/subjects/${subjectId}/topics`);
      setTopicsForSubject(res.data.data || []);
    } catch (err) {
      console.error('Failed to fetch topics for subject', err);
      setTopicsForSubject([]);
    }
  };

  const openCreateModal = () => {
    setIsEditing(false);
    setCurrentId(null);
    setFormData({
      subject_id: subjects.length > 0 ? subjects[0].id : '',
      topic_id: '',
      study_guide_id: '',
      question: '',
      answer: '',
      explanation: '',
      question_type: 'mcq',
      options: ['', '', '', ''],
      correct_option: 'A',
      difficulty: 'medium',
      status: 'published',
    });
    if (subjects.length > 0) {
      handleModalSubjectChange(subjects[0].id);
    }
    setValidationErrors(null);
    setShowModal(true);
  };

  const openEditModal = async (q) => {
    setIsEditing(true);
    setCurrentId(q.id);

    let opts = ['', '', '', ''];
    if (Array.isArray(q.options) && q.options.length > 0) {
      opts = [...q.options];
      while (opts.length < 4) opts.push('');
    }

    setFormData({
      subject_id: q.subject?.id || '',
      topic_id: q.topic?.id || '',
      study_guide_id: q.study_guide?.id || '',
      question: q.question || '',
      answer: q.answer || '',
      explanation: q.explanation || '',
      question_type: q.question_type || 'mcq',
      options: opts,
      correct_option: q.correct_option || 'A',
      difficulty: q.difficulty || 'medium',
      status: q.status || 'published',
    });

    if (q.subject?.id) {
      try {
        const res = await apiClient.get(`/subjects/${q.subject.id}/topics`);
        setTopicsForSubject(res.data.data || []);
      } catch (err) {
        console.error(err);
      }
    }

    setValidationErrors(null);
    setShowModal(true);
  };

  const handleOptionChange = (index, value) => {
    const newOptions = [...formData.options];
    newOptions[index] = value;
    setFormData((prev) => ({
      ...prev,
      options: newOptions,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setValidationErrors(null);

    const payload = {
      subject_id: formData.subject_id,
      topic_id: formData.topic_id || null,
      study_guide_id: formData.study_guide_id || null,
      question: formData.question,
      answer: formData.answer,
      explanation: formData.explanation || null,
      question_type: formData.question_type,
      difficulty: formData.difficulty,
      status: formData.status,
    };

    if (formData.question_type === 'mcq') {
      payload.options = formData.options.filter((opt) => opt.trim() !== '');
      payload.correct_option = formData.correct_option;
      // If user hasn't explicitly filled answer, set answer to selected option text
      const optMap = { A: 0, B: 1, C: 2, D: 3 };
      const idx = optMap[formData.correct_option];
      if (!payload.answer && formData.options[idx]) {
        payload.answer = formData.options[idx];
      }
    } else {
      payload.options = null;
      payload.correct_option = null;
    }

    try {
      if (isEditing) {
        await apiClient.put(`/admin/public-questions/${currentId}`, payload);
        setAlert({ type: 'success', message: 'প্রশ্নটি সফলভাবে আপডেট হয়েছে।' });
      } else {
        await apiClient.post('/admin/public-questions', payload);
        setAlert({ type: 'success', message: 'নতুন প্রশ্ন সফলভাবে যুক্ত হয়েছে।' });
      }
      setShowModal(false);
      fetchQuestions(page);
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setAlert({ type: 'danger', message: 'সংরক্ষণ ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।' });
      }
    } finally {
      setSaving(false);
    }
  };

  const handlePublishToggle = async (q) => {
    try {
      if (q.status === 'published') {
        await apiClient.post(`/admin/public-questions/${q.id}/archive`);
        setAlert({ type: 'info', message: 'প্রশ্নটি আর্কাইভে পাঠানো হয়েছে।' });
      } else {
        await apiClient.post(`/admin/public-questions/${q.id}/publish`);
        setAlert({ type: 'success', message: 'প্রশ্নটি প্রকাশিত হয়েছে।' });
      }
      fetchQuestions(page);
    } catch (err) {
      setAlert({ type: 'danger', message: 'স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে।' });
    }
  };

  const handleDeleteConfirm = async () => {
    if (!deleteId) return;
    setDeleting(true);
    try {
      await apiClient.delete(`/admin/public-questions/${deleteId}`);
      setAlert({ type: 'success', message: 'প্রশ্নটি সফলভাবে মুছে ফেলা হয়েছে।' });
      setDeleteId(null);
      fetchQuestions(page);
    } catch (err) {
      setAlert({ type: 'danger', message: 'প্রশ্ন ডিলিট করা যায়নি।' });
    } finally {
      setDeleting(false);
    }
  };

  const getDifficultyBadge = (diff) => {
    switch (diff) {
      case 'easy':
        return <span className="badge bg-success-subtle text-success border border-success-subtle">সহজ</span>;
      case 'medium':
        return <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">মাঝারি</span>;
      case 'hard':
        return <span className="badge bg-danger-subtle text-danger border border-danger-subtle">কঠিন</span>;
      default:
        return <span className="badge bg-secondary">{diff}</span>;
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case 'published':
        return <span className="badge bg-success"><i className="bi bi-check-circle me-1"></i>প্রকাশিত</span>;
      case 'draft':
        return <span className="badge bg-secondary"><i className="bi bi-pencil me-1"></i>খসড়া</span>;
      case 'archived':
        return <span className="badge bg-dark"><i className="bi bi-archive me-1"></i>আর্কাইভ</span>;
      default:
        return <span className="badge bg-light text-dark">{status}</span>;
    }
  };

  return (
    <div>
      <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
          <h1 className="h3 fw-bold text-slate-800 mb-1">পাবলিক প্রশ্ন ব্যাংক ব্যবস্থাপনা</h1>
          <p className="text-muted mb-0">
            সরকারি ও বেসরকারি চাকরির প্রস্তুতির জন্য উন্মুক্ত প্রশ্ন ও সমাধান তৈরি এবং পরিচালনা করুন।
          </p>
        </div>
        <button className="btn btn-primary shadow-sm" onClick={openCreateModal}>
          <i className="bi bi-plus-lg me-1"></i> নতুন প্রশ্ন তৈরি করুন
        </button>
      </div>

      {alert && <AlertMessage type={alert.type} message={alert.message} onClose={() => setAlert(null)} />}

      {/* Filter Toolbar */}
      <div className="card shadow-sm border-0 mb-4">
        <div className="card-body p-3">
          <div className="row g-2 align-items-center">
            <div className="col-12 col-md-3">
              <form onSubmit={handleSearchSubmit} className="input-group">
                <input
                  type="text"
                  className="form-control"
                  placeholder="প্রশ্ন বা সমাধান খুঁজুন..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
                <button className="btn btn-outline-secondary" type="submit">
                  <i className="bi bi-search"></i>
                </button>
              </form>
            </div>

            <div className="col-6 col-md-2">
              <select
                className="form-select"
                value={selectedSubject}
                onChange={(e) => setSelectedSubject(e.target.value)}
              >
                <option value="">সকল বিষয়</option>
                {subjects.map((sub) => (
                  <option key={sub.id} value={sub.id}>
                    {sub.name}
                  </option>
                ))}
              </select>
            </div>

            <div className="col-6 col-md-2">
              <select
                className="form-select"
                value={selectedType}
                onChange={(e) => setSelectedType(e.target.value)}
              >
                <option value="">সকল প্রশ্নের ধরন</option>
                <option value="mcq">MCQ</option>
                <option value="short_answer">সংক্ষিপ্ত প্রশ্ন</option>
                <option value="true_false">সত্য / মিথ্যা</option>
              </select>
            </div>

            <div className="col-6 col-md-2">
              <select
                className="form-select"
                value={selectedDifficulty}
                onChange={(e) => setSelectedDifficulty(e.target.value)}
              >
                <option value="">সকল জটিলতা</option>
                <option value="easy">সহজ (Easy)</option>
                <option value="medium">মাঝারি (Medium)</option>
                <option value="hard">কঠিন (Hard)</option>
              </select>
            </div>

            <div className="col-6 col-md-2">
              <select
                className="form-select"
                value={selectedStatus}
                onChange={(e) => setSelectedStatus(e.target.value)}
              >
                <option value="">সকল স্ট্যাটাস</option>
                <option value="published">প্রকাশিত</option>
                <option value="draft">খসড়া</option>
                <option value="archived">আর্কাইভ</option>
              </select>
            </div>

            <div className="col-12 col-md-1 text-md-end">
              <button
                className="btn btn-outline-secondary w-100"
                title="ফিল্টার রিসেট"
                onClick={() => {
                  setSelectedSubject('');
                  setSelectedDifficulty('');
                  setSelectedStatus('');
                  setSelectedType('');
                  setSearchQuery('');
                }}
              >
                <i className="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Questions Table */}
      <div className="card shadow-sm border-0">
        <div className="card-body p-0">
          {loading ? (
            <div className="p-5 text-center">
              <LoadingSpinner message="প্রশ্নমালা লোড হচ্ছে..." />
            </div>
          ) : questions.length === 0 ? (
            <div className="p-5 text-center text-muted">
              <i className="bi bi-question-circle display-4 text-muted opacity-50 mb-3 d-block"></i>
              <h5>কোন প্রশ্ন পাওয়া যায়নি</h5>
              <p className="mb-3">ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন অথবা নতুন প্রশ্ন যুক্ত করুন।</p>
              <button className="btn btn-sm btn-primary" onClick={openCreateModal}>
                <i className="bi bi-plus-lg me-1"></i> প্রথম প্রশ্ন তৈরি করুন
              </button>
            </div>
          ) : (
            <div className="table-responsive">
              <table className="table table-hover align-middle mb-0">
                <thead className="table-light">
                  <tr>
                    <th style={{ width: '60px' }} className="text-center">#</th>
                    <th>প্রশ্ন ও বিবরণ</th>
                    <th style={{ width: '160px' }}>বিষয় ও বিষয়বস্তু</th>
                    <th style={{ width: '100px' }}>ধরন</th>
                    <th style={{ width: '100px' }}>জটিলতা</th>
                    <th style={{ width: '110px' }}>অবস্থা</th>
                    <th style={{ width: '140px' }} className="text-end">অ্যাকশন</th>
                  </tr>
                </thead>
                <tbody>
                  {questions.map((q, idx) => (
                    <tr key={q.id}>
                      <td className="text-center text-muted small">
                        {paginationMeta ? (paginationMeta.current_page - 1) * 20 + idx + 1 : idx + 1}
                      </td>
                      <td>
                        <div className="fw-semibold text-slate-800 mb-1 bng-font line-clamp-2">
                          {q.question}
                        </div>
                        {q.question_type === 'mcq' && q.options && (
                          <div className="small text-muted mb-1">
                            <span className="me-2 text-primary">
                              <strong>সঠিক উত্তর:</strong> {q.correct_option}
                            </span>
                            <span className="opacity-75">
                              {Array.isArray(q.options) ? q.options.join(' • ') : ''}
                            </span>
                          </div>
                        )}
                        <div className="small text-success bng-font">
                          <i className="bi bi-check2 me-1"></i>
                          {q.answer?.length > 70 ? q.answer.substring(0, 70) + '...' : q.answer}
                        </div>
                      </td>
                      <td>
                        <span className="badge bg-light text-dark border mb-1 d-inline-block">
                          {q.subject?.name || 'বিষয়হীন'}
                        </span>
                        {q.topic && (
                          <div className="small text-muted bng-font">{q.topic.name}</div>
                        )}
                      </td>
                      <td>
                        <span className="badge bg-info-subtle text-info-emphasis border border-info-subtle text-uppercase">
                          {q.question_type}
                        </span>
                      </td>
                      <td>{getDifficultyBadge(q.difficulty)}</td>
                      <td>{getStatusBadge(q.status)}</td>
                      <td className="text-end">
                        <div className="btn-group btn-group-sm">
                          <button
                            className="btn btn-outline-secondary"
                            title={q.status === 'published' ? 'আর্কাইভ করুন' : 'প্রকাশ করুন'}
                            onClick={() => handlePublishToggle(q)}
                          >
                            <i className={`bi ${q.status === 'published' ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                          </button>
                          <button
                            className="btn btn-outline-primary"
                            title="সম্পাদনা করুন"
                            onClick={() => openEditModal(q)}
                          >
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button
                            className="btn btn-outline-danger"
                            title="মুছে ফেলুন"
                            onClick={() => setDeleteId(q.id)}
                          >
                            <i className="bi bi-trash"></i>
                          </button>
                        </div>
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
              মোট {paginationMeta.total} টি প্রশ্নের মধ্যে {paginationMeta.from}-{paginationMeta.to} দেখানো হচ্ছে
            </span>
            <Pagination
              currentPage={paginationMeta.current_page}
              lastPage={paginationMeta.last_page}
              onPageChange={handlePageChange}
            />
          </div>
        )}
      </div>

      {/* Create / Edit Question Modal */}
      {showModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }} tabIndex="-1">
          <div className="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title fw-bold">
                  {isEditing ? 'প্রশ্ন সম্পাদনা করুন' : 'নতুন প্রশ্ন তৈরি করুন'}
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setShowModal(false)}
                  disabled={saving}
                ></button>
              </div>

              <form onSubmit={handleSubmit}>
                <div className="modal-body">
                  <div className="row g-3 mb-3">
                    <div className="col-md-6">
                      <label className="form-label fw-semibold">
                        বিষয় <span className="text-danger">*</span>
                      </label>
                      <select
                        className={`form-select ${validationErrors?.subject_id ? 'is-invalid' : ''}`}
                        value={formData.subject_id}
                        onChange={(e) => handleModalSubjectChange(e.target.value)}
                        required
                      >
                        <option value="">বিষয় নির্বাচন করুন</option>
                        {subjects.map((sub) => (
                          <option key={sub.id} value={sub.id}>
                            {sub.name}
                          </option>
                        ))}
                      </select>
                      {validationErrors?.subject_id && (
                        <div className="invalid-feedback">{validationErrors.subject_id[0]}</div>
                      )}
                    </div>

                    <div className="col-md-6">
                      <label className="form-label fw-semibold">টপিক / বিষয়বস্তু</label>
                      <select
                        className={`form-select ${validationErrors?.topic_id ? 'is-invalid' : ''}`}
                        value={formData.topic_id}
                        onChange={(e) => setFormData({ ...formData, topic_id: e.target.value })}
                      >
                        <option value="">টপিক নির্বাচন করুন (ঐচ্ছিক)</option>
                        {topicsForSubject.map((t) => (
                          <option key={t.id} value={t.id}>
                            {t.name}
                          </option>
                        ))}
                      </select>
                      {validationErrors?.topic_id && (
                        <div className="invalid-feedback">{validationErrors.topic_id[0]}</div>
                      )}
                    </div>
                  </div>

                  <div className="row g-3 mb-3">
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">প্রশ্নের ধরন</label>
                      <select
                        className="form-select"
                        value={formData.question_type}
                        onChange={(e) => setFormData({ ...formData, question_type: e.target.value })}
                      >
                        <option value="mcq">MCQ (বহুনির্বাচনী)</option>
                        <option value="short_answer">সংক্ষিপ্ত প্রশ্ন</option>
                        <option value="true_false">সত্য / মিথ্যা</option>
                      </select>
                    </div>

                    <div className="col-md-4">
                      <label className="form-label fw-semibold">জটিলতার মাত্রা</label>
                      <select
                        className="form-select"
                        value={formData.difficulty}
                        onChange={(e) => setFormData({ ...formData, difficulty: e.target.value })}
                      >
                        <option value="easy">সহজ (Easy)</option>
                        <option value="medium">মাঝারি (Medium)</option>
                        <option value="hard">কঠিন (Hard)</option>
                      </select>
                    </div>

                    <div className="col-md-4">
                      <label className="form-label fw-semibold">স্ট্যাটাস</label>
                      <select
                        className="form-select"
                        value={formData.status}
                        onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                      >
                        <option value="published">প্রকাশিত (Published)</option>
                        <option value="draft">খসড়া (Draft)</option>
                        <option value="archived">আর্কাইভ (Archived)</option>
                      </select>
                    </div>
                  </div>

                  {/* Question Text */}
                  <div className="mb-3">
                    <label className="form-label fw-semibold">
                      প্রশ্ন <span className="text-danger">*</span>
                    </label>
                    <textarea
                      className={`form-control bng-font ${validationErrors?.question ? 'is-invalid' : ''}`}
                      rows="3"
                      placeholder="এখানে প্রশ্ন লিখুন (বাংলা বা ইংরেজি)..."
                      value={formData.question}
                      onChange={(e) => setFormData({ ...formData, question: e.target.value })}
                      required
                    ></textarea>
                    {validationErrors?.question && (
                      <div className="invalid-feedback">{validationErrors.question[0]}</div>
                    )}
                  </div>

                  {/* MCQ Options Builder */}
                  {formData.question_type === 'mcq' && (
                    <div className="card bg-light border p-3 mb-3">
                      <div className="d-flex justify-content-between align-items-center mb-2">
                        <span className="fw-semibold small text-slate-800">
                          MCQ অপশনসমূহ ও সঠিক উত্তর নির্বাচন করুন
                        </span>
                        <span className="small text-muted">সঠিক উত্তরের রেডিও বাটনে ক্লিক করুন</span>
                      </div>
                      <div className="row g-2">
                        {['A', 'B', 'C', 'D'].map((label, idx) => (
                          <div className="col-md-6" key={label}>
                            <div className="input-group">
                              <div className="input-group-text bg-white">
                                <input
                                  type="radio"
                                  name="correctOptionRadio"
                                  className="form-check-input mt-0"
                                  checked={formData.correct_option === label}
                                  onChange={() => {
                                    setFormData((prev) => ({
                                      ...prev,
                                      correct_option: label,
                                      answer: prev.options[idx] || prev.answer,
                                    }));
                                  }}
                                  title={`অপশন ${label} কে সঠিক উত্তর হিসেবে চিহ্নিত করুন`}
                                />
                                <span className="ms-2 fw-bold text-slate-700">{label}</span>
                              </div>
                              <input
                                type="text"
                                className="form-control bng-font"
                                placeholder={`অপশন ${label}`}
                                value={formData.options[idx] || ''}
                                onChange={(e) => handleOptionChange(idx, e.target.value)}
                              />
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Answer Text */}
                  <div className="mb-3">
                    <label className="form-label fw-semibold">
                      সঠিক উত্তর <span className="text-danger">*</span>
                    </label>
                    <input
                      type="text"
                      className={`form-control bng-font ${validationErrors?.answer ? 'is-invalid' : ''}`}
                      placeholder="প্রশ্নের সরাসরি সঠিক উত্তর"
                      value={formData.answer}
                      onChange={(e) => setFormData({ ...formData, answer: e.target.value })}
                      required
                    />
                    {validationErrors?.answer && (
                      <div className="invalid-feedback">{validationErrors.answer[0]}</div>
                    )}
                  </div>

                  {/* Explanation */}
                  <div className="mb-2">
                    <label className="form-label fw-semibold">বিস্তারিত ব্যাখ্যা / নোট (ঐচ্ছিক)</label>
                    <textarea
                      className="form-control bng-font"
                      rows="3"
                      placeholder="প্রশ্নটির নির্ভুল প্রেক্ষাপট, সূত্র অথবা সহায়ক তথ্য লিখুন..."
                      value={formData.explanation}
                      onChange={(e) => setFormData({ ...formData, explanation: e.target.value })}
                    ></textarea>
                  </div>
                </div>

                <div className="modal-footer bg-light">
                  <button
                    type="button"
                    className="btn btn-secondary"
                    onClick={() => setShowModal(false)}
                    disabled={saving}
                  >
                    বাতিল
                  </button>
                  <button type="submit" className="btn btn-primary" disabled={saving}>
                    {saving ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-1"></span>
                        সংরক্ষণ হচ্ছে...
                      </>
                    ) : isEditing ? (
                      'আপডেট করুন'
                    ) : (
                      'সংরক্ষণ করুন'
                    )}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      <ConfirmModal
        show={!!deleteId}
        title="প্রশ্ন মুছে ফেলার নিশ্চিতকরণ"
        message="আপনি কি নিশ্চিতভাবে এই প্রশ্নটি মুছে ফেলতে চান? এই অ্যাকশনটি অপরিবর্তনীয়।"
        confirmLabel="হ্যাঁ, মুছে ফেলুন"
        cancelLabel="বাতিল"
        confirmVariant="danger"
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteId(null)}
        loading={deleting}
      />
    </div>
  );
};

export default PublicQuestionManagementPage;
