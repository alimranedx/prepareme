import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const MistakeRevisionPage = () => {
  const { id } = useParams();
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let isMounted = true;
    const fetchMistakes = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await apiClient.get(`/test-attempts/${id}/mistakes`);
        if (isMounted) {
          setData(res.data || null);
        }
      } catch (err) {
        console.error('Failed to load mistakes', err);
        if (isMounted) {
          setError('ভুল প্রশ্নের তালিকা লোড করা সম্ভব হয়নি।');
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    if (id) fetchMistakes();
    return () => { isMounted = false; };
  }, [id]);

  if (loading) {
    return (
      <div className="container py-5">
        <LoadingSpinner text="ভুল প্রশ্নগুলো রিভিশন মোডে প্রস্তুত করা হচ্ছে..." />
      </div>
    );
  }

  if (error || !data) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger rounded-4 p-4 bangla-text">
          <h5>{error || 'কোনো ভুল উত্তর পাওয়া যায়নি'}</h5>
          <Link to="/model-tests" className="btn btn-primary rounded-pill px-4 mt-3">
            মডেল টেস্ট তালিকায় যান
          </Link>
        </div>
      </div>
    );
  }

  const mistakes = data.mistakes || [];

  return (
    <div className="container py-4 py-lg-5">
      {/* Header */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
        <div className="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <span className="badge bg-danger text-white rounded-pill px-3 py-2 fw-semibold mb-3">
              <i className="bi bi-arrow-repeat me-1"></i>ভুল প্রশ্ন রিভিশন স্টুডিও
            </span>
            <h1 className="fw-bold text-dark bangla-text display-6 mb-2">
              ভুল প্রশ্ন থেকে ১০০% প্রস্তুতি
            </h1>
            <p className="text-muted bangla-text fs-6 mb-0">
              টেস্ট: <strong>{data.model_test_title}</strong> | মোট ভুল প্রশ্ন: <span className="text-danger fw-bold">{data.total_mistakes} টি</span>
            </p>
          </div>

          <div className="d-flex gap-2">
            <Link
              to={`/model-tests/attempts/${id}/result`}
              className="btn btn-outline-secondary bangla-text rounded-pill px-4"
            >
              <i className="bi bi-arrow-left me-1"></i>স্কোরকার্ডে ফিরে যান
            </Link>
          </div>
        </div>
      </div>

      {mistakes.length === 0 ? (
        <div className="alert alert-success border-0 rounded-4 p-5 text-center bangla-text shadow-sm bg-white">
          <i className="bi bi-check-circle-fill text-success fs-1 d-block mb-3"></i>
          <h4 className="fw-bold text-dark mb-2">চমৎকার! এই পরীক্ষায় আপনার কোনো ভুল প্রশ্ন নেই!</h4>
          <p className="text-muted mb-4">আপনি সবকটি প্রশ্নের সঠিক উত্তর দিয়েছেন অথবা বাকিগুলো স্কিপ করেছেন।</p>
          <Link to="/model-tests" className="btn btn-primary rounded-pill px-4">
            নতুন টেস্ট শুরু করুন
          </Link>
        </div>
      ) : (
        <div className="d-flex flex-column gap-4">
          {mistakes.map((item, index) => {
            const q = item.question;
            return (
              <div className="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-danger" key={item.answer_id}>
                <div className="d-flex justify-content-between align-items-center mb-3">
                  <span className="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-bold">
                    ভুল প্রশ্ন {index + 1}
                  </span>
                  <div className="d-flex gap-2">
                    {q.subject && (
                      <span className="badge bg-light text-dark border rounded-pill px-3 py-1 small">
                        {q.subject.name}
                      </span>
                    )}
                    {q.chapter && (
                      <span className="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                        {q.chapter.name}
                      </span>
                    )}
                  </div>
                </div>

                <h4 className="fw-bold text-dark bangla-text fs-5 mb-4" style={{ lineHeight: '1.7' }}>
                  {q.question}
                </h4>

                {/* Options Review */}
                {q.options && (
                  <div className="row g-3 mb-4">
                    {Object.entries(q.options).map(([optKey, optVal]) => {
                      const isUserMistake = item.selected_option === optKey;
                      const isCorrect = q.correct_option === optKey;

                      let optStyle = 'bg-light border text-dark';
                      if (isCorrect) {
                        optStyle = 'bg-success-subtle border-success text-success-emphasis fw-bold';
                      } else if (isUserMistake) {
                        optStyle = 'bg-danger-subtle border-danger text-danger fw-bold';
                      }

                      return (
                        <div className="col-md-6" key={optKey}>
                          <div className={`p-3 rounded-3 bangla-text d-flex align-items-center justify-content-between ${optStyle}`}>
                            <div className="d-flex align-items-center gap-2">
                              <span className="fw-bold">{optKey})</span>
                              <span>{optVal}</span>
                            </div>
                            <div>
                              {isCorrect && (
                                <span className="badge bg-success text-white rounded-pill px-2 py-1 small me-1">
                                  সঠিক উত্তর
                                </span>
                              )}
                              {isUserMistake && (
                                <span className="badge bg-danger text-white rounded-pill px-2 py-1 small">
                                  আপনার ভুল উত্তর
                                </span>
                              )}
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}

                {/* In-depth revision explanation */}
                {q.explanation && (
                  <div className="p-4 rounded-3 bg-light border-start border-4 border-success">
                    <span className="fw-bold text-success d-flex align-items-center gap-2 mb-2 bangla-text">
                      <i className="bi bi-lightbulb-fill"></i>
                      ভুল সংশোধনের ব্যাখ্যা ও মনে রাখার টেকনিক:
                    </span>
                    <p className="bangla-text text-dark mb-0 small" style={{ lineHeight: '1.8' }}>
                      {q.explanation}
                    </p>
                  </div>
                )}
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default MistakeRevisionPage;
