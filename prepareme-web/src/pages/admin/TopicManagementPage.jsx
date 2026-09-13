import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import ConfirmModal from '../../components/common/ConfirmModal';
import Pagination from '../../components/common/Pagination';

const TopicManagementPage = () => {
  const [topics, setTopics] = useState([]);
  const [subjects, setSubjects] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedSubjectId, setSelectedSubjectId] = useState('');
  const [alert, setAlert] = useState(null);

  // Form Modal
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [currentId, setCurrentId] = useState(null);
  const [formData, setFormData] = useState({
    subject_id: '',
    parent_id: '',
    name: '',
    slug: '',
    description: '',
    sort_order: 0,
    status: 'published',
  });
  const [saving, setSaving] = useState(false);
  const [validationErrors, setValidationErrors] = useState(null);

  // Delete Modal
  const [deleteId, setDeleteId] = useState(null);
  const [deleting, setDeleting] = useState(false);

  const fetchTopics = async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (selectedSubjectId) params.append('subject_id', selectedSubjectId);
      params.append('page', page);

      const response = await apiClient.get(`/admin/topics?${params.toString()}`);
      setTopics(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load admin topics', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {}
    };
    fetchSubjects();
  }, []);

  useEffect(() => {
    fetchTopics(1);
  }, [selectedSubjectId]);

  const openCreateModal = () => {
    setIsEditing(false);
    setCurrentId(null);
    setFormData({
      subject_id: selectedSubjectId || (subjects[0]?.id || ''),
      parent_id: '',
      name: '',
      slug: '',
      description: '',
      sort_order: topics.length + 1,
      status: 'published',
    });
    setValidationErrors(null);
    setShowModal(true);
  };

  const openEditModal = (t) => {
    setIsEditing(true);
    setCurrentId(t.id);
    setFormData({
      subject_id: t.subject_id,
      parent_id: t.parent_id || '',
      name: t.name,
      slug: t.slug,
      description: t.description || '',
      sort_order: t.sort_order || 0,
      status: t.status || 'published',
    });
    setValidationErrors(null);
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setValidationErrors(null);

    const payload = {
      ...formData,
      parent_id: formData.parent_id ? parseInt(formData.parent_id) : null,
    };

    try {
      if (isEditing) {
        await apiClient.put(`/admin/topics/${currentId}`, payload);
        setAlert({ type: 'success', message: 'টপিক সফলভাবে আপডেট হয়েছে।' });
      } else {
        await apiClient.post('/admin/topics', payload);
        setAlert({ type: 'success', message: 'নতুন টপিক সফলভাবে যুক্ত হয়েছে।' });
      }
      setShowModal(false);
      fetchTopics(meta?.current_page || 1);
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setAlert({ type: 'danger', message: 'সংরক্ষণ ব্যর্থ হয়েছে।' });
      }
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!deleteId) return;
    setDeleting(true);
    try {
      await apiClient.delete(`/admin/topics/${deleteId}`);
      setAlert({ type: 'success', message: 'টপিক মুছে ফেলা হয়েছে।' });
      setDeleteId(null);
      fetchTopics(meta?.current_page || 1);
    } catch (err) {
      setAlert({ type: 'danger', message: 'টপিক মুছতে সমস্যা হয়েছে।' });
    } finally {
      setDeleting(false);
    }
  };

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">টপিক ও সাবটপিক ব্যবস্থাপনা</h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            বিষয়ভিত্তিক অধ্যায় ও নেস্টেড সাব-টপিক পরিচালনা করুন
          </p>
        </div>
        <button className="btn btn-primary bangla-text rounded-pill px-4" onClick={openCreateModal}>
          <i className="bi bi-plus-lg me-1"></i> নতুন টপিক যুক্ত করুন
        </button>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} onClose={() => setAlert(null)} />

      {/* Filter by subject */}
      <div className="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div className="row align-items-center">
          <div className="col-md-5">
            <label className="form-label small fw-semibold bangla-text mb-1">বিষয় অনুযায়ী ফিল্টার করুন</label>
            <select
              className="form-select bangla-text"
              value={selectedSubjectId}
              onChange={(e) => setSelectedSubjectId(e.target.value)}
            >
              <option value="">সকল বিষয় (All Subjects)</option>
              {subjects.map((sub) => (
                <option key={sub.id} value={sub.id}>{sub.name}</option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {/* Topics Table */}
      <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        {loading ? (
          <div className="p-5">
            <LoadingSpinner text="টপিকসমূহ লোড হচ্ছে..." />
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 bangla-text">
              <thead className="table-light">
                <tr>
                  <th className="ps-4">ক্রম</th>
                  <th>টপিকের নাম</th>
                  <th>মূল বিষয় (Subject)</th>
                  <th>প্যারেন্ট টপিক</th>
                  <th>গাইড</th>
                  <th>প্রশ্ন</th>
                  <th>স্ট্যাটাস</th>
                  <th className="text-end pe-4">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                {topics.map((t) => (
                  <tr key={t.id}>
                    <td className="ps-4 fw-bold text-muted">{t.sort_order}</td>
                    <td className="fw-bold text-dark">{t.name}</td>
                    <td>
                      <span className="badge bg-primary-subtle text-primary">
                        {t.subject?.name || subjects.find((s) => s.id === t.subject_id)?.name || 'বিষয়'}
                      </span>
                    </td>
                    <td className="small text-muted">
                      {t.parent?.name || (t.parent_id ? `ID: ${t.parent_id}` : 'মূল টপিক (Root)')}
                    </td>
                    <td>
                      <span className="badge bg-light text-secondary border">
                        {t.study_guides_count || 0} টি
                      </span>
                    </td>
                    <td>
                      <span className="badge bg-light text-secondary border">
                        {t.public_questions_count || 0} টি
                      </span>
                    </td>
                    <td>
                      <span className={`badge ${t.status === 'published' ? 'bg-success' : 'bg-secondary'}`}>
                        {t.status === 'published' ? 'প্রকাশিত' : t.status}
                      </span>
                    </td>
                    <td className="text-end pe-4">
                      <button
                        className="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                        onClick={() => openEditModal(t)}
                      >
                        <i className="bi bi-pencil me-1"></i> এডিট
                      </button>
                      <button
                        className="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onClick={() => setDeleteId(t.id)}
                      >
                        <i className="bi bi-trash me-1"></i> মুছুন
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <div className="p-3">
          <Pagination meta={meta} onPageChange={(p) => fetchTopics(p)} />
        </div>
      </div>

      {/* Form Modal */}
      {showModal && (
        <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(15, 23, 42, 0.7)' }}>
          <div className="modal-dialog modal-dialog-centered">
            <div className="modal-content border-0 shadow-lg rounded-4">
              <div className="modal-header border-0 pb-0">
                <h5 className="modal-title fw-bold text-dark bangla-text">
                  {isEditing ? 'টপিক সম্পাদনা করুন' : 'নতুন টপিক যুক্ত করুন'}
                </h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
              </div>

              <form onSubmit={handleSubmit}>
                <div className="modal-body py-4">
                  <AlertMessage type="danger" errors={validationErrors} />

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">বিষয় (Subject) <span className="text-danger">*</span></label>
                    <select
                      className="form-select bangla-text"
                      value={formData.subject_id}
                      onChange={(e) => setFormData((prev) => ({ ...prev, subject_id: e.target.value }))}
                      required
                    >
                      <option value="">বিষয় নির্বাচন করুন...</option>
                      {subjects.map((sub) => (
                        <option key={sub.id} value={sub.id}>{sub.name}</option>
                      ))}
                    </select>
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">প্যারেন্ট টপিক (যদি সাবটপিক হয়)</label>
                    <select
                      className="form-select bangla-text"
                      value={formData.parent_id}
                      onChange={(e) => setFormData((prev) => ({ ...prev, parent_id: e.target.value }))}
                    >
                      <option value="">কোনটি নয় (মূল টপিক)</option>
                      {topics
                        .filter((top) => !isEditing || top.id !== currentId)
                        .map((top) => (
                          <option key={top.id} value={top.id}>{top.name}</option>
                        ))}
                    </select>
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">টপিকের নাম <span className="text-danger">*</span></label>
                    <input
                      type="text"
                      className="form-control bangla-text"
                      value={formData.name}
                      onChange={(e) => setFormData((prev) => ({ ...prev, name: e.target.value }))}
                      required
                    />
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">স্লাগ (Slug - ঐচ্ছিক)</label>
                    <input
                      type="text"
                      className="form-control"
                      value={formData.slug}
                      onChange={(e) => setFormData((prev) => ({ ...prev, slug: e.target.value }))}
                    />
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">সংক্ষিপ্ত বিবরণ</label>
                    <textarea
                      className="form-control bangla-text"
                      rows="2"
                      value={formData.description}
                      onChange={(e) => setFormData((prev) => ({ ...prev, description: e.target.value }))}
                    ></textarea>
                  </div>

                  <div className="row g-3">
                    <div className="col-6">
                      <label className="form-label bangla-text fw-semibold small">ক্রম (Order)</label>
                      <input
                        type="number"
                        className="form-control"
                        value={formData.sort_order}
                        onChange={(e) => setFormData((prev) => ({ ...prev, sort_order: parseInt(e.target.value) || 0 }))}
                      />
                    </div>
                    <div className="col-6">
                      <label className="form-label bangla-text fw-semibold small">স্ট্যাটাস</label>
                      <select
                        className="form-select bangla-text"
                        value={formData.status}
                        onChange={(e) => setFormData((prev) => ({ ...prev, status: e.target.value }))}
                      >
                        <option value="published">প্রকাশিত (Published)</option>
                        <option value="draft">ড্রাফট (Draft)</option>
                        <option value="archived">আর্কাইভ (Archived)</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div className="modal-footer border-0 pt-0">
                  <button type="button" className="btn btn-light bangla-text px-4 rounded-pill" onClick={() => setShowModal(false)}>
                    বাতিল
                  </button>
                  <button type="submit" className="btn btn-primary bangla-text fw-bold px-4 rounded-pill" disabled={saving}>
                    {saving ? 'সংরক্ষণ হচ্ছে...' : 'সংরক্ষণ করুন'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* Delete Modal */}
      <ConfirmModal
        isOpen={!!deleteId}
        title="টপিক মুছে ফেলবেন?"
        message="এই টপিকটি মুছে ফেললে সংশ্লিষ্ট সাবটপিক ও স্টাডি উপাদান প্রভাবিত হতে পারে। আপনি কি নিশ্চিত?"
        confirmText="মুছে ফেলুন"
        confirmVariant="danger"
        loading={deleting}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteId(null)}
      />
    </div>
  );
};

export default TopicManagementPage;
