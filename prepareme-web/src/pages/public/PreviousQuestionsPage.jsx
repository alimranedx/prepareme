import React, { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';
import Pagination from '../../components/common/Pagination';

const PreviousQuestionsPage = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const [questions, setQuestions] = useState([]);
  const [sources, setSources] = useState([]);
  const [exams, setExams] = useState([]);
  const [subjects, setSubjects] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);

  // Filters state
  const [examId, setExamId] = useState(searchParams.get('exam_id') || '');
  const [sourceId, setSourceId] = useState(searchParams.get('source_id') || '');
  const [year, setYear] = useState(searchParams.get('year') || '');
  const [subjectId, setSubjectId] = useState(searchParams.get('subject_id') || '');
  const [search, setSearch] = useState(searchParams.get('search') || '');
  const [currentPage, setCurrentPage] = useState(parseInt(searchParams.get('page') || '1', 10));

  // Revealed explanations map { [questionId]: boolean }
  const [revealedAnswers, setRevealedAnswers] = useState({});

  // Fetch filter metadata (sources, exams, subjects)
  useEffect(() => {
    let isMounted = true;
    const fetchMetadata = async () => {
      try {
        const [examsRes, sourcesRes, subjectsRes] = await Promise.all([
          apiClient.get('/exams').catch(() => ({ data: { data: [] } })),
          apiClient.get('/question-sources').catch(() => ({ data: { data: [] } })),
          apiClient.get('/subjects').catch(() => ({ data: { data: [] } })),
        ]);

        if (isMounted) {
          setExams(examsRes.data.data || []);
          setSources(sourcesRes.data.data || []);
          setSubjects(subjectsRes.data.data || []);
        }
      } catch (err) {
        console.error('Failed to load filter metadata', err);
      }
    };
    fetchMetadata();
    return () => { isMounted = false; };
  }, []);

  // Fetch questions on filter or page change
  useEffect(() => {
    let isMounted = true;

    const fetchQuestions = async () => {
      setLoading(true);
      try {
        const params = {
          page: currentPage,
          per_page: 15,
        };
        if (examId) params.exam_id = examId;
        if (sourceId) params.source_id = sourceId;
        if (year) params.year = year;
        if (subjectId) params.subject_id = subjectId;
        if (search.trim()) params.search = search.trim();

        const res = await apiClient.get('/previous-questions', { params });
        if (isMounted) {
          setQuestions(res.data.data || []);
          setPagination(res.data.meta || null);
        }
      } catch (err) {
        console.error('Failed to load previous questions', err);
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    fetchQuestions();
    return () => { isMounted = false; };
  }, [examId, sourceId, year, subjectId, search, currentPage]);

  const toggleAnswer = (qId) => {
    setRevealedAnswers((prev) => ({
      ...prev,
      [qId]: !prev[qId],
    }));
  };

  const handleResetFilters = () => {
    setExamId('');
    setSourceId('');
    setYear('');
    setSubjectId('');
    setSearch('');
    setCurrentPage(1);
    setSearchParams({});
  };

  return (
    <div className="container py-4 py-lg-5">
      {/* Hero Header */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
        <div className="row align-items-center gy-3">
          <div className="col-lg-8">
            <span className="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold mb-3">
              <i className="bi bi-archive-fill me-1"></i>বিগত ১০+ বছরের প্রশ্ন সংকলন
            </span>
            <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
              বিগত সালের চাকরি পরীক্ষার প্রশ্নব্যাংক ও নির্ভুল সমাধান
            </h1>
            <p className="text-muted bangla-text fs-6 mb-0" style={{ lineHeight: '1.8' }}>
              বিসিএস প্রিলিমিনারি (১০ম থেকে ৪৬তম), বাংলাদেশ ব্যাংক ও সমন্বিত সরকারি ব্যাংক এবং প্রাইমারি সহকারী শিক্ষক নিয়োগের সকল প্রশ্নের বিষয়ভিত্তিক ও বছরভিত্তিক ব্যাখ্যাসহ সংগ্রহ।
            </p>
          </div>
          <div className="col-lg-4 text-lg-end">
            <Link to="/model-tests" className="btn btn-warning fw-bold bangla-text rounded-pill px-4 py-2 shadow-sm">
              <i className="bi bi-clock-history me-1"></i>টাইমড মডেল টেস্ট দিন
            </Link>
          </div>
        </div>

        {/* Filters Form */}
        <div className="mt-4 pt-4 border-top">
          <div className="row g-3">
            <div className="col-md-3">
              <label className="form-label bangla-text small fw-bold text-dark mb-1">
                পরীক্ষার ধরন
              </label>
              <select
                className="form-select bangla-text rounded-3"
                value={examId}
                onChange={(e) => { setExamId(e.target.value); setCurrentPage(1); }}
              >
                <option value="">সকল পরীক্ষা</option>
                {exams.map((ex) => (
                  <option key={ex.id} value={ex.id}>{ex.name}</option>
                ))}
              </select>
            </div>

            <div className="col-md-3">
              <label className="form-label bangla-text small fw-bold text-dark mb-1">
                প্রশ্নপত্র (Question Paper)
              </label>
              <select
                className="form-select bangla-text rounded-3"
                value={sourceId}
                onChange={(e) => { setSourceId(e.target.value); setCurrentPage(1); }}
              >
                <option value="">সকল প্রশ্নপত্র</option>
                {sources.map((src) => (
                  <option key={src.id} value={src.id}>
                    {src.name} ({src.year})
                  </option>
                ))}
              </select>
            </div>

            <div className="col-md-3">
              <label className="form-label bangla-text small fw-bold text-dark mb-1">
                বিষয় নির্বাচন
              </label>
              <select
                className="form-select bangla-text rounded-3"
                value={subjectId}
                onChange={(e) => { setSubjectId(e.target.value); setCurrentPage(1); }}
              >
                <option value="">সকল বিষয়</option>
                {subjects.map((sub) => (
                  <option key={sub.id} value={sub.id}>{sub.name}</option>
                ))}
              </select>
            </div>

            <div className="col-md-3">
              <label className="form-label bangla-text small fw-bold text-dark mb-1">
                প্রশ্ন সার্চ করুন
              </label>
              <div className="input-group">
                <input
                  type="text"
                  className="form-control bangla-text rounded-start-3"
                  placeholder="কী-ওয়ার্ড দিয়ে খুঁজুন..."
                  value={search}
                  onChange={(e) => { setSearch(e.target.value); setCurrentPage(1); }}
                />
                {(examId || sourceId || subjectId || search) && (
                  <button
                    className="btn btn-outline-secondary"
                    type="button"
                    onClick={handleResetFilters}
                    title="রিসেট"
                  >
                    <i className="bi bi-x-circle"></i>
                  </button>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Questions List */}
      {loading ? (
        <LoadingSpinner text="প্রশ্নাবলি সাজানো হচ্ছে..." />
      ) : questions.length === 0 ? (
        <EmptyState
          title="কোন প্রশ্ন পাওয়া যায়নি"
          message="আপনার দেওয়া ফিল্টারের সাথে মিলে এমন কোনো বিগত প্রশ্ন পাওয়া যায়নি। অন্য ফিল্টার দিয়ে আবার চেষ্টা করুন।"
        />
      ) : (
        <div className="d-flex flex-column gap-4">
          {questions.map((q, index) => {
            const isRevealed = revealedAnswers[q.id];
            const examTag = q.previous_exam_tag || (q.source ? q.source.name : null);

            return (
              <div className="card border-0 shadow-sm rounded-4 p-4 bg-white" key={q.id}>
                {/* Question Header & Badges */}
                <div className="d-flex justify-content-between align-items-start gap-2 mb-3 flex-wrap">
                  <div className="d-flex align-items-center gap-2 flex-wrap">
                    <span className="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold">
                      প্রশ্ন {((currentPage - 1) * 15) + (index + 1)}
                    </span>
                    {examTag && (
                      <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-semibold">
                        <i className="bi bi-award-fill me-1"></i>{examTag}
                      </span>
                    )}
                    {q.subject && (
                      <span className="badge bg-light text-dark border rounded-pill px-3 py-1">
                        {q.subject.name}
                      </span>
                    )}
                    {q.chapter && (
                      <span className="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                        {q.chapter.name}
                      </span>
                    )}
                  </div>

                  <button
                    type="button"
                    className={`btn btn-sm rounded-pill px-3 bangla-text ${isRevealed ? 'btn-success' : 'btn-outline-primary'}`}
                    onClick={() => toggleAnswer(q.id)}
                  >
                    <i className={`bi ${isRevealed ? 'bi-eye-slash-fill' : 'bi-eye-fill'} me-1`}></i>
                    {isRevealed ? 'উত্তর লুকান' : 'উত্তর ও ব্যাখ্যা দেখুন'}
                  </button>
                </div>

                {/* Question Text */}
                <h4 className="fw-bold text-dark bangla-text fs-5 mb-4" style={{ lineHeight: '1.7' }}>
                  {q.question}
                </h4>

                {/* MCQ Options */}
                {q.options && (
                  <div className="row g-3 mb-3">
                    {Object.entries(q.options).map(([optKey, optVal]) => {
                      const isCorrect = optKey === q.correct_option;
                      let optionClass = 'bg-light border text-dark';
                      if (isRevealed && isCorrect) {
                        optionClass = 'bg-success-subtle border-success text-success-emphasis fw-bold';
                      }

                      return (
                        <div className="col-md-6" key={optKey}>
                          <div className={`p-3 rounded-3 bangla-text d-flex align-items-center justify-content-between ${optionClass}`}>
                            <div className="d-flex align-items-center gap-2">
                              <span className="fw-bold">{optKey})</span>
                              <span>{optVal}</span>
                            </div>
                            {isRevealed && isCorrect && (
                              <span className="badge bg-success text-white rounded-pill px-2 py-1 small">
                                <i className="bi bi-check-lg me-1"></i>সঠিক উত্তর
                              </span>
                            )}
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}

                {/* Explanation Drawer */}
                {isRevealed && (
                  <div className="mt-3 p-4 rounded-3 bg-light border-start border-4 border-success">
                    <div className="d-flex align-items-center gap-2 mb-2">
                      <i className="bi bi-lightbulb-fill text-success fs-5"></i>
                      <h6 className="fw-bold text-success mb-0 bangla-text">
                        সঠিক উত্তর: <span className="text-dark">({q.correct_option}) {q.answer}</span>
                      </h6>
                    </div>
                    {q.explanation ? (
                      <p className="bangla-text text-dark mb-0 small" style={{ lineHeight: '1.8' }}>
                        {q.explanation}
                      </p>
                    ) : (
                      <p className="bangla-text text-muted mb-0 small">
                        ব্যাখ্যা সংযোজন করা হচ্ছে।
                      </p>
                    )}
                  </div>
                )}
              </div>
            );
          })}

          {/* Pagination */}
          {pagination && pagination.last_page > 1 && (
            <div className="d-flex justify-content-center mt-4">
              <Pagination
                currentPage={currentPage}
                totalPages={pagination.last_page}
                onPageChange={(page) => setCurrentPage(page)}
              />
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default PreviousQuestionsPage;
