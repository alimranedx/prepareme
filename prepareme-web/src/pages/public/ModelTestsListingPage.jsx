import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const ModelTestsListingPage = () => {
  const [modelTests, setModelTests] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filterType, setFilterType] = useState('all');

  useEffect(() => {
    let isMounted = true;
    const fetchModelTests = async () => {
      setLoading(true);
      try {
        const res = await apiClient.get('/model-tests');
        if (isMounted) {
          setModelTests(res.data.data || []);
        }
      } catch (err) {
        console.error('Failed to load model tests', err);
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    fetchModelTests();
    return () => { isMounted = false; };
  }, []);

  const filteredTests = modelTests.filter((test) => {
    if (filterType === 'all') return true;
    return test.model_test_type === filterType;
  });

  return (
    <div className="container py-4 py-lg-5">
      {/* Header */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
        <div className="row align-items-center gy-3">
          <div className="col-lg-8">
            <span className="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-semibold mb-3">
              <i className="bi bi-stopwatch-fill me-1"></i>লাইভ এক্সাম সিমুলেটর
            </span>
            <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
              অনলাইন মডেল টেস্ট ও বিসিএস মক এক্সাম
            </h1>
            <p className="text-muted bangla-text fs-6 mb-0" style={{ lineHeight: '1.8' }}>
              নেগেটিভ মার্কিং সহ রিয়েল পরীক্ষার পরিবেশে বিসিএস প্রিলিমিনারি ও ব্যাংক নিয়োগের বিষয়ভিত্তিক এবং পূর্ণাঙ্গ ২০০ নম্বরের মডেল টেস্ট দিন। তাৎক্ষণিক ফলাফল এবং ভুল প্রশ্ন রিভিশন সুবিধা।
            </p>
          </div>
          <div className="col-lg-4 text-lg-end">
            <div className="p-3 rounded-4 bg-light border text-start">
              <div className="d-flex align-items-center gap-2 mb-1">
                <i className="bi bi-shield-check text-success fs-5"></i>
                <span className="bangla-text small fw-bold text-dark">নেগেটিভ মার্কিং সিস্টেম</span>
              </div>
              <span className="bangla-text text-muted small">
                বিসিএস পরীক্ষার মতো ভুল উত্তরে ০.৫০ বা ০.২৫ নম্বর কর্তন করা হয়।
              </span>
            </div>
          </div>
        </div>

        {/* Filter Pills */}
        <div className="mt-4 pt-3 border-top d-flex gap-2 flex-wrap">
          <button
            type="button"
            className={`btn btn-sm bangla-text rounded-pill px-3 py-2 ${filterType === 'all' ? 'btn-primary' : 'btn-outline-secondary'}`}
            onClick={() => setFilterType('all')}
          >
            সকল মডেল টেস্ট ({modelTests.length})
          </button>
          <button
            type="button"
            className={`btn btn-sm bangla-text rounded-pill px-3 py-2 ${filterType === 'full_exam' ? 'btn-primary' : 'btn-outline-secondary'}`}
            onClick={() => setFilterType('full_exam')}
          >
            পূর্ণাঙ্গ পরীক্ষা (Full Mock)
          </button>
          <button
            type="button"
            className={`btn btn-sm bangla-text rounded-pill px-3 py-2 ${filterType === 'subject_wise' ? 'btn-primary' : 'btn-outline-secondary'}`}
            onClick={() => setFilterType('subject_wise')}
          >
            বিষয়ভিত্তিক টেস্ট (Subject-wise)
          </button>
        </div>
      </div>

      {/* Test List */}
      {loading ? (
        <LoadingSpinner text="মডেল টেস্ট লোড করা হচ্ছে..." />
      ) : (
        <div className="row g-4">
          {filteredTests.map((test) => (
            <div className="col-lg-6" key={test.id}>
              <div className="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white d-flex flex-column justify-content-between hover-lift">
                <div>
                  <div className="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <span className="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                      {test.exam ? test.exam.name : (test.subject ? test.subject.name : 'টেস্ট')}
                    </span>
                    <span className="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small">
                      নেগেটিভ মার্ক: -{test.negative_marking_rate}
                    </span>
                  </div>

                  <h3 className="fw-bold text-dark bangla-text fs-4 mb-2">
                    {test.title}
                  </h3>

                  <p className="text-muted bangla-text small mb-4" style={{ lineHeight: '1.7' }}>
                    {test.description}
                  </p>

                  <div className="row g-2 text-center mb-4">
                    <div className="col-4">
                      <div className="p-2 rounded-3 bg-light border">
                        <span className="text-muted small d-block bangla-text">প্রশ্ন সংখ্যা</span>
                        <strong className="text-dark fs-6 bangla-text">{test.total_questions} টি</strong>
                      </div>
                    </div>
                    <div className="col-4">
                      <div className="p-2 rounded-3 bg-light border">
                        <span className="text-muted small d-block bangla-text">সময়</span>
                        <strong className="text-dark fs-6 bangla-text">{test.duration_minutes} মিনিট</strong>
                      </div>
                    </div>
                    <div className="col-4">
                      <div className="p-2 rounded-3 bg-light border">
                        <span className="text-muted small d-block bangla-text">পূর্ণমান</span>
                        <strong className="text-primary fs-6 bangla-text">{test.total_marks} নম্বর</strong>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="pt-3 border-top">
                  <Link
                    to={`/model-tests/${test.slug}/take`}
                    className="btn btn-warning fw-bold bangla-text rounded-pill w-100 py-2 shadow-sm"
                  >
                    <i className="bi bi-play-circle-fill me-2"></i>মডেল টেস্ট শুরু করুন
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

export default ModelTestsListingPage;
