import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import ConfirmModal from '../../components/common/ConfirmModal';
import Pagination from '../../components/common/Pagination';

const StudyGuideManagementPage = () => {
  const [guides, setGuides] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [statusFilter, setStatusFilter] = useState('');
  const [search, setSearch] = useState('');
  const [alert, setAlert] = useState(null);

  // Delete State
  const [deleteId, setDeleteId] = useState(null);
  const [deleting, setDeleting] = useState(false);

  const fetchGuides = async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (statusFilter) params.append('status', statusFilter);
      if (search) params.append('search', search);
      params.append('page', page);

      const response = await apiClient.get(`/admin/study-guides?${params.toString()}`);
      setGuides(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load study guides', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchGuides(1);
  }, [statusFilter]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchGuides(1);
  };

  const handlePublish = async (guide) => {
    try {
      await apiClient.post(`/admin/study-guides/${guide.id}/publish`);
      setAlert({ type: 'success', message: `'${guide.title}' সফলভাবে প্রকাশিত হয়েছে।` });
      fetchGuides(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'প্রকাশনা ব্যর্থ হয়েছে।' });
    }
  };

  const handleArchive = async (guide) => {
    try {
      await apiClient.post(`/admin/study-guides/${guide.id}/archive`);
      setAlert({ type: 'info', message: `'${guide.title}' আর্কাইভ করা হয়েছে।` });
      fetchGuides(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'আর্কাইভ ব্যর্থ হয়েছে।' });
    }
  };

  const handleDeleteConfirm = async () => {
    if (!deleteId) return;
    setDeleting(true);
    try {
      await apiClient.delete(`/admin/study-guides/${deleteId}`);
      setAlert({ type: 'success', message: 'স্টাডি গাইডটি মুছে ফেলা হয়েছে।' });
      setDeleteId(null);
      fetchGuides(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'ডিলিট করতে সমস্যা হয়েছে।' });
    } finally {
      setDeleting(false);
    }
  };

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">স্টাডি গাইড ও কন্টেন্ট ব্যবস্থাপনা</h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            চাকরি প্রস্তুতির অধ্যায়ভিত্তিক স্টাডি গাইড তৈরি, এডিট ও প্রকাশনা নিয়ন্ত্রণ
          </p>
        </div>
        <Link to="/admin/study-guides/create" className="btn btn-primary bangla-text rounded-pill px-4">
          <i className="bi bi-plus-lg me-1"></i> নতুন গাইড তৈরি করুন
        </Link>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} onClose={() => setAlert(null)} />

      {/* Filter Bar */}
      <div className="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div className="row g-3 align-items-center">
          <div className="col-md-3">
            <select
              className="form-select bangla-text"
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
            >
              <option value="">সকল স্ট্যাটাস</option>
              <option value="published">প্রকাশিত (Published)</option>
              <option value="draft">ড্রাফট (Draft)</option>
              <option value="archived">আর্কাইভ (Archived)</option>
            </select>
          </div>

          <div className="col-md-9">
            <form onSubmit={handleSearchSubmit}>
              <div className="input-group">
                <input
                  type="text"
                  className="form-control bangla-text"
                  placeholder="শিরোনাম বা বিষয়বস্তু লিখে খুঁজুন..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                />
                <button type="submit" className="btn btn-primary bangla-text px-4">
                  <i className="bi bi-search me-1"></i> খুঁজুন
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {/* Guides Table */}
      <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        {loading ? (
          <div className="p-5">
            <LoadingSpinner text="স্টাডি গাইড লোড হচ্ছে..." />
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 bangla-text">
              <thead className="table-light">
                <tr>
                  <th className="ps-4">আইডি</th>
                  <th>শিরোনাম</th>
                  <th>বিষয় ও টপিক</th>
                  <th>সেকশন</th>
                  <th>স্ট্যাটাস</th>
                  <th>প্রকাশনার তারিখ</th>
                  <th className="text-end pe-4">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                {guides.map((g) => (
                  <tr key={g.id}>
                    <td className="ps-4 text-muted small">#{g.id}</td>
                    <td>
                      <div className="fw-bold text-dark line-clamp-1">{g.title}</div>
                      <div className="small text-muted line-clamp-1">{g.summary}</div>
                    </td>
                    <td>
                      <span className="badge bg-primary-subtle text-primary mb-1 d-block" style={{ width: 'fit-content' }}>
                        {g.topic?.subject?.name || 'বিষয়'}
                      </span>
                      <span className="small text-muted">{g.topic?.name}</span>
                    </td>
                    <td>
                      <span className="badge bg-light text-dark border">
                        {g.sections?.length || 0} টি সেকশন
                      </span>
                    </td>
                    <td>
                      <span className={`badge ${g.status === 'published' ? 'bg-success' : g.status === 'draft' ? 'bg-warning text-dark' : 'bg-secondary'}`}>
                        {g.status === 'published' ? 'প্রকাশিত' : g.status === 'draft' ? 'ড্রাফট' : 'আর্কাইভ'}
                      </span>
                    </td>
                    <td className="small text-muted">
                      {g.published_at ? new Date(g.published_at).toLocaleDateString('bn-BD') : 'অপ্রকাশিত'}
                    </td>
                    <td className="text-end pe-4">
                      <div className="btn-group btn-group-sm">
                        {g.status !== 'published' && (
                          <button
                            type="button"
                            className="btn btn-outline-success"
                            title="পাবলিশ করুন"
                            onClick={() => handlePublish(g)}
                          >
                            <i className="bi bi-check-circle"></i>
                          </button>
                        )}
                        {g.status !== 'archived' && (
                          <button
                            type="button"
                            className="btn btn-outline-secondary"
                            title="আর্কাইভ করুন"
                            onClick={() => handleArchive(g)}
                          >
                            <i className="bi bi-archive"></i>
                          </button>
                        )}
                        <Link
                          to={`/admin/study-guides/${g.id}/edit`}
                          className="btn btn-outline-primary"
                          title="এডিট করুন"
                        >
                          <i className="bi bi-pencil"></i>
                        </Link>
                        <button
                          type="button"
                          className="btn btn-outline-danger"
                          title="মুছে ফেলুন"
                          onClick={() => setDeleteId(g.id)}
                        >
                          <i className="bi bi-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <div className="p-3">
          <Pagination meta={meta} onPageChange={(p) => fetchGuides(p)} />
        </div>
      </div>

      {/* Delete Modal */}
      <ConfirmModal
        isOpen={!!deleteId}
        title="স্টাডি গাইড মুছে ফেলবেন?"
        message="আপনি কি নিশ্চিতভাবে এই স্টাডি গাইডটি এবং এর সকল সেকশন মুছে ফেলতে চান?"
        confirmText="মুছে ফেলুন"
        confirmVariant="danger"
        loading={deleting}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteId(null)}
      />
    </div>
  );
};

export default StudyGuideManagementPage;
