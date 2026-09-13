import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const AdminDashboardPage = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const response = await apiClient.get('/admin/dashboard');
        setStats(response.data.data);
      } catch (err) {
        console.error('Failed to load admin stats', err);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  if (loading) return <LoadingSpinner fullPage text="এডমিন ড্যাশবোর্ড লোড হচ্ছে..." />;

  const { users, content, ocr } = stats || {};

  return (
    <div>
      {/* Top Header */}
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">সিস্টেম কন্ট্রোল ড্যাশবোর্ড</h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            PrepareMe.com প্ল্যাটফর্মের সামগ্রিক তথ্য, কনটেন্ট পরিসংখ্যান ও মনিটরিং
          </p>
        </div>
        <div className="d-flex gap-2">
          <Link to="/admin/study-guides/create" className="btn btn-primary bangla-text rounded-pill px-3">
            <i className="bi bi-plus-lg me-1"></i> নতুন স্টাডি গাইড
          </Link>
          <Link to="/admin/public-questions/create" className="btn btn-outline-primary bangla-text rounded-pill px-3">
            <i className="bi bi-plus-lg me-1"></i> নতুন পাবলিক প্রশ্ন
          </Link>
        </div>
      </div>

      {/* User Stats Row */}
      <h6 className="fw-bold text-secondary text-uppercase bangla-text small mb-3">ব্যবহারকারী হিসাব</h6>
      <div className="row g-3 mb-4">
        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                <i className="bi bi-people-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">মোট পরীক্ষার্থী</span>
                <h3 className="fw-bold text-dark mb-0">{users?.total || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-success-subtle text-success rounded-3 fs-3">
                <i className="bi bi-person-check-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">সক্রিয় অ্যাকাউন্ট</span>
                <h3 className="fw-bold text-dark mb-0">{users?.active || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-danger-subtle text-danger rounded-3 fs-3">
                <i className="bi bi-person-x-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">স্থগিত / ব্লকড</span>
                <h3 className="fw-bold text-dark mb-0">{users?.blocked || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-warning-subtle text-warning-emphasis rounded-3 fs-3">
                <i className="bi bi-shield-lock-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">এডমিন ইউজার</span>
                <h3 className="fw-bold text-dark mb-0">{users?.admins || 0}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Content Stats Row */}
      <h6 className="fw-bold text-secondary text-uppercase bangla-text small mb-3">পাঠ্যক্রম ও কনটেন্ট</h6>
      <div className="row g-3 mb-4">
        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-info-subtle text-info rounded-3 fs-3">
                <i className="bi bi-collection-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">মোট বিষয় (Subjects)</span>
                <h3 className="fw-bold text-dark mb-0">{content?.subjects || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-info-subtle text-info rounded-3 fs-3">
                <i className="bi bi-diagram-3-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">টপিক ও সাব-টপিক</span>
                <h3 className="fw-bold text-dark mb-0">{content?.topics || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                <i className="bi bi-file-earmark-richtext-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">প্রকাশিত স্টাডি গাইড</span>
                <h3 className="fw-bold text-dark mb-0">{content?.study_guides?.published || 0}</h3>
                <span className="small text-muted bangla-text">ড্রাফট: {content?.study_guides?.draft || 0}</span>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-success-subtle text-success rounded-3 fs-3">
                <i className="bi bi-patch-question-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">পাবলিক প্রশ্নব্যাংক</span>
                <h3 className="fw-bold text-dark mb-0">{content?.public_questions?.published || 0}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* OCR Engine Monitoring */}
      <h6 className="fw-bold text-secondary text-uppercase bangla-text small mb-3">OCR প্রসেসিং স্বাস্থ্য</h6>
      <div className="row g-3">
        <div className="col-md-4">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-secondary-subtle text-secondary rounded-3 fs-3">
                <i className="bi bi-camera-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">মোট আপলোড</span>
                <h3 className="fw-bold text-dark mb-0">{ocr?.total || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-4">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-success-subtle text-success rounded-3 fs-3">
                <i className="bi bi-check2-all"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">সফলভাবে সম্পন্ন</span>
                <h3 className="fw-bold text-dark mb-0">{ocr?.completed || 0}</h3>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-4">
          <div className="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div className="d-flex align-items-center gap-3">
              <div className="p-3 bg-danger-subtle text-danger rounded-3 fs-3">
                <i className="bi bi-exclamation-triangle-fill"></i>
              </div>
              <div>
                <span className="text-muted small bangla-text">ব্যর্থ / এরর</span>
                <h3 className="fw-bold text-dark mb-0">{ocr?.failed || 0}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboardPage;
