import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const UserDashboardPage = () => {
  const { user } = useAuth();
  const [stats, setStats] = useState({
    questionsCount: 0,
    bookmarksCount: 0,
    progressCount: 0,
    ocrCount: 0,
  });
  const [recentQuestions, setRecentQuestions] = useState([]);
  const [recentBookmarks, setRecentBookmarks] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        const [qRes, bRes, pRes, oRes] = await Promise.all([
          apiClient.get('/my/questions?per_page=5'),
          apiClient.get('/my/bookmarks?per_page=5'),
          apiClient.get('/my/progress?per_page=5'),
          apiClient.get('/my/ocr-documents?per_page=5'),
        ]);

        setStats({
          questionsCount: qRes.data.meta?.total || qRes.data.data?.length || 0,
          bookmarksCount: bRes.data.meta?.total || bRes.data.data?.length || 0,
          progressCount: pRes.data.meta?.total || pRes.data.data?.length || 0,
          ocrCount: oRes.data.meta?.total || oRes.data.data?.length || 0,
        });

        setRecentQuestions(qRes.data.data || []);
        setRecentBookmarks(bRes.data.data || []);
      } catch (err) {
        console.error('Dashboard load error', err);
      } finally {
        setLoading(false);
      }
    };

    fetchDashboardData();
  }, []);

  if (loading) return <LoadingSpinner fullPage text="ড্যাশবোর্ড লোড হচ্ছে..." />;

  return (
    <div className="container py-5">
      {/* Welcome Banner */}
      <div className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
        <div className="row align-items-center gy-3">
          <div className="col-lg-8">
            <span className="badge bg-primary px-3 py-2 rounded-pill bangla-text mb-2">
              পরীক্ষার্থী ড্যাশবোর্ড
            </span>
            <h2 className="fw-bold text-dark bangla-text mb-2">
              স্বাগতম, {user?.name}!
            </h2>
            <p className="text-muted bangla-text mb-0">
              আপনার চাকরি প্রস্তুতির সার্বিক অগ্রগতির সংক্ষিপ্ত বিবরণ ও ব্যক্তিগত নোটবুক।
            </p>
          </div>
          <div className="col-lg-4 text-lg-end">
            <div className="d-flex flex-wrap gap-2 justify-content-lg-end">
              <Link to="/my/questions/create" className="btn btn-primary bangla-text rounded-pill px-4">
                <i className="bi bi-plus-lg me-1"></i> নতুন প্রশ্ন যুক্ত করুন
              </Link>
              <Link to="/my/ocr" className="btn btn-outline-success bangla-text rounded-pill px-4">
                <i className="bi bi-camera me-1"></i> বই স্ক্যান (OCR)
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Metric Cards */}
      <div className="row g-4 mb-5">
        <div className="col-sm-6 col-lg-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
            <div className="card-body d-flex align-items-center gap-3">
              <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                <i className="bi bi-journal-check"></i>
              </div>
              <div>
                <h6 className="text-muted small bangla-text mb-1">ব্যক্তিগত প্রশ্ন ও নোট</h6>
                <h3 className="fw-bold text-dark mb-0">{stats.questionsCount}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-sm-6 col-lg-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
            <div className="card-body d-flex align-items-center gap-3">
              <div className="p-3 bg-warning-subtle text-warning rounded-3 fs-3">
                <i className="bi bi-bookmark-star-fill"></i>
              </div>
              <div>
                <h6 className="text-muted small bangla-text mb-1">সংরক্ষিত বুকমার্ক</h6>
                <h3 className="fw-bold text-dark mb-0">{stats.bookmarksCount}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-sm-6 col-lg-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
            <div className="card-body d-flex align-items-center gap-3">
              <div className="p-3 bg-success-subtle text-success rounded-3 fs-3">
                <i className="bi bi-graph-up-arrow"></i>
              </div>
              <div>
                <h6 className="text-muted small bangla-text mb-1">পড়ার অগ্রগতি</h6>
                <h3 className="fw-bold text-dark mb-0">{stats.progressCount}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-sm-6 col-lg-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
            <div className="card-body d-flex align-items-center gap-3">
              <div className="p-3 bg-danger-subtle text-danger rounded-3 fs-3">
                <i className="bi bi-camera-fill"></i>
              </div>
              <div>
                <h6 className="text-muted small bangla-text mb-1">OCR স্ক্যান ডকুমেন্ট</h6>
                <h3 className="fw-bold text-dark mb-0">{stats.ocrCount}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="row g-4">
        {/* Recent Personal Questions */}
        <div className="col-lg-7">
          <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div className="d-flex justify-content-between align-items-center mb-4">
              <h5 className="fw-bold text-dark bangla-text mb-0">
                <i className="bi bi-journal-text text-primary me-2"></i> সাম্প্রতিক ব্যক্তিগত প্রশ্নসমূহ
              </h5>
              <Link to="/my/questions" className="small text-decoration-none bangla-text">
                সব দেখুন <i className="bi bi-arrow-right"></i>
              </Link>
            </div>

            {recentQuestions.length === 0 ? (
              <p className="text-muted small bangla-text my-4 text-center">
                এখনও কোনো ব্যক্তিগত প্রশ্ন তৈরি করেননি। বই পড়তে পড়তে গুরুত্বপূর্ণ প্রশ্ন লিখে রাখুন!
              </p>
            ) : (
              <div className="list-group list-group-flush">
                {recentQuestions.map((pq) => (
                  <div key={pq.id} className="list-group-item px-0 py-3 border-bottom">
                    <div className="d-flex justify-content-between align-items-start mb-1">
                      <h6 className="fw-bold text-dark bangla-text mb-0">{pq.question}</h6>
                      {pq.source_title && (
                        <span className="badge bg-light text-muted border bangla-text small">
                          {pq.source_title} {pq.source_page ? `(${pq.source_page})` : ''}
                        </span>
                      )}
                    </div>
                    <p className="text-secondary small bangla-text mb-2 line-clamp-2">{pq.answer}</p>
                    <div className="d-flex gap-1 flex-wrap">
                      {pq.tags?.map((t) => (
                        <span key={t.id} className="badge bg-secondary-subtle text-secondary small">
                          #{t.name}
                        </span>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        {/* Bookmarks */}
        <div className="col-lg-5">
          <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div className="d-flex justify-content-between align-items-center mb-4">
              <h5 className="fw-bold text-dark bangla-text mb-0">
                <i className="bi bi-bookmark-fill text-warning me-2"></i> সাম্প্রতিক বুকমার্ক
              </h5>
              <Link to="/my/bookmarks" className="small text-decoration-none bangla-text">
                সব দেখুন <i className="bi bi-arrow-right"></i>
              </Link>
            </div>

            {recentBookmarks.length === 0 ? (
              <p className="text-muted small bangla-text my-4 text-center">
                কোনো স্টাডি গাইড বুকমার্কে রাখা হয়নি।
              </p>
            ) : (
              <div className="list-group list-group-flush">
                {recentBookmarks.map((b) => (
                  <Link
                    key={b.id}
                    to={`/study-guides/${b.study_guide?.slug || b.study_guide_id}`}
                    className="list-group-item list-group-item-action px-0 py-2 border-0 bangla-text"
                  >
                    <div className="d-flex align-items-center gap-2">
                      <i className="bi bi-chevron-right text-primary small"></i>
                      <span className="fw-semibold text-dark line-clamp-1">{b.study_guide?.title}</span>
                    </div>
                  </Link>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserDashboardPage;
