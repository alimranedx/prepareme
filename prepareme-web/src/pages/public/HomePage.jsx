import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import { useAuth } from '../../context/AuthContext';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const HomePage = () => {
  const { isAuthenticated } = useAuth();
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {
        console.error('Failed to load subjects', err);
      } finally {
        setLoading(false);
      }
    };
    fetchSubjects();
  }, []);

  return (
    <div>
      {/* Hero Section */}
      <section className="hero-wrapper shadow-sm">
        <div className="container py-3 position-relative" style={{ zIndex: 2 }}>
          <div className="row align-items-center gy-5">
            <div className="col-lg-7">
              <span className="hero-badge mb-3 bangla-text">
                <i className="bi bi-stars text-warning"></i> বিসিএস ও সরকারি চাকরি প্রস্তুতি
              </span>
              <h1 className="display-4 fw-extrabold mb-3 bangla-text text-white" style={{ fontWeight: 800 }}>
                চাকরি পরীক্ষার <span className="text-gradient-primary">সম্পূর্ণ ও স্মার্ট</span> প্রস্তুতি নিন এক ছাদের নিচে
              </h1>
              <p className="lead mb-4 bangla-text" style={{ fontSize: '1.2rem', lineHeight: '1.85', color: '#cbd5e1' }}>
                বাংলা, ইংরেজি, গাণিতিক যুক্তি, সাধারণ জ্ঞান ও আইসিটি সহ সকল বিষয়ের অধ্যায়ভিত্তিক স্টাডি গাইড, বিগত সালের প্রশ্নব্যাংক এবং আপনার নিজস্ব প্রশ্ন সংরক্ষণ ও বই থেকে OCR স্ক্যানিং সুবিধা।
              </p>
              <div className="d-flex flex-wrap gap-3">
                <Link to="/exams" className="btn btn-hero-primary btn-lg px-4 py-3 bangla-text rounded-3">
                  <i className="bi bi-mortarboard-fill me-2"></i> সরকারি চাকরি সিলেবাস
                </Link>
                <Link to="/previous-questions" className="btn btn-hero-emerald btn-lg px-4 py-3 bangla-text rounded-3">
                  <i className="bi bi-archive-fill me-2"></i> বিগত সালের প্রশ্নব্যাংক
                </Link>
                <Link to="/model-tests" className="btn btn-hero-glass btn-lg px-4 py-3 bangla-text rounded-3">
                  <i className="bi bi-stopwatch-fill me-2"></i> মডেল টেস্ট দিন
                </Link>
              </div>
            </div>

            <div className="col-lg-5">
              <div className="hero-card-glass">
                <h5 className="fw-bold text-white bangla-text mb-4 d-flex align-items-center gap-2">
                  <span className="icon-avatar" style={{ background: 'rgba(16, 185, 129, 0.2)', color: '#34d399' }}>
                    <i className="bi bi-check-circle-fill"></i>
                  </span>
                  <span>PrepareMe যা দিচ্ছে:</span>
                </h5>
                <ul className="list-group bangla-text">
                  <li className="list-group-item px-0 py-25 d-flex align-items-center gap-3">
                    <span className="icon-avatar" style={{ background: 'rgba(99, 102, 241, 0.2)', color: '#818cf8' }}>
                      <i className="bi bi-book"></i>
                    </span>
                    <span className="fw-medium">বিষয়ভিত্তিক গোছানো অধ্যায় ও স্টাডি গাইড</span>
                  </li>
                  <li className="list-group-item px-0 py-25 d-flex align-items-center gap-3">
                    <span className="icon-avatar" style={{ background: 'rgba(16, 185, 129, 0.2)', color: '#34d399' }}>
                      <i className="bi bi-patch-question"></i>
                    </span>
                    <span className="fw-medium">ব্যাখ্যাসহ বিগত বিসিএস ও ব্যাংক নিয়োগ পরীক্ষার প্রশ্ন</span>
                  </li>
                  <li className="list-group-item px-0 py-25 d-flex align-items-center gap-3">
                    <span className="icon-avatar" style={{ background: 'rgba(56, 189, 248, 0.2)', color: '#38bdf8' }}>
                      <i className="bi bi-journal-text"></i>
                    </span>
                    <span className="fw-medium">সম্পূর্ণ ব্যক্তিগত ও গোপনীয় ডিজিটাল নোটবুক</span>
                  </li>
                  <li className="list-group-item px-0 py-25 d-flex align-items-center gap-3">
                    <span className="icon-avatar" style={{ background: 'rgba(244, 114, 182, 0.2)', color: '#f472b6' }}>
                      <i className="bi bi-camera"></i>
                    </span>
                    <span className="fw-medium">বইয়ের পৃষ্ঠার ছবি তুলে টেক্সট বানানোর OCR প্রযুক্তি</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Subjects Section */}
      <section className="py-5" style={{ background: '#f8fafc' }}>
        <div className="container py-4">
          <div className="text-center mb-5">
            <span className="badge bg-indigo-subtle text-primary fw-bold text-uppercase bangla-text px-3 py-2 rounded-pill" style={{ background: '#e0e7ff', color: '#4f46e5' }}>
              প্রস্তুতির সিলেবাস
            </span>
            <h2 className="fw-extrabold text-dark bangla-text mt-2 display-6">বিষয়ভিত্তিক প্রস্তুতি শুরু করুন</h2>
            <p className="text-muted bangla-text mx-auto" style={{ maxWidth: '620px', fontSize: '1.05rem' }}>
              সকল প্রধান বিষয়ের পূর্ণাঙ্গ বিষয়ভিত্তিক আলোচনা, টপিক ও সমৃদ্ধ প্রশ্নমালা
            </p>
          </div>

          {loading ? (
            <LoadingSpinner text="বিষয়সমূহ লোড হচ্ছে..." />
          ) : (
            <div className="row g-4">
              {subjects.map((sub) => (
                <div className="col-md-6 col-lg-4" key={sub.id}>
                  <div className="card h-100 border-0 shadow-sm rounded-4 card-hover p-3">
                    <div className="card-body d-flex flex-column">
                      <div className="d-flex align-items-center justify-content-between mb-3">
                        <div className="p-3 rounded-3 fs-4" style={{ background: '#e0e7ff', color: '#4f46e5' }}>
                          <i className="bi bi-mortarboard-fill"></i>
                        </div>
                        <span className="badge px-3 py-2 rounded-pill" style={{ background: '#f1f5f9', color: '#475569', border: '1px solid #e2e8f0' }}>
                          {sub.topics_count || 0} টি টপিক
                        </span>
                      </div>
                      <h4 className="card-title fw-bold text-dark bangla-text mb-2">
                        {sub.name}
                      </h4>
                      <p className="card-text text-muted bangla-text flex-grow-1 small line-clamp-3" style={{ lineHeight: 1.6 }}>
                        {sub.description || 'চাকরি পরীক্ষার গুরুত্বপূর্ণ অধ্যায় ও প্রশ্নোত্তর।'}
                      </p>
                      <Link
                        to={`/subjects/${sub.slug || sub.id}`}
                        className="btn btn-outline-primary bangla-text w-100 rounded-pill mt-3 py-2"
                      >
                        অধ্যায়সমূহ দেখুন <i className="bi bi-arrow-right ms-1"></i>
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Feature Highlights Section */}
      <section className="py-5 bg-white border-top">
        <div className="container py-3">
          <div className="row g-4 align-items-center">
            <div className="col-md-4">
              <div className="feature-card h-100">
                <div className="feature-icon-wrapper" style={{ background: '#e0e7ff', color: '#4f46e5' }}>
                  <i className="bi bi-journal-code"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">ব্যক্তিগত প্রশ্নোত্তর ডায়েরি</h4>
                <p className="text-muted bangla-text small mb-0" style={{ lineHeight: 1.6 }}>
                  নিজে যে প্রশ্নগুলো শিখছেন তা বইয়ের নাম ও পৃষ্ঠা নম্বর সহ নিরাপদে সংরক্ষণ করুন। এটি সম্পূর্ণ আপনার ব্যক্তিগত।
                </p>
              </div>
            </div>

            <div className="col-md-4">
              <div className="feature-card h-100">
                <div className="feature-icon-wrapper" style={{ background: '#d1fae5', color: '#10b981' }}>
                  <i className="bi bi-camera-reels"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">বাংলা ও ইংরেজি OCR স্ক্যানিং</h4>
                <p className="text-muted bangla-text small mb-0" style={{ lineHeight: 1.6 }}>
                  বইয়ের পাতা থেকে দ্রুত টেক্সট কনভার্ট করুন, এডিট করে নিজের নোটবুকে সরাসরি রূপান্তর করুন। টাইপিংয়ের সময় বাঁচান।
                </p>
              </div>
            </div>

            <div className="col-md-4">
              <div className="feature-card h-100">
                <div className="feature-icon-wrapper" style={{ background: '#fef3c7', color: '#d97706' }}>
                  <i className="bi bi-bookmark-check"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">অগ্রগতি ও বুকমার্ক ট্র্যাকিং</h4>
                <p className="text-muted bangla-text small mb-0" style={{ lineHeight: 1.6 }}>
                  কোন অধ্যায় কতটুকু পড়া হলো তা ট্র্যাক করুন এবং প্রিয় স্টাডি গাইডগুলো বুকমার্কে রেখে যেকোনো সময় দ্রুত পড়ুন।
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default HomePage;
