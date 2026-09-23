import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const ExamsHubPage = () => {
  const [exams, setExams] = useState([]);
  const [loading, setLoading] = useState(true);
  const [activeCategory, setActiveCategory] = useState('all');

  useEffect(() => {
    let isMounted = true;
    const fetchExams = async () => {
      setLoading(true);
      try {
        const res = await apiClient.get('/exams');
        if (isMounted) {
          setExams(res.data.data || []);
        }
      } catch (err) {
        console.error('Failed to load exams', err);
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    fetchExams();
    return () => { isMounted = false; };
  }, []);

  const categories = [
    { id: 'all', label: 'সকল চাকরি পরীক্ষা', icon: 'bi-grid-fill' },
    { id: 'bcs', label: 'বিসিএস (BCS)', icon: 'bi-award-fill' },
    { id: 'bank', label: 'ব্যাংক নিয়োগ', icon: 'bi-bank' },
    { id: 'primary', label: 'প্রাইমারি শিক্ষক', icon: 'bi-mortarboard-fill' },
    { id: 'ntrca', label: 'শিক্ষক নিবন্ধন (NTRCA)', icon: 'bi-card-checklist' },
  ];

  const filteredExams = exams.filter((exam) => {
    if (activeCategory === 'all') return true;
    return exam.category === activeCategory;
  });

  return (
    <div className="container py-4 py-lg-5">
      {/* Header */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
        <div className="row align-items-center gy-4">
          <div className="col-lg-8">
            <span className="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold mb-3">
              <i className="bi bi-mortarboard-fill me-1"></i>সরকারি ও ব্যাংক চাকরি পোর্টাল
            </span>
            <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
              পরীক্ষাভিত্তিক সিলেবাস ও নম্বর বণ্টন
            </h1>
            <p className="text-muted bangla-text fs-6 mb-0" style={{ lineHeight: '1.8' }}>
              বিসিএস, বাংলাদেশ ব্যাংক, প্রাইমারি এবং শিক্ষক নিবন্ধন পরীক্ষার অফিসিয়াল মানবণ্টন, বিষয়ভিত্তিক সিলেবাস এবং প্রতিটি বিষয়ের গুরুত্বপূর্ণ অধ্যায়সমূহের পূর্ণাঙ্গ গাইডলাইন।
            </p>
          </div>
          <div className="col-lg-4 text-lg-end">
            <Link to="/model-tests" className="btn btn-warning fw-bold bangla-text rounded-pill px-4 py-2 shadow-sm">
              <i className="bi bi-stopwatch-fill me-2"></i>মডেল টেস্ট সিমুলেটর
            </Link>
          </div>
        </div>

        {/* Category filters */}
        <div className="mt-4 pt-3 border-top d-flex gap-2 flex-wrap">
          {categories.map((cat) => (
            <button
              key={cat.id}
              type="button"
              className={`btn btn-sm bangla-text rounded-pill px-3 py-2 ${activeCategory === cat.id ? 'btn-primary' : 'btn-outline-secondary'}`}
              onClick={() => setActiveCategory(cat.id)}
            >
              <i className={`bi ${cat.icon} me-1`}></i>
              {cat.label}
            </button>
          ))}
        </div>
      </div>

      {/* Content */}
      {loading ? (
        <LoadingSpinner text="সিলেবাস লোড হচ্ছে..." />
      ) : (
        <div className="row g-4">
          {filteredExams.map((exam) => (
            <div className="col-lg-6" key={exam.id}>
              <div className="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white d-flex flex-column justify-content-between hover-lift">
                <div>
                  <div className="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <span className="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                      {exam.category?.toUpperCase() || 'EXAM'}
                    </span>
                    <div className="d-flex gap-2">
                      <span className="badge bg-light text-dark border px-2 py-1 small">
                        <i className="bi bi-award-fill text-warning me-1"></i>
                        পূর্ণমান: {exam.total_marks}
                      </span>
                      <span className="badge bg-light text-dark border px-2 py-1 small">
                        <i className="bi bi-clock-fill text-info me-1"></i>
                        {exam.duration_minutes} মিনিট
                      </span>
                    </div>
                  </div>

                  <h3 className="fw-bold text-dark bangla-text fs-4 mb-2">
                    {exam.name}
                  </h3>

                  <p className="text-muted bangla-text small mb-4" style={{ lineHeight: '1.7' }}>
                    {exam.description}
                  </p>

                  <div className="p-3 rounded-3 bg-light border mb-4">
                    <div className="d-flex justify-content-between align-items-center mb-2">
                      <span className="bangla-text small fw-bold text-dark">
                        <i className="bi bi-diagram-3-fill text-primary me-1"></i>
                        অন্তর্ভুক্ত বিষয়সমূহ ({exam.subjects_count || 0} টি)
                      </span>
                      <span className="bangla-text small text-muted">
                        বিগত প্রশ্নপত্র: {exam.sources_count || 0} টি
                      </span>
                    </div>
                  </div>
                </div>

                <div className="d-flex gap-2 pt-3 border-top">
                  <Link
                    to={`/exams/${exam.slug}`}
                    className="btn btn-primary bangla-text rounded-pill px-4 flex-grow-1"
                  >
                    পূর্ণাঙ্গ সিলেবাস দেখুন <i className="bi bi-arrow-right ms-1"></i>
                  </Link>
                  <Link
                    to={`/previous-questions?exam_id=${exam.id}`}
                    className="btn btn-outline-secondary bangla-text rounded-pill px-3"
                    title="এই পরীক্ষার বিগত সালের প্রশ্ন"
                  >
                    বিগত প্রশ্ন
                  </Link>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default ExamsHubPage;
