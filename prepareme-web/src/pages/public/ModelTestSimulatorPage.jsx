import React, { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const ModelTestSimulatorPage = () => {
  const { slug } = useParams();
  const navigate = useNavigate();

  const [testSession, setTestSession] = useState(null);
  const [questions, setQuestions] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [answers, setAnswers] = useState({}); // { [questionId]: selected_option }
  const [questionTimeSpent, setQuestionTimeSpent] = useState({}); // { [questionId]: seconds }
  const [timeLeft, setTimeLeft] = useState(0); // in seconds
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [showSubmitModal, setShowSubmitModal] = useState(false);
  const [error, setError] = useState(null);

  const timerRef = useRef(null);
  const startTimeRef = useRef(Date.now());

  // 1. Initialize Test Session
  useEffect(() => {
    let isMounted = true;

    const startTest = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await apiClient.post(`/model-tests/${slug}/start`);
        if (isMounted) {
          const session = res.data;
          setTestSession(session);
          setQuestions(session.questions || []);
          setTimeLeft((session.model_test?.duration_minutes || 30) * 60);
          startTimeRef.current = Date.now();
        }
      } catch (err) {
        console.error('Failed to start test session', err);
        if (isMounted) {
          setError('মডেল টেস্ট শুরু করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    if (slug) startTest();

    return () => {
      isMounted = false;
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [slug]);

  // 2. Countdown Timer
  useEffect(() => {
    if (!testSession || timeLeft <= 0 || submitting) return;

    timerRef.current = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev <= 1) {
          clearInterval(timerRef.current);
          handleAutoSubmit();
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => {
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [testSession, submitting]);

  // Select option handler
  const handleSelectOption = (questionId, optionKey) => {
    setAnswers((prev) => {
      // Toggle off if already selected
      if (prev[questionId] === optionKey) {
        const updated = { ...prev };
        delete updated[questionId];
        return updated;
      }
      return {
        ...prev,
        [questionId]: optionKey,
      };
    });
  };

  const handleClearOption = (questionId) => {
    setAnswers((prev) => {
      const updated = { ...prev };
      delete updated[questionId];
      return updated;
    });
  };

  // Submit test
  const submitTest = async () => {
    if (!testSession || submitting) return;
    setSubmitting(true);

    try {
      const totalTimeTaken = Math.round((Date.now() - startTimeRef.current) / 1000);
      const answersPayload = questions.map((q) => ({
        question_id: q.id,
        selected_option: answers[q.id] || null,
        time_spent_seconds: questionTimeSpent[q.id] || 0,
      }));

      const res = await apiClient.post(`/test-attempts/${testSession.attempt_id}/submit`, {
        answers: answersPayload,
        time_taken_seconds: totalTimeTaken,
      });

      navigate(`/model-tests/attempts/${testSession.attempt_id}/result`);
    } catch (err) {
      console.error('Failed to submit test', err);
      alert('পরীক্ষা জমা দিতে ত্রুটি হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
      setSubmitting(false);
    }
  };

  const handleAutoSubmit = () => {
    submitTest();
  };

  if (loading) {
    return (
      <div className="container py-5">
        <LoadingSpinner text="পরীক্ষার প্রশ্নপত্র ও টাইমার প্রস্তুত করা হচ্ছে..." />
      </div>
    );
  }

  if (error || !testSession) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger rounded-4 p-4 bangla-text">
          <h5>{error || 'টেস্ট সেশন লোড করা সম্ভব হয়নি'}</h5>
          <Link to="/model-tests" className="btn btn-primary rounded-pill px-4 mt-3">
            মডেল টেস্ট তালিকায় ফিরে যান
          </Link>
        </div>
      </div>
    );
  }

  const currentQ = questions[currentIndex];
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  const isTimeCritical = timeLeft < 300; // < 5 mins
  const totalAnswered = Object.keys(answers).length;

  return (
    <div className="container-fluid py-3 px-lg-5 bg-light min-vh-100">
      {/* Top Test Header Bar */}
      <div className="card border-0 shadow-sm rounded-4 p-3 mb-3 bg-white">
        <div className="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <span className="badge bg-primary text-white rounded-pill px-3 py-1 small mb-1">
              লাইভ পরীক্ষা
            </span>
            <h5 className="fw-bold text-dark bangla-text mb-0">
              {testSession.model_test?.title}
            </h5>
          </div>

          {/* Real-time Timer Indicator */}
          <div className="d-flex align-items-center gap-3">
            <div className={`px-4 py-2 rounded-pill d-flex align-items-center gap-2 fw-bold fs-5 ${isTimeCritical ? 'bg-danger text-white animate-pulse' : 'bg-warning-subtle text-dark border border-warning'}`}>
              <i className="bi bi-clock-fill"></i>
              <span>
                {String(minutes).padStart(2, '0')}:{String(seconds).padStart(2, '0')}
              </span>
            </div>

            <button
              type="button"
              className="btn btn-success bangla-text rounded-pill px-4 fw-bold shadow-sm"
              onClick={() => setShowSubmitModal(true)}
              disabled={submitting}
            >
              <i className="bi bi-send-check-fill me-1"></i>
              {submitting ? 'জমা দেওয়া হচ্ছে...' : 'পরীক্ষা জমা দিন'}
            </button>
          </div>
        </div>
      </div>

      <div className="row g-3">
        {/* Main Question Card (Left Column) */}
        <div className="col-lg-8">
          <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white h-100 d-flex flex-column justify-content-between">
            {currentQ ? (
              <div>
                {/* Question Metadata */}
                <div className="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                  <span className="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold fs-6">
                    প্রশ্ন {currentIndex + 1} / {questions.length}
                  </span>
                  <div className="d-flex gap-2">
                    {currentQ.subject_name && (
                      <span className="badge bg-light text-dark border rounded-pill px-3 py-1 small">
                        {currentQ.subject_name}
                      </span>
                    )}
                    {currentQ.chapter_name && (
                      <span className="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                        {currentQ.chapter_name}
                      </span>
                    )}
                  </div>
                </div>

                {/* Question Prompt */}
                <h3 className="fw-bold text-dark bangla-text fs-4 mb-4" style={{ lineHeight: '1.8' }}>
                  {currentQ.question}
                </h3>

                {/* Options List */}
                <div className="d-flex flex-column gap-3 mb-4">
                  {currentQ.options && currentQ.options.map((opt) => {
                    const isSelected = answers[currentQ.id] === opt.key;
                    return (
                      <div
                        key={opt.key}
                        onClick={() => handleSelectOption(currentQ.id, opt.key)}
                        className={`p-3 rounded-4 border bangla-text cursor-pointer d-flex align-items-center gap-3 transition-all ${
                          isSelected
                            ? 'bg-primary text-white border-primary shadow-sm'
                            : 'bg-light text-dark hover-bg-light border-light-subtle'
                        }`}
                        style={{ cursor: 'pointer' }}
                      >
                        <div
                          className={`rounded-circle d-flex align-items-center justify-content-center fw-bold ${
                            isSelected ? 'bg-white text-primary' : 'bg-white text-dark border'
                          }`}
                          style={{ width: '36px', height: '36px', minWidth: '36px' }}
                        >
                          {opt.key}
                        </div>
                        <span className="fs-6 fw-medium">{opt.text}</span>
                      </div>
                    );
                  })}
                </div>
              </div>
            ) : (
              <div className="text-center py-5 bangla-text text-muted">প্রশ্ন খুঁজে পাওয়া যায়নি</div>
            )}

            {/* Question Navigation Controls */}
            <div className="d-flex justify-content-between align-items-center pt-4 border-top">
              <button
                type="button"
                className="btn btn-outline-secondary bangla-text rounded-pill px-4"
                disabled={currentIndex === 0}
                onClick={() => setCurrentIndex((prev) => Math.max(0, prev - 1))}
              >
                <i className="bi bi-arrow-left me-1"></i> পূর্ববর্তী
              </button>

              {currentQ && answers[currentQ.id] && (
                <button
                  type="button"
                  className="btn btn-outline-danger btn-sm bangla-text rounded-pill px-3"
                  onClick={() => handleClearOption(currentQ.id)}
                >
                  <i className="bi bi-x-circle me-1"></i> উত্তর বাতিল করুন
                </button>
              )}

              <button
                type="button"
                className="btn btn-primary bangla-text rounded-pill px-4"
                disabled={currentIndex === questions.length - 1}
                onClick={() => setCurrentIndex((prev) => Math.min(questions.length - 1, prev + 1))}
              >
                পরবর্তী <i className="bi bi-arrow-right ms-1"></i>
              </button>
            </div>
          </div>
        </div>

        {/* Right Palette (Question Navigator) */}
        <div className="col-lg-4">
          <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 className="fw-bold text-dark bangla-text mb-3">
              <i className="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
              প্রশ্ন প্যালেট (Question Navigator)
            </h6>

            {/* Answered / Unanswered stats */}
            <div className="d-flex gap-3 mb-4 pb-3 border-bottom small bangla-text">
              <div className="d-flex align-items-center gap-1">
                <span className="badge rounded-circle p-1 bg-success me-1"> </span>
                <span>উত্তর দেওয়া: <strong>{totalAnswered}</strong></span>
              </div>
              <div className="d-flex align-items-center gap-1">
                <span className="badge rounded-circle p-1 bg-secondary me-1"> </span>
                <span>বাকি আছে: <strong>{questions.length - totalAnswered}</strong></span>
              </div>
            </div>

            {/* Question buttons grid */}
            <div
              className="d-grid gap-2"
              style={{
                gridTemplateColumns: 'repeat(5, 1fr)',
                maxHeight: '400px',
                overflowY: 'auto',
              }}
            >
              {questions.map((q, idx) => {
                const isAnswered = Boolean(answers[q.id]);
                const isCurrent = idx === currentIndex;

                let btnClass = 'btn-outline-secondary';
                if (isAnswered) btnClass = 'btn-success text-white';
                if (isCurrent) btnClass += ' border-3 border-dark fw-bold';

                return (
                  <button
                    key={q.id}
                    type="button"
                    className={`btn btn-sm rounded-3 ${btnClass}`}
                    onClick={() => setCurrentIndex(idx)}
                  >
                    {idx + 1}
                  </button>
                );
              })}
            </div>

            <div className="mt-4 pt-3 border-top">
              <button
                type="button"
                className="btn btn-warning fw-bold bangla-text rounded-pill w-100 py-2 shadow-sm"
                onClick={() => setShowSubmitModal(true)}
              >
                <i className="bi bi-check2-circle me-1"></i>পরীক্ষা শেষ করুন
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Confirmation Submit Modal */}
      {showSubmitModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.6)' }} tabIndex="-1">
          <div className="modal-dialog modal-dialog-centered">
            <div className="modal-content rounded-4 border-0 p-3 p-lg-4">
              <div className="modal-header border-0 pb-0">
                <h5 className="modal-title fw-bold bangla-text text-dark">
                  পরীক্ষা জমা দেওয়ার নিশ্চয়তা
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setShowSubmitModal(false)}
                ></button>
              </div>
              <div className="modal-body py-4 bangla-text">
                <p className="fs-6 text-dark mb-3">
                  আপনি মোট <strong>{questions.length}</strong> টির মধ্যে <strong>{totalAnswered}</strong> টি প্রশ্নের উত্তর দিয়েছেন।
                </p>
                {questions.length - totalAnswered > 0 && (
                  <div className="alert alert-warning border-0 rounded-3 small">
                    <i className="bi bi-exclamation-triangle-fill me-2"></i>
                    আপনার <strong>{questions.length - totalAnswered}</strong> টি প্রশ্নের উত্তর দেওয়া এখনও বাকি রয়েছে।
                  </div>
                )}
                <p className="text-muted small mb-0">
                  জমা দেওয়ার পর আপনি অবিলম্বে বিস্তারিত স্কোরকার্ড, সঠিক উত্তর এবং ব্যাখ্যা দেখতে পাবেন।
                </p>
              </div>
              <div className="modal-footer border-0 pt-0">
                <button
                  type="button"
                  className="btn btn-light bangla-text rounded-pill px-4"
                  onClick={() => setShowSubmitModal(false)}
                >
                  ফিরে যান
                </button>
                <button
                  type="button"
                  className="btn btn-success fw-bold bangla-text rounded-pill px-4"
                  onClick={() => { setShowSubmitModal(false); submitTest(); }}
                  disabled={submitting}
                >
                  {submitting ? 'জমা দেওয়া হচ্ছে...' : 'হ্যাঁ, জমা দিন'}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ModelTestSimulatorPage;
