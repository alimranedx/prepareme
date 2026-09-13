import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';
import Pagination from '../../components/common/Pagination';

const PublicQuestionsPage = () => {
  const [questions, setQuestions] = useState([]);
  const [subjects, setSubjects] = useState([]);
  const [topics, setTopics] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);

  // Filters
  const [filters, setFilters] = useState({
    subject_id: '',
    topic_id: '',
    difficulty: '',
    question_type: '',
    search: '',
    page: 1,
  });

  // Track interactive MCQ answers selected by the user in this session
  const [userSelections, setUserSelections] = useState({});
  const [expandedExplanations, setExpandedExplanations] = useState({});

  // Load subjects once
  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {
        console.error('Failed to load subjects', err);
      }
    };
    fetchSubjects();
  }, []);

  // Load topics when subject changes
  useEffect(() => {
    if (!filters.subject_id) {
      setTopics([]);
      return;
    }
    const fetchTopics = async () => {
      try {
        const response = await apiClient.get(`/subjects/${filters.subject_id}/topics`);
        setTopics(response.data.data || []);
      } catch (err) {
        console.error('Failed to load topics', err);
      }
    };
    fetchTopics();
  }, [filters.subject_id]);

  // Load questions
  useEffect(() => {
    const fetchQuestions = async () => {
      setLoading(true);
      try {
        const params = new URLSearchParams();
        if (filters.subject_id) params.append('subject_id', filters.subject_id);
        if (filters.topic_id) params.append('topic_id', filters.topic_id);
        if (filters.difficulty) params.append('difficulty', filters.difficulty);
        if (filters.question_type) params.append('question_type', filters.question_type);
        if (filters.search) params.append('search', filters.search);
        params.append('page', filters.page);

        const response = await apiClient.get(`/public-questions?${params.toString()}`);
        setQuestions(response.data.data || []);
        setMeta(response.data.meta || null);
      } catch (err) {
        console.error('Failed to load questions', err);
      } finally {
        setLoading(false);
      }
    };

    fetchQuestions();
  }, [filters]);

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilters((prev) => ({
      ...prev,
      [name]: value,
      page: 1, // Reset to page 1 on filter change
      ...(name === 'subject_id' ? { topic_id: '' } : {}),
    }));
  };

  const handleOptionSelect = (questionId, optionKey) => {
    if (userSelections[questionId]) return; // already answered
    setUserSelections((prev) => ({
      ...prev,
      [questionId]: optionKey,
    }));
    // Auto expand explanation on selection
    setExpandedExplanations((prev) => ({
      ...prev,
      [questionId]: true,
    }));
  };

  const toggleExplanation = (questionId) => {
    setExpandedExplanations((prev) => ({
      ...prev,
      [questionId]: !prev[questionId],
    }));
  };

  const getDifficultyBadge = (level) => {
    switch (level) {
      case 'easy':
        return <span className="badge bg-success-subtle text-success">সহজ</span>;
      case 'medium':
        return <span className="badge bg-warning-subtle text-warning-emphasis">মাঝারি</span>;
      case 'hard':
        return <span className="badge bg-danger-subtle text-danger">কঠিন</span>;
      default:
        return null;
    }
  };

  return (
    <div className="container py-5">
      {/* Header */}
      <div className="text-center mb-5">
        <span className="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold bangla-text mb-2">
          বিগত সালের প্রশ্ন ও মডেল টেস্ট
        </span>
        <h2 className="fw-bold text-dark bangla-text">পাবলিক প্রশ্নব্যাংক ও প্র্যাকটিস</h2>
        <p className="text-muted bangla-text mx-auto" style={{ maxWidth: '650px' }}>
          বিসিএস, সরকারি ব্যাংক, পিএসসি ও প্রাথমিক শিক্ষক নিয়োগ পরীক্ষার ব্যাখ্যাসহ অধ্যায়ভিত্তিক প্রশ্ন ও নির্ভুল উত্তরমালা।
        </p>
      </div>

      {/* Filter Bar */}
      <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div className="row g-3">
          <div className="col-md-4 col-lg-3">
            <label className="form-label small fw-semibold bangla-text">বিষয় নির্বাচন করুন</label>
            <select
              name="subject_id"
              className="form-select bangla-text"
              value={filters.subject_id}
              onChange={handleFilterChange}
            >
              <option value="">সকল বিষয়</option>
              {subjects.map((sub) => (
                <option key={sub.id} value={sub.id}>{sub.name}</option>
              ))}
            </select>
          </div>

          <div className="col-md-4 col-lg-3">
            <label className="form-label small fw-semibold bangla-text">টপিক বা অধ্যায়</label>
            <select
              name="topic_id"
              className="form-select bangla-text"
              value={filters.topic_id}
              onChange={handleFilterChange}
              disabled={!filters.subject_id}
            >
              <option value="">সকল অধ্যায়</option>
              {topics.map((top) => (
                <option key={top.id} value={top.id}>{top.name}</option>
              ))}
            </select>
          </div>

          <div className="col-md-4 col-lg-2">
            <label className="form-label small fw-semibold bangla-text">কাঠিন্য মান</label>
            <select
              name="difficulty"
              className="form-select bangla-text"
              value={filters.difficulty}
              onChange={handleFilterChange}
            >
              <option value="">সকল মান</option>
              <option value="easy">সহজ</option>
              <option value="medium">মাঝারি</option>
              <option value="hard">কঠিন</option>
            </select>
          </div>

          <div className="col-md-4 col-lg-2">
            <label className="form-label small fw-semibold bangla-text">প্রশ্নের ধরন</label>
            <select
              name="question_type"
              className="form-select bangla-text"
              value={filters.question_type}
              onChange={handleFilterChange}
            >
              <option value="">সকল ধরন</option>
              <option value="mcq">MCQ</option>
              <option value="short_answer">সংক্ষিপ্ত উত্তর</option>
              <option value="true_false">সত্য / মিথ্যা</option>
            </select>
          </div>

          <div className="col-md-8 col-lg-2">
            <label className="form-label small fw-semibold bangla-text">অনুসন্ধান</label>
            <input
              type="text"
              name="search"
              className="form-control bangla-text"
              placeholder="প্রশ্ন খুঁজুন..."
              value={filters.search}
              onChange={handleFilterChange}
            />
          </div>
        </div>
      </div>

      {/* Questions List */}
      {loading ? (
        <LoadingSpinner text="প্রশ্নসমূহ লোড হচ্ছে..." />
      ) : questions.length === 0 ? (
        <EmptyState
          title="কোন প্রশ্ন পাওয়া যায়নি"
          message="আপনার ফিল্টারের সাথে মিলে এমন কোনো প্রশ্ন বর্তমানে নেই।"
        />
      ) : (
        <div className="d-flex flex-column gap-4">
          {questions.map((q, idx) => {
            const selectedOpt = userSelections[q.id];
            const isAnswered = !!selectedOpt;
            const isExplanationOpen = expandedExplanations[q.id];

            return (
              <div key={q.id} className="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div className="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                  <div className="d-flex align-items-center gap-2">
                    <span className="badge bg-primary px-3 py-2 rounded-pill">
                      প্রশ্ন #{((filters.page - 1) * (meta?.per_page || 15)) + idx + 1}
                    </span>
                    {q.subject && (
                      <span className="badge bg-light text-secondary border bangla-text">
                        {q.subject.name}
                      </span>
                    )}
                    {q.topic && (
                      <span className="badge bg-light text-secondary border bangla-text">
                        {q.topic.name}
                      </span>
                    )}
                  </div>
                  {getDifficultyBadge(q.difficulty)}
                </div>

                <h5 className="fw-bold text-dark bangla-text mb-4" style={{ lineHeight: '1.7' }}>
                  {q.question}
                </h5>

                {/* MCQ Options */}
                {q.question_type === 'mcq' && q.options && (
                  <div className="row g-3 mb-3">
                    {Object.entries(q.options).map(([optKey, optVal]) => {
                      const isSelected = selectedOpt === optKey;
                      const isCorrect = q.correct_option === optKey;

                      let btnClass = 'btn-outline-secondary';
                      let icon = optKey;

                      if (isAnswered) {
                        if (isCorrect) {
                          btnClass = 'btn-success text-white';
                          icon = <i className="bi bi-check-lg"></i>;
                        } else if (isSelected && !isCorrect) {
                          btnClass = 'btn-danger text-white';
                          icon = <i className="bi bi-x-lg"></i>;
                        }
                      }

                      return (
                        <div className="col-md-6" key={optKey}>
                          <button
                            type="button"
                            className={`btn ${btnClass} w-100 text-start p-3 rounded-3 d-flex align-items-center gap-3 bangla-text`}
                            onClick={() => handleOptionSelect(q.id, optKey)}
                            disabled={isAnswered}
                            style={{ transition: 'all 0.2s ease' }}
                          >
                            <span className="badge bg-dark bg-opacity-25 rounded-circle p-2" style={{ minWidth: '30px', textAlign: 'center' }}>
                              {icon}
                            </span>
                            <span className="flex-grow-1">{optVal}</span>
                          </button>
                        </div>
                      );
                    })}
                  </div>
                )}

                {/* Non-MCQ Answer Display */}
                {q.question_type !== 'mcq' && (
                  <div className="p-3 bg-light rounded-3 mb-3">
                    <span className="text-secondary small fw-bold bangla-text d-block mb-1">সঠিক উত্তর:</span>
                    <p className="mb-0 fw-semibold text-success bangla-text">{q.answer}</p>
                  </div>
                )}

                {/* Action Bar */}
                <div className="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                  <button
                    type="button"
                    className="btn btn-sm btn-link text-decoration-none p-0 bangla-text"
                    onClick={() => toggleExplanation(q.id)}
                  >
                    <i className={`bi ${isExplanationOpen ? 'bi-chevron-up' : 'bi-chevron-down'} me-1`}></i>
                    {isExplanationOpen ? 'ব্যাখ্যা লুকান' : 'বিস্তারিত ব্যাখ্যা দেখুন'}
                  </button>

                  {isAnswered && (
                    <span className="small text-muted bangla-text">
                      সঠিক উত্তর: <strong className="text-success">{q.correct_option || q.answer}</strong>
                    </span>
                  )}
                </div>

                {/* Collapsible Explanation Box */}
                {isExplanationOpen && (
                  <div className="mt-3 p-3 bg-primary-subtle rounded-3 border-start border-4 border-primary">
                    <h6 className="fw-bold text-primary bangla-text mb-1">
                      <i className="bi bi-info-circle-fill me-1"></i> ব্যাখ্যা ও রেফারেন্স:
                    </h6>
                    <p className="text-secondary mb-0 bangla-text small" style={{ lineHeight: '1.7', whiteSpace: 'pre-line' }}>
                      {q.explanation || 'এই প্রশ্নের জন্য কোনো অতিরিক্ত ব্যাখ্যা সংযুক্ত করা হয়নি।'}
                    </p>
                  </div>
                )}
              </div>
            );
          })}

          {/* Pagination */}
          <Pagination
            meta={meta}
            onPageChange={(p) => setFilters((prev) => ({ ...prev, page: p }))}
          />
        </div>
      )}
    </div>
  );
};

export default PublicQuestionsPage;
