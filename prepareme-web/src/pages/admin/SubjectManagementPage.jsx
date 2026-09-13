import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';
import ConfirmModal from '../../components/common/ConfirmModal';

const SubjectManagementPage = () => {
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [alert, setAlert] = useState(null);

  // Form Modal State
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [currentId, setCurrentId] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    slug: '',
    description: '',
    sort_order: 0,
    status: 'published',
  });
  const [saving, setSaving] = useState(false);
  const [validationErrors, setValidationErrors] = useState(null);

  // Delete Modal State
  const [deleteId, setDeleteId] = useState(null);
  const [deleting, setDeleting] = useState(false);

  const fetchSubjects = async () => {
    setLoading(true);
    try {
      const response = await apiClient.get('/admin/subjects');
      setSubjects(response.data.data || []);
    } catch (err) {
      console.error('Failed to load admin subjects', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSubjects();
  }, []);

  const openCreateModal = () => {
    setIsEditing(false);
    setCurrentId(null);
    setFormData({
      name: '',
      slug: '',
      description: '',
      sort_order: subjects.length + 1,
      status: 'published',
    });
    setValidationErrors(null);
    setShowModal(true);
  };

  const openEditModal = (sub) => {
    setIsEditing(true);
    setCurrentId(sub.id);
    setFormData({
      name: sub.name,
      slug: sub.slug,
      description: sub.description || '',
      sort_order: sub.sort_order || 0,
      status: sub.status || 'published',
    });
    setValidationErrors(null);
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setValidationErrors(null);

    try {
      if (isEditing) {
        await apiClient.put(`/admin/subjects/${currentId}`, formData);
        setAlert({ type: 'success', message: 'বিষয়টি সফলভাবে আপডেট হয়েছে।' });
      } else {
        await apiClient.post('/admin/subjects', formData);
        setAlert({ type: 'success', message: 'নতুন বিষয় সফলভাবে যুক্ত হয়েছে।' });
      }
      setShowModal(false);
      fetchSubjects();
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
      await apiClient.delete(`/admin/subjects/${deleteId}`);
      setAlert({ type: 'success', message: 'বিষয়টি মুছে ফেলা হয়েছে।' });
      setDeleteId(null);
      fetchSubjects();
    } catch (err) {
      setAlert({ type: 'danger', message: 'বিষয় মুছতে সমস্যা হয়েছে।' });
    } finally {
      setDeleting(false);
    }
  };

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">বিষয় ব্যবস্থাপনা (Subjects)</h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            চাকরি পরীক্ষার মূল বিষয়সমূহ সংযোজন, পরিবর্তন বা অপসারণ করুন
          </p>
        </div>
        <button className="btn btn-primary bangla-text rounded-pill px-4" onClick={openCreateModal}>
          <i className="bi bi-plus-lg me-1"></i> নতুন বিষয় যুক্ত করুন
        </button>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} onClose={() => setAlert(null)} />

      <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        {loading ? (
          <div className="p-5">
            <LoadingSpinner text="বিষয়সমূহ লোড হচ্ছে..." />
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 bangla-text">
              <thead className="table-light">
                <tr>
                  <th className="ps-4">ক্রম</th>
                  <th>বিষয়ের নাম</th>
                  <th>স্লাগ (Slug)</th>
                  <th>টপিক সংখ্যা</th>
                  <th>পাবলিক প্রশ্ন</th>
                  <th>স্ট্যাটাস</th>
                  <th className="text-end pe-4">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                {subjects.map((sub) => (
                  <tr key={sub.id}>
                    <td className="ps-4 fw-bold text-muted">{sub.sort_order}</td>
                    <td className="fw-bold text-dark">{sub.name}</td>
                    <td className="small text-muted font-monospace">{sub.slug}</td>
                    <td>
                      <span className="badge bg-light text-primary border">
                        {sub.topics_count || 0} টি
                      </span>
                    </td>
                    <td>
                      <span className="badge bg-light text-success border">
                        {sub.public_questions_count || 0} টি
                      </span>
                    </td>
                    <td>
                      <span className={`badge ${sub.status === 'published' ? 'bg-success' : 'bg-secondary'}`}>
                        {sub.status === 'published' ? 'প্রকাশিত' : sub.status}
                      </span>
                    </td>
                    <td className="text-end pe-4">
                      <button
                        className="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                        onClick={() => openEditModal(sub)}
                      >
                        <i className="bi bi-pencil me-1"></i> এডিট
                      </button>
                      <button
                        className="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onClick={() => setDeleteId(sub.id)}
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
      </div>

      {/* Form Modal */}
      {showModal && (
        <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(15, 23, 42, 0.7)' }}>
          <div className="modal-dialog modal-dialog-centered">
            <div className="modal-content border-0 shadow-lg rounded-4">
              <div className="modal-header border-0 pb-0">
                <h5 className="modal-title fw-bold text-dark bangla-text">
                  {isEditing ? 'বিষয় সম্পাদনা করুন' : 'নতুন বিষয় তৈরি করুন'}
                </h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
              </div>

              <form onSubmit={handleSubmit}>
                <div className="modal-body py-4">
                  <AlertMessage type="danger" errors={validationErrors} />

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">বিষয়ের নাম <span className="text-danger">*</span></label>
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
                      placeholder="উদাঃ bangla-grammar"
                    />
                  </div>

                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold small">সংক্ষিপ্ত বিবরণ</label>
                    <textarea
                      className="form-control bangla-text"
                      rows="3"
                      value={formData.description}
                      onChange={(e) => setFormData((prev) => ({ ...prev, description: e.target.value }))}
                    ></textarea>
                  </div>

                  <div className="row g-3">
                    <div className="col-6">
                      <label className="form-label bangla-text fw-semibold small">ক্রম (Sort Order)</label>
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
        title="বিষয় মুছে ফেলবেন?"
        message="এই বিষয়টি মুছে ফেললে এর অধীনে থাকা সকল টপিক ও গাইড মুছে যেতে পারে। আপনি কি নিশ্চিত?"
        confirmText="মুছে ফেলুন"
        confirmVariant="danger"
        loading={deleting}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteId(null)}
      />
    </div>
  );
};

export default SubjectManagementPage;
