import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';
import Pagination from '../../components/common/Pagination';
import AlertMessage from '../../components/common/AlertMessage';

const BookmarksPage = () => {
  const [bookmarks, setBookmarks] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [alert, setAlert] = useState(null);

  const fetchBookmarks = async (page = 1) => {
    setLoading(true);
    try {
      const response = await apiClient.get(`/my/bookmarks?page=${page}`);
      setBookmarks(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load bookmarks', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchBookmarks(1);
  }, []);

  const handleRemoveBookmark = async (studyGuideId) => {
    try {
      await apiClient.delete(`/my/bookmarks/${studyGuideId}`);
      setAlert({ type: 'success', message: 'বুকমার্ক সফলভাবে মুছে ফেলা হয়েছে।' });
      fetchBookmarks(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'বুকমার্ক অপসারণে সমস্যা হয়েছে।' });
    }
  };

  return (
    <div className="container py-5">
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <span className="badge bg-warning text-dark px-3 py-2 rounded-pill bangla-text mb-2">
            সংরক্ষিত
          </span>
          <h2 className="fw-bold text-dark bangla-text mb-0">আমার বুকমার্কসমূহ</h2>
          <p className="text-muted bangla-text small mb-0 mt-1">
            যে স্টাডি গাইডগুলো আপনি পরবর্তীতে দ্রুত পড়ার জন্য সংরক্ষণ করে রেখেছেন
          </p>
        </div>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} onClose={() => setAlert(null)} />

      {loading ? (
        <LoadingSpinner text="বুকমার্কসমূহ লোড হচ্ছে..." />
      ) : bookmarks.length === 0 ? (
        <EmptyState
          icon="bi-bookmark-x"
          title="কোন বুকমার্ক পাওয়া যায়নি"
          message="আপনি এখনও কোনো স্টাডি গাইড বুকমার্কে সংরক্ষণ করেননি। পড়তে পড়তে প্রয়োজনীয় গাইড বুকমার্কে রাখুন!"
          actionText="স্টাডি গাইড ব্রাউজ করুন"
          actionLink="/subjects"
        />
      ) : (
        <div className="row g-4">
          {bookmarks.map((b) => {
            const guide = b.study_guide;
            if (!guide) return null;

            return (
              <div className="col-md-6 col-lg-4" key={b.id}>
                <div className="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column">
                  <div className="d-flex justify-content-between align-items-start mb-2">
                    <span className="badge bg-primary-subtle text-primary bangla-text">
                      {guide.topic?.subject?.name || 'বিষয়'}
                    </span>
                    <button
                      type="button"
                      className="btn btn-sm text-danger p-0"
                      title="বুকমার্ক থেকে সরান"
                      onClick={() => handleRemoveBookmark(guide.id)}
                    >
                      <i className="bi bi-bookmark-x-fill fs-5"></i>
                    </button>
                  </div>

                  <h5 className="fw-bold text-dark bangla-text mb-2 line-clamp-2">
                    {guide.title}
                  </h5>

                  <p className="text-muted small bangla-text flex-grow-1 line-clamp-3">
                    {guide.summary || guide.content?.substring(0, 120)}...
                  </p>

                  <div className="mt-3 pt-3 border-top">
                    <Link
                      to={`/study-guides/${guide.slug || guide.id}`}
                      className="btn btn-outline-primary bangla-text w-100 rounded-pill"
                    >
                      পড়া শুরু করুন <i className="bi bi-arrow-right ms-1"></i>
                    </Link>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}

      <Pagination meta={meta} onPageChange={(p) => fetchBookmarks(p)} />
    </div>
  );
};

export default BookmarksPage;
