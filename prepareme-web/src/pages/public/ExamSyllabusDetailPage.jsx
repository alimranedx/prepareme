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
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedSubjectId, setSelectedSubjectId] = useState('all');

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
        <LoadingSpinner text="অফিসিয়াল সিলেবাস ও ২০০ নম্বরের বিষয়ভিত্তিক বিন্যাস বিশ্লেষণ করা হচ্ছে..." />
      </div>
    );
  }

  if (error || !exam) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger rounded-4 p-4 bangla-text shadow-sm mx-auto" style={{ maxWidth: '540px' }}>
          <i className="bi bi-exclamation-triangle-fill fs-2 text-danger d-block mb-2"></i>
          <h5 className="fw-bold mb-2">{error || 'সিলেবাস পাওয়া যায়নি'}</h5>
          <Link to="/exams" className="btn btn-primary rounded-pill px-4 mt-3 bangla-text">
            সকল পরীক্ষা দেখুন
          </Link>
        </div>
      </div>
    );
  }

  // Filter syllabus by selected subject tab and search query
  const filteredSyllabus = syllabus.filter((sub) => {
    const matchesSubject = selectedSubjectId === 'all' || String(sub.id) === String(selectedSubjectId);
    if (!matchesSubject) return false;
    if (!searchQuery.trim()) return true;

    const q = searchQuery.toLowerCase();
    const matchesSubName = sub.name.toLowerCase().includes(q);
    const matchesChapters = sub.chapters?.some((chap) =>
      chap.name.toLowerCase().includes(q) ||
      chap.topics?.some((top) => top.name.toLowerCase().includes(q))
    );
    return matchesSubName || matchesChapters;
  });

  return (
    <div className="container py-4 py-lg-5">
      {/* Breadcrumb */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text small bg-white p-3 rounded-4 shadow-sm border mb-0 flex-wrap align-items-center">
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
          <div className="col-lg-7">
            <div className="d-flex gap-2 flex-wrap mb-3">
              <span className="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold">
                <i className="bi bi-patch-check-fill me-1"></i>অফিসিয়াল সিলেবাস ও মানবণ্টন
              </span>
              <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-semibold">
                পূর্ণমান: {exam.total_marks} নম্বর | সময়: {exam.duration_minutes} মিনিট
              </span>
            </div>
            <h1 className="fw-extrabold text-dark bangla-text display-6 mb-3" style={{ fontWeight: 800 }}>
              {exam.name}
            </h1>
            <p className="text-muted bangla-text fs-6 mb-4" style={{ lineHeight: '1.85' }}>
              {exam.description}
            </p>

            <div className="d-flex gap-3 flex-wrap">
              <Link to="/model-tests" className="btn btn-hero-primary bangla-text rounded-pill px-4 py-25 shadow-sm fw-semibold">
                <i className="bi bi-stopwatch me-1"></i>মডেল টেস্ট অনুশীলন করুন
              </Link>
              <Link to={`/previous-questions?exam_id=${exam.id}`} className="btn btn-outline-primary bangla-text rounded-pill px-4 py-25 fw-semibold">
                <i className="bi bi-archive me-1"></i>বিগত সালের প্রশ্ন সমাধান
              </Link>
            </div>
          </div>

          <div className="col-lg-5">
            <div className="p-4 rounded-4 bg-light border">
              <h6 className="fw-bold text-dark bangla-text mb-3 d-flex align-items-center justify-content-between">
                <span>
                  <i className="bi bi-pie-chart-fill text-primary me-2"></i>
                  ২০০ নম্বরের মানবণ্টন চার্ট
                </span>
                <span className="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">
                  {syllabus.length} টি বিষয়
                </span>
              </h6>
              <div className="d-flex flex-column gap-2" style={{ maxHeight: '280px', overflowY: 'auto' }}>
                {syllabus.map((sub) => {
                  const percent = Math.round((sub.allocated_marks / exam.total_marks) * 100);
                  return (
                    <div key={sub.id} className="p-2 bg-white rounded-3 border">
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

      {/* Interactive Search & Filter Controls */}
      <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div className="row g-3 align-items-center">
          <div className="col-md-6">
            <div className="input-group">
              <span className="input-group-text bg-light border-end-0 text-muted">
                <i className="bi bi-search"></i>
              </span>
              <input
                type="text"
                className="form-control bg-light border-start-0 bangla-text"
                placeholder="সিলেবাসের যেকোনো টপিক বা অধ্যায় খুঁজুন..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
              {searchQuery && (
                <button className="btn btn-light border" onClick={() => setSearchQuery('')}>
                  <i className="bi bi-x"></i>
                </button>
              )}
            </div>
          </div>

          <div className="col-md-6">
            <div className="d-flex align-items-center gap-2 overflow-x-auto pb-1" style={{ scrollbarWidth: 'none' }}>
              <button
                className={`btn btn-sm rounded-pill px-3 bangla-text text-nowrap ${
                  selectedSubjectId === 'all' ? 'btn-primary' : 'btn-outline-secondary'
                }`}
                onClick={() => setSelectedSubjectId('all')}
              >
                সকল বিষয় ({syllabus.length})
              </button>
              {syllabus.map((sub) => (
                <button
                  key={sub.id}
                  className={`btn btn-sm rounded-pill px-3 bangla-text text-nowrap ${
                    String(selectedSubjectId) === String(sub.id) ? 'btn-primary' : 'btn-outline-secondary'
                  }`}
                  onClick={() => setSelectedSubjectId(sub.id)}
                >
                  {sub.name.split(' ')[0]} ({sub.allocated_marks}ম)
                </button>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Syllabus Subject-by-Subject Breakdown */}
      <div className="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h3 className="fw-bold text-dark bangla-text mb-0">
          <i className="bi bi-list-task text-primary me-2"></i>
          বিষয়ভিত্তিক পূর্ণাঙ্গ সিলেবাস ও হাই-ইল্ড টপিকসমূহ
        </h3>
        <span className="text-muted bangla-text small">
          দেখাচ্ছে: {filteredSyllabus.length} টি বিষয়
        </span>
      </div>

      {filteredSyllabus.length === 0 ? (
        <div className="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
          <i className="bi bi-search fs-1 text-muted d-block mb-3"></i>
          <h5 className="fw-bold bangla-text mb-2">কোনো মিল খুঁজে পাওয়া যায়নি</h5>
          <p className="text-muted bangla-text mb-3">"{searchQuery}" এর সাথে মিলে এমন কোনো অধ্যায় বা টপিক নেই।</p>
          <button className="btn btn-outline-primary rounded-pill px-4 bangla-text mx-auto" onClick={() => { setSearchQuery(''); setSelectedSubjectId('all'); }}>
            ফিল্টার রিসেট করুন
          </button>
        </div>
      ) : (
        <div className="d-flex flex-column gap-4">
          {filteredSyllabus.map((sub) => (
            <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white card-hover" key={sub.id}>
              <div className="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div className="d-flex align-items-center gap-3">
                  <div className="p-3 rounded-3 fs-4" style={{ background: '#e0e7ff', color: '#4f46e5' }}>
                    <i className="bi bi-book-half"></i>
                  </div>
                  <div>
                    <h4 className="fw-bold text-dark bangla-text mb-1">
                      {sub.name}
                    </h4>
                    <span className="text-muted small bangla-text">
                      বরাদ্দকৃত নম্বর: <strong className="text-primary fs-6 me-3">{sub.allocated_marks} নম্বর</strong>
                      অধ্যায় সংখ্যা: <strong className="text-dark">{sub.chapters?.length || 0} টি</strong>
                    </span>
                  </div>
                </div>

                <Link
                  to={`/subjects/${sub.slug}`}
                  className="btn btn-outline-primary btn-sm bangla-text rounded-pill px-3 py-2 fw-semibold"
                >
                  বিষয়ভিত্তিক পড়া শুরু করুন <i className="bi bi-arrow-right ms-1"></i>
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
                            <span className="badge bg-white text-muted border small rounded-pill px-2.5 py-1">
                              {chap.topics?.length || 0} টপিক
                            </span>
                          </h6>

                          {chap.topics && chap.topics.length > 0 ? (
                            <div className="d-flex flex-column gap-2">
                              {chap.topics.map((top) => (
                                <div
                                  key={top.id}
                                  className="p-2.5 rounded-3 bg-white border small bangla-text d-flex justify-content-between align-items-center gap-2 flex-wrap"
                                >
                                  <div className="d-flex align-items-center gap-2">
                                    <i className="bi bi-check-circle-fill text-success small"></i>
                                    <Link
                                      to={`/subjects/${sub.slug}?chapter=${chap.id}#topic-${top.id}`}
                                      className="fw-semibold text-dark text-decoration-none hover-primary"
                                    >
                                      {top.name}
                                    </Link>
                                  </div>

                                  <div className="d-flex align-items-center gap-2">
                                    <Link
                                      to={`/public-questions?topic_id=${top.id}`}
                                      className="badge bg-light text-secondary border text-decoration-none px-2 py-1"
                                      title="প্রশ্ন অনুশীলন করুন"
                                    >
                                      <i className="bi bi-question-circle me-1"></i>প্রশ্নমালা
                                    </Link>
                                    {top.is_high_yield && (
                                      <span className="badge-high-yield small py-1">
                                        <i className="bi bi-star-fill me-1"></i>হাই-ইল্ড
                                      </span>
                                    )}
                                  </div>
                                </div>
                              ))}
                            </div>
                          ) : (
                            <div className="text-muted small bangla-text py-2">টপিক তালিকা প্রস্তুতাধীন।</div>
                          )}
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="text-muted small bangla-text py-2">এই বিষয়ের অধ্যায় বিন্যাস প্রস্তুতাধীন।</div>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default ExamSyllabusDetailPage;
