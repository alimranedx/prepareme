import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const ModelTestResultPage = () => {
  const { id } = useParams();
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let isMounted = true;
    const fetchResult = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await apiClient.get(`/test-attempts/${id}/result`);
        if (isMounted) {
          setResult(res.data.data || null);
        }
      } catch (err) {
        console.error('Failed to load result', err);
        if (isMounted) {
          setError('ফলাফল লোড করতে সমস্যা হয়েছে।');
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    if (id) fetchResult();
    return () => { isMounted = false; };
  }, [id]);

  if (loading) {
    return (
      <div className="container py-5">
        <LoadingSpinner text="ফলাফল ও বিশ্লেষণ তৈরি করা হচ্ছে..." />
      </div>
    );
  }

  if (error || !result) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger rounded-4 p-4 bangla-text">
          <h5>{error || 'ফলাফল পাওয়া যায়নি'}</h5>
          <Link to="/model-tests" className="btn btn-primary rounded-pill px-4 mt-3">
            সকল মডেল টেস্ট
          </Link>
        </div>
      </div>
    );
  }

  const modelTest = result.model_test;
  const isPassed = result.is_passed;

  return (
    <div className="container py-4 py-lg-5">
      {/* Result Hero Scorecard */}
      <div className={`card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 text-center ${isPassed ? 'bg-success-subtle border-success' : 'bg-white'}`}>
        <div className="d-flex justify-content-center mb-3">
          <span className={`p-3 rounded-circle fs-1 ${isPassed ? 'bg-success text-white' : 'bg-primary text-white'}`}>
            <i className={isPassed ? 'bi bi-trophy-fill' : 'bi bi-check2-circle'}></i>
          </span>
        </div>

        <h2 className="fw-bold text-dark bangla-text mb-2">
          {isPassed ? 'অভিনন্দন! আপনি উত্তীর্ণ হয়েছেন' : 'পরীক্ষা সম্পন্ন হয়েছে'}
        </h2>
        <p className="text-muted bangla-text fs-6 mb-4">
          {modelTest?.title}
        </p>

        {/* Score & Accuracy Stats Grid */}
        <div className="row g-3 justify-content-center mb-4">
          <div className="col-6 col-md-3">
            <div className="p-3 rounded-4 bg-white border shadow-xs">
              <span className="text-muted small bangla-text d-block">প্রাপ্ত মোট নম্বর</span>
              <strong className="fs-3 fw-bold text-primary bangla-text">{result.score}</strong>
              <span className="text-muted small d-block bangla-text">/ {result.total_questions}</span>
            </div>
          </div>
          <div className="col-6 col-md-3">
            <div className="p-3 rounded-4 bg-white border shadow-xs">
              <span className="text-muted small bangla-text d-block">সঠিক উত্তর</span>
              <strong className="fs-3 fw-bold text-success bangla-text">{result.total_correct} টি</strong>
              <span className="text-success small d-block bangla-text">পরিশুদ্ধ</span>
            </div>
          </div>
          <div className="col-6 col-md-3">
            <div className="p-3 rounded-4 bg-white border shadow-xs">
              <span className="text-muted small bangla-text d-block">ভুল উত্তর</span>
              <strong className="fs-3 fw-bold text-danger bangla-text">{result.total_wrong} টি</strong>
              <span className="text-danger small d-block bangla-text">নেগেটিভ মার্ক কর্তিত</span>
            </div>
          </div>
          <div className="col-6 col-md-3">
            <div className="p-3 rounded-4 bg-white border shadow-xs">
              <span className="text-muted small bangla-text d-block">নির্ভুলতা (Accuracy)</span>
              <strong className="fs-3 fw-bold text-info bangla-text">{result.accuracy_percentage}%</strong>
              <span className="text-muted small d-block bangla-text">সঠিক অনুপাত</span>
            </div>
          </div>
        </div>

        {/* Action buttons */}
        <div className="d-flex justify-content-center gap-3 flex-wrap">
          {result.total_wrong > 0 && (
            <Link
              to={`/model-tests/attempts/${id}/mistakes`}
              className="btn btn-danger fw-bold bangla-text rounded-pill px-4 py-2 shadow-sm"
            >
              <i className="bi bi-arrow-repeat me-1"></i>
              ভুল প্রশ্নগুলো রিভিশন দিন ({result.total_wrong} টি)
            </Link>
          )}

          <Link
            to={`/model-tests/${modelTest?.slug}/take`}
            className="btn btn-warning fw-bold bangla-text rounded-pill px-4 py-2 shadow-sm"
          >
            <i className="bi bi-arrow-clockwise me-1"></i>পুনরায় টেস্ট দিন
          </Link>

          <Link to="/model-tests" className="btn btn-outline-secondary bangla-text rounded-pill px-4 py-2">
            অন্যান্য মডেল টেস্ট
          </Link>
        </div>
      </div>

      {/* Question-by-Question Detailed Review */}
      <h3 className="fw-bold text-dark bangla-text mb-4">
        <i className="bi bi-card-checklist text-primary me-2"></i>
        প্রশ্নভিত্তিক পুঙ্খানুপুঙ্খ বিশ্লেষণ ও ব্যাখ্যা
      </h3>

      <div className="d-flex flex-column gap-4">
        {result.answers && result.answers.map((item, idx) => {
          const q = item.question;
          const isCorrect = item.is_correct;
          const isSkipped = !item.selected_option;

          let cardBorder = 'border-start border-4 ';
          if (isSkipped) cardBorder += 'border-secondary';
          else if (isCorrect) cardBorder += 'border-success';
          else cardBorder += 'border-danger';

          return (
            <div className={`card border-0 shadow-sm rounded-4 p-4 bg-white ${cardBorder}`} key={item.id}>
              {/* Review Header */}
              <div className="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div className="d-flex align-items-center gap-2">
                  <span className="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold">
                    প্রশ্ন {idx + 1}
                  </span>
                  {isSkipped ? (
                    <span className="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                      <i className="bi bi-dash-circle me-1"></i>উত্তর দেওয়া হয়নি
                    </span>
                  ) : isCorrect ? (
                    <span className="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                      <i className="bi bi-check-circle-fill me-1"></i>সঠিক উত্তর (+{item.marks_awarded})
                    </span>
                  ) : (
                    <span className="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                      <i className="bi bi-x-circle-fill me-1"></i>ভুল উত্তর ({item.marks_awarded})
                    </span>
                  )}
                </div>

                <div className="d-flex gap-2">
                  {q?.subject && (
                    <span className="badge bg-light text-muted border small rounded-pill">
                      {q.subject.name}
                    </span>
                  )}
                  {q?.chapter && (
                    <span className="badge bg-light text-muted border small rounded-pill">
                      {q.chapter.name}
                    </span>
                  )}
                </div>
              </div>

              {/* Question Text */}
              <h5 className="fw-bold text-dark bangla-text fs-5 mb-4" style={{ lineHeight: '1.7' }}>
                {q?.question}
              </h5>

              {/* Options Review */}
              {q?.options && (
                <div className="row g-3 mb-4">
                  {Object.entries(q.options).map(([optKey, optVal]) => {
                    const isUserChoice = item.selected_option === optKey;
                    const isActuallyCorrect = q.correct_option === optKey;

                    let optClass = 'bg-light border text-dark';
                    if (isActuallyCorrect) {
                      optClass = 'bg-success-subtle border-success text-success-emphasis fw-bold';
                    } else if (isUserChoice && !isCorrect) {
                      optClass = 'bg-danger-subtle border-danger text-danger fw-bold';
                    }

                    return (
                      <div className="col-md-6" key={optKey}>
                        <div className={`p-3 rounded-3 bangla-text d-flex align-items-center justify-content-between ${optClass}`}>
                          <div className="d-flex align-items-center gap-2">
                            <span className="fw-bold">{optKey})</span>
                            <span>{optVal}</span>
                          </div>
                          <div>
                            {isActuallyCorrect && (
                              <span className="badge bg-success text-white rounded-pill px-2 py-1 small me-1">
                                সঠিক উত্তর
                              </span>
                            )}
                            {isUserChoice && (
                              <span className={`badge ${isCorrect ? 'bg-success' : 'bg-danger'} text-white rounded-pill px-2 py-1 small`}>
                                আপনার উত্তর
                              </span>
                            )}
                          </div>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}

              {/* Explanation Card */}
              {q?.explanation && (
                <div className="p-3 rounded-3 bg-light border-start border-3 border-success bangla-text small">
                  <span className="fw-bold text-success d-block mb-1">
                    <i className="bi bi-lightbulb-fill me-1"></i>ব্যাখ্যা ও সমাধানের সূত্র:
                  </span>
                  <p className="text-dark mb-0" style={{ lineHeight: '1.7' }}>
                    {q.explanation}
                  </p>
                </div>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default ModelTestResultPage;
