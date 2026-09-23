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
      <section className="bg-gradient bg-primary text-white py-5 shadow-sm" style={{ background: 'linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0369a1 100%)' }}>
        <div className="container py-4">
          <div className="row align-items-center gy-4">
            <div className="col-lg-7">
              <span className="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 bangla-text">
                <i className="bi bi-stars me-1"></i> বিসিএস ও সরকারি চাকরি প্রস্তুতি
              </span>
              <h1 className="display-4 fw-bold mb-3 bangla-text">
                চাকরি পরীক্ষার সম্পূর্ণ ও স্মার্ট প্রস্তুতি নিন এক ছাদের নিচে
              </h1>
              <p className="lead opacity-90 mb-4 bangla-text" style={{ fontSize: '1.2rem', lineHeight: '1.8' }}>
                বাংলা, ইংরেজি, গাণিতিক যুক্তি, সাধারণ জ্ঞান ও আইসিটি সহ সকল বিষয়ের অধ্যায়ভিত্তিক স্টাডি গাইড, বিগত সালের প্রশ্নব্যাংক এবং আপনার নিজস্ব প্রশ্ন সংরক্ষণ ও বই থেকে OCR স্ক্যানিং সুবিধা।
              </p>
              <div className="d-flex flex-wrap gap-3">
                <Link to="/exams" className="btn btn-warning btn-lg px-4 py-3 fw-bold bangla-text shadow-sm rounded-3">
                  <i className="bi bi-mortarboard-fill me-2"></i> সরকারি চাকরি সিলেবাস
                </Link>
                <Link to="/previous-questions" className="btn btn-light btn-lg px-4 py-3 bangla-text text-primary fw-bold shadow-sm rounded-3">
                  <i className="bi bi-archive-fill me-2"></i> বিগত সালের প্রশ্নব্যাংক
                </Link>
                <Link to="/model-tests" className="btn btn-outline-light btn-lg px-4 py-3 bangla-text rounded-3">
                  <i className="bi bi-stopwatch-fill me-2"></i> মডেল টেস্ট দিন
                </Link>
              </div>
            </div>

            <div className="col-lg-5">
              <div className="card border-0 shadow-lg rounded-4 overflow-hidden bg-white text-dark p-4">
                <h5 className="fw-bold text-primary bangla-text mb-3">
                  <i className="bi bi-check-circle-fill text-success me-2"></i> PrepareMe যা দিচ্ছে:
                </h5>
                <ul className="list-group list-group-flush bangla-text">
                  <li className="list-group-item px-0 py-2 d-flex align-items-center gap-2 border-0">
                    <span className="badge bg-primary-subtle text-primary p-2 rounded-circle"><i className="bi bi-book"></i></span>
                    <span>বিষয়ভিত্তিক গোছানো অধ্যায় ও স্টাডি গাইড</span>
                  </li>
                  <li className="list-group-item px-0 py-2 d-flex align-items-center gap-2 border-0">
                    <span className="badge bg-success-subtle text-success p-2 rounded-circle"><i className="bi bi-patch-question"></i></span>
                    <span>ব্যাখ্যাসহ বিগত বিসিএস ও ব্যাংক নিয়োগ পরীক্ষার প্রশ্ন</span>
                  </li>
                  <li className="list-group-item px-0 py-2 d-flex align-items-center gap-2 border-0">
                    <span className="badge bg-info-subtle text-info p-2 rounded-circle"><i className="bi bi-journal-text"></i></span>
                    <span>সম্পূর্ণ ব্যক্তিগত ও গোপনীয় ডিজিটাল নোটবুক</span>
                  </li>
                  <li className="list-group-item px-0 py-2 d-flex align-items-center gap-2 border-0">
                    <span className="badge bg-danger-subtle text-danger p-2 rounded-circle"><i className="bi bi-camera"></i></span>
                    <span>বইয়ের পৃষ্ঠার ছবি তুলে টেক্সট বানানোর OCR প্রযুক্তি</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Subjects Section */}
      <section className="py-5">
        <div className="container py-2">
          <div className="text-center mb-5">
            <span className="text-primary fw-bold text-uppercase bangla-text">প্রস্তুতির সিলেবাস</span>
            <h2 className="fw-bold text-dark bangla-text mt-1">বিষয়ভিত্তিক প্রস্তুতি শুরু করুন</h2>
            <p className="text-muted bangla-text mx-auto" style={{ maxWidth: '600px' }}>
              সকল প্রধান বিষয়ের পূর্ণাঙ্গ বিষয়ভিত্তিক আলোচনা, টপিক ও প্রশ্নমালা
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
                        <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-4">
                          <i className="bi bi-mortarboard-fill"></i>
                        </div>
                        <span className="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">
                          {sub.topics_count || 0} টি টপিক
                        </span>
                      </div>
                      <h4 className="card-title fw-bold text-dark bangla-text mb-2">
                        {sub.name}
                      </h4>
                      <p className="card-text text-muted bangla-text flex-grow-1 small line-clamp-3">
                        {sub.description || 'চাকরি পরীক্ষার গুরুত্বপূর্ণ অধ্যায় ও প্রশ্নোত্তর।'}
                      </p>
                      <Link
                        to={`/subjects/${sub.slug || sub.id}`}
                        className="btn btn-outline-primary bangla-text w-100 rounded-pill mt-3"
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
              <div className="p-4 rounded-4 bg-light border text-center h-100">
                <div className="text-primary fs-1 mb-3">
                  <i className="bi bi-journal-code"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">ব্যক্তিগত প্রশ্নোত্তর ডায়েরি</h4>
                <p className="text-muted bangla-text small mb-0">
                  নিজে যে প্রশ্নগুলো শিখছেন তা বইয়ের নাম ও পৃষ্ঠা নম্বর সহ নিরাপদে সংরক্ষণ করুন। এটি সম্পূর্ণ আপনার ব্যক্তিগত।
                </p>
              </div>
            </div>

            <div className="col-md-4">
              <div className="p-4 rounded-4 bg-light border text-center h-100">
                <div className="text-success fs-1 mb-3">
                  <i className="bi bi-camera-reels"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">বাংলা ও ইংরেজি OCR স্ক্যানিং</h4>
                <p className="text-muted bangla-text small mb-0">
                  বইয়ের পাতা থেকে দ্রুত টেক্সট কনভার্ট করুন, এডিট করে নিজের নোটবুকে সরাসরি রূপান্তর করুন। টাইপিংয়ের সময় বাঁচান।
                </p>
              </div>
            </div>

            <div className="col-md-4">
              <div className="p-4 rounded-4 bg-light border text-center h-100">
                <div className="text-warning fs-1 mb-3">
                  <i className="bi bi-bookmark-check"></i>
                </div>
                <h4 className="fw-bold bangla-text mb-2">অগ্রগতি ও বুকমার্ক ট্র্যাকিং</h4>
                <p className="text-muted bangla-text small mb-0">
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
