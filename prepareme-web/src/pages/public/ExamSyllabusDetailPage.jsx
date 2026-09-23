import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const ExamSyllabusDetailPage = () => {
  const { slug } = useParams();
  const [exam, setExam] = useState(null);
  const [syllabus, setSyllabus] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let isMounted = true;
    const fetchSyllabus = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await apiClient.get(`/exams/${slug}/syllabus`);
        if (isMounted) {
          setExam(res.data.exam || null);
          setSyllabus(res.data.syllabus || []);
        }
      } catch (err) {
        console.error('Failed to load syllabus', err);
        if (isMounted) {
          setError('সিলেবাস লোড করতে ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    if (slug) fetchSyllabus();
    return () => { isMounted = false; };
  }, [slug]);

  if (loading) {
    return (
      <div className="container py-5">
        <LoadingSpinner text="অফিসিয়াল সিলেবাস বিশ্লেষণ করা হচ্ছে..." />
      </div>
    );
  }

  if (error || !exam) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger rounded-4 p-4 bangla-text">
          <h5>{error || 'সিলেবাস পাওয়া যায়নি'}</h5>
          <Link to="/exams" className="btn btn-primary rounded-pill px-4 mt-3">
            সকল পরীক্ষা দেখুন
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="container py-4 py-lg-5">
      {/* Breadcrumb */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text small">
          <li className="breadcrumb-item">
            <Link to="/" className="text-decoration-none text-muted">
              <i className="bi bi-house-door me-1"></i>হোম
            </Link>
          </li>
          <li className="breadcrumb-item">
            <Link to="/exams" className="text-decoration-none text-muted">
              চাকরি সিলেবাস
            </Link>
          </li>
          <li className="breadcrumb-item active fw-bold text-dark" aria-current="page">
            {exam.name}
          </li>
        </ol>
      </nav>

      {/* Hero Exam Header */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
        <div className="row align-items-center gy-4">
          <div className="col-lg-8">
            <div className="d-flex gap-2 flex-wrap mb-3">
              <span className="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold">
                <i className="bi bi-check2-circle me-1"></i>অফিসিয়াল মানবণ্টন
              </span>
              <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-semibold">
                পূর্ণমান: {exam.total_marks} নম্বর | সময়: {exam.duration_minutes} মিনিট
              </span>
            </div>
            <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
              {exam.name}
            </h1>
            <p className="text-muted bangla-text fs-6 mb-4" style={{ lineHeight: '1.8' }}>
              {exam.description}
            </p>

            <div className="d-flex gap-3 flex-wrap">
              <Link to="/model-tests" className="btn btn-warning fw-bold bangla-text rounded-pill px-4 py-2 shadow-sm">
                <i className="bi bi-stopwatch me-1"></i>মডেল টেস্ট অনুশীলন করুন
              </Link>
              <Link to={`/previous-questions?exam_id=${exam.id}`} className="btn btn-outline-primary bangla-text rounded-pill px-4 py-2">
                <i className="bi bi-archive me-1"></i>বিগত সালের প্রশ্ন সমাধান
              </Link>
            </div>
          </div>

          <div className="col-lg-4">
            <div className="p-4 rounded-4 bg-light border">
              <h6 className="fw-bold text-dark bangla-text mb-3">
                <i className="bi bi-pie-chart-fill text-primary me-2"></i>
                বিষয়ভিত্তিক মানবণ্টন চার্ট
              </h6>
              <div className="d-flex flex-column gap-2">
                {syllabus.map((sub) => {
                  const percent = Math.round((sub.allocated_marks / exam.total_marks) * 100);
                  return (
                    <div key={sub.id}>
                      <div className="d-flex justify-content-between small bangla-text mb-1">
                        <span className="fw-semibold text-dark">{sub.name}</span>
                        <span className="fw-bold text-primary">{sub.allocated_marks} নম্বর ({percent}%)</span>
                      </div>
                      <div className="progress" style={{ height: '6px' }}>
                        <div
                          className="progress-bar bg-primary"
                          role="progressbar"
                          style={{ width: `${percent}%` }}
                          aria-valuenow={percent}
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Syllabus Subject-by-Subject Breakdown */}
      <h3 className="fw-bold text-dark bangla-text mb-4">
        <i className="bi bi-list-task text-primary me-2"></i>
        বিষয়ভিত্তিক পূর্ণাঙ্গ সিলেবাস ও হাই-ইল্ড টপিক
      </h3>

      <div className="d-flex flex-column gap-4">
        {syllabus.map((sub) => (
          <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white" key={sub.id}>
            <div className="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div className="d-flex align-items-center gap-3">
                <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-5">
                  <i className="bi bi-book-half"></i>
                </div>
                <div>
                  <h4 className="fw-bold text-dark bangla-text mb-0">
                    {sub.name}
                  </h4>
                  <span className="text-muted small bangla-text">
                    বরাদ্দকৃত নম্বর: <strong className="text-primary">{sub.allocated_marks} নম্বর</strong>
                  </span>
                </div>
              </div>

              <Link
                to={`/subjects/${sub.slug}`}
                className="btn btn-outline-primary btn-sm bangla-text rounded-pill px-3"
              >
                বিষয়ভিত্তিক পড়ার গাইড দেখুন <i className="bi bi-arrow-right ms-1"></i>
              </Link>
            </div>

            <div className="card-body p-4">
              {sub.chapters && sub.chapters.length > 0 ? (
                <div className="row g-4">
                  {sub.chapters.map((chap) => (
                    <div className="col-lg-6" key={chap.id}>
                      <div className="p-3 rounded-4 bg-light border h-100">
                        <h6 className="fw-bold text-dark bangla-text mb-3 d-flex align-items-center justify-content-between">
                          <span>
                            <i className="bi bi-journal-text text-primary me-2"></i>
                            {chap.name}
                          </span>
                          <span className="badge bg-white text-muted border small">
                            {chap.topics?.length || 0} টপিক
                          </span>
                        </h6>

                        {chap.topics && chap.topics.length > 0 ? (
                          <div className="d-flex flex-column gap-2">
                            {chap.topics.map((top) => (
                              <div
                                key={top.id}
                                className="p-2 rounded-3 bg-white border small bangla-text d-flex justify-content-between align-items-center"
                              >
                                <div className="d-flex align-items-center gap-2">
                                  <i className="bi bi-check-circle-fill text-success small"></i>
                                  <span className="fw-semibold text-dark">{top.name}</span>
                                </div>
                                {top.is_high_yield && (
                                  <span className="badge-high-yield small py-1">
                                    <i className="bi bi-star-fill me-1"></i>হাই-ইল্ড
                                  </span>
                                )}
                              </div>
                            ))}
                          </div>
                        ) : (
                          <div className="text-muted small bangla-text">টপিক তালিকা প্রস্তুতাধীন।</div>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-muted small bangla-text">এই বিষয়ের অধ্যায় বিন্যাস প্রস্তুতাধীন।</div>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ExamSyllabusDetailPage;
