import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';
import Pagination from '../../components/common/Pagination';

const ReadingProgressPage = () => {
  const [progressList, setProgressList] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchProgress = async (page = 1) => {
    setLoading(true);
    try {
      const response = await apiClient.get(`/my/progress?page=${page}`);
      setProgressList(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load progress', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchProgress(1);
  }, []);

  return (
    <div className="container py-5">
      <div className="mb-4">
        <span className="badge bg-success text-white px-3 py-2 rounded-pill bangla-text mb-2">
          ট্র্যাকিং
        </span>
        <h2 className="fw-bold text-dark bangla-text mb-0">পড়াশোনার অগ্রগতি</h2>
        <p className="text-muted bangla-text small mb-0 mt-1">
          কোন অধ্যায় কতটুকু পড়েছেন তা ট্র্যাক করুন এবং অসমাপ্ত বিষয়গুলো দ্রুত শেষ করুন
        </p>
      </div>

      {loading ? (
        <LoadingSpinner text="অগ্রগতি লোড হচ্ছে..." />
      ) : progressList.length === 0 ? (
        <EmptyState
          icon="bi-graph-up"
          title="কোন অগ্রগতির রেকর্ড নেই"
          message="আপনি এখনও কোনো স্টাডি গাইড পড়া শুরু করেননি।"
          actionText="স্টাডি গাইড ব্রাউজ করুন"
          actionLink="/subjects"
        />
      ) : (
        <div className="row g-4">
          {progressList.map((p) => {
            const guide = p.study_guide;
            if (!guide) return null;

            const isCompleted = p.status === 'completed' || p.progress_percent >= 100;

            return (
              <div className="col-md-6" key={p.id}>
                <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                  <div className="d-flex justify-content-between align-items-center mb-3">
                    <span className="badge bg-primary-subtle text-primary bangla-text">
                      {guide.topic?.subject?.name || 'বিষয়'}
                    </span>
                    <span className={`badge ${isCompleted ? 'bg-success' : 'bg-warning text-dark'} bangla-text`}>
                      {isCompleted ? 'সম্পূর্ণ' : 'চলমান'}
                    </span>
                  </div>

                  <h5 className="fw-bold text-dark bangla-text mb-2">{guide.title}</h5>
                  <p className="text-muted small bangla-text mb-3 line-clamp-2">{guide.summary}</p>

                  {/* Progress Bar */}
                  <div className="mb-3">
                    <div className="d-flex justify-content-between text-muted small bangla-text mb-1">
                      <span>পড়ার পরিমাণ</span>
                      <strong>{p.progress_percent}%</strong>
                    </div>
                    <div className="progress" style={{ height: '8px' }}>
                      <div
                        className={`progress-bar ${isCompleted ? 'bg-success' : 'bg-primary'}`}
                        role="progressbar"
                        style={{ width: `${p.progress_percent}%` }}
                        aria-valuenow={p.progress_percent}
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </div>

                  <div className="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                    <span className="small text-muted bangla-text">
                      সর্বশেষ পাঠ:{' '}
                      {p.last_read_at ? new Date(p.last_read_at).toLocaleDateString('bn-BD') : 'আজ'}
                    </span>
                    <Link
                      to={`/study-guides/${guide.slug || guide.id}`}
                      className="btn btn-sm btn-primary bangla-text rounded-pill px-3"
                    >
                      {isCompleted ? 'পুনরায় পড়ুন' : 'পড়া চালিয়ে যান'} <i className="bi bi-arrow-right ms-1"></i>
                    </Link>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}

      <Pagination meta={meta} onPageChange={(p) => fetchProgress(p)} />
    </div>
  );
};

export default ReadingProgressPage;
