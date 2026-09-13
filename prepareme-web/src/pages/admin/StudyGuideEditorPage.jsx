import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';

const StudyGuideEditorPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEditing = !!id;

  const [subjects, setSubjects] = useState([]);
  const [topics, setTopics] = useState([]);
  const [selectedSubjectId, setSelectedSubjectId] = useState('');

  const [formData, setFormData] = useState({
    topic_id: '',
    title: '',
    slug: '',
    summary: '',
    content: '',
    status: 'published',
    published_at: '',
  });

  const [sections, setSections] = useState([]);
  const [loading, setLoading] = useState(isEditing);
  const [saving, setSaving] = useState(false);
  const [alert, setAlert] = useState(null);
  const [validationErrors, setValidationErrors] = useState(null);

  // Load subjects
  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {}
    };
    fetchSubjects();
  }, []);

  // Load topics when subject changes
  const handleSubjectChange = async (e) => {
    const subId = e.target.value;
    setSelectedSubjectId(subId);
    setFormData((prev) => ({ ...prev, topic_id: '' }));
    if (!subId) {
      setTopics([]);
      return;
    }
    try {
      const res = await apiClient.get(`/subjects/${subId}/topics`);
      setTopics(res.data.data || []);
    } catch (err) {}
  };

  // Load guide if editing
  useEffect(() => {
    if (!isEditing) return;

    const fetchGuide = async () => {
      try {
        const response = await apiClient.get(`/admin/study-guides/${id}`);
        const g = response.data.data;
        setFormData({
          topic_id: g.topic_id || '',
          title: g.title || '',
          slug: g.slug || '',
          summary: g.summary || '',
          content: g.content || '',
          status: g.status || 'published',
          published_at: g.published_at ? g.published_at.substring(0, 10) : '',
        });

        if (g.sections && g.sections.length > 0) {
          setSections(g.sections.map((s, idx) => ({
            id: s.id,
            title: s.title,
            content: s.content,
            section_type: s.section_type || 'explanation',
            sort_order: s.sort_order ?? idx,
          })));
        }

        if (g.topic?.subject_id) {
          setSelectedSubjectId(g.topic.subject_id);
          const tRes = await apiClient.get(`/subjects/${g.topic.subject_id}/topics`);
          setTopics(tRes.data.data || []);
        }
      } catch (err) {
        setAlert({ type: 'danger', message: 'স্টাডি গাইড লোড করা যায়নি।' });
      } finally {
        setLoading(false);
      }
    };

    fetchGuide();
  }, [id, isEditing]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  // Section manipulation
  const handleAddSection = () => {
    setSections((prev) => [
      ...prev,
      {
        title: '',
        content: '',
        section_type: 'explanation',
        sort_order: prev.length,
      },
    ]);
  };

  const handleSectionChange = (index, field, value) => {
    setSections((prev) => {
      const updated = [...prev];
      updated[index] = { ...updated[index], [field]: value };
      return updated;
    });
  };

  const handleRemoveSection = (index) => {
    setSections((prev) => prev.filter((_, i) => i !== index));
  };

  const handleMoveSection = (index, direction) => {
    setSections((prev) => {
      const targetIndex = index + direction;
      if (targetIndex < 0 || targetIndex >= prev.length) return prev;
      const updated = [...prev];
      const temp = updated[index];
      updated[index] = updated[targetIndex];
      updated[targetIndex] = temp;
      return updated.map((s, idx) => ({ ...s, sort_order: idx }));
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setAlert(null);
    setValidationErrors(null);

    const payload = {
      ...formData,
      sections: sections.map((s, idx) => ({
        ...s,
        sort_order: idx,
      })),
    };

    try {
      if (isEditing) {
        await apiClient.put(`/admin/study-guides/${id}`, payload);
      } else {
        await apiClient.post('/admin/study-guides', payload);
      }
      navigate('/admin/study-guides');
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setAlert({ type: 'danger', message: 'সংরক্ষণ ব্যর্থ হয়েছে। অনুগ্রহ করে ডেটা যাচাই করুন।' });
      }
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <LoadingSpinner fullPage text="এডিটর প্রস্তুত হচ্ছে..." />;

  return (
    <div className="container-fluid py-2">
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text">
          <li className="breadcrumb-item"><Link to="/admin/dashboard" className="text-decoration-none">এডমিন</Link></li>
          <li className="breadcrumb-item"><Link to="/admin/study-guides" className="text-decoration-none">স্টাডি গাইড</Link></li>
          <li className="breadcrumb-item active" aria-current="page">
            {isEditing ? 'গাইড ও সেকশন এডিটর' : 'নতুন গাইড তৈরি'}
          </li>
        </ol>
      </nav>

      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">
            {isEditing ? 'স্টাডি গাইড সম্পাদনা করুন' : 'নতুন স্টাডি গাইড লিখুন'}
          </h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            পূর্ণাঙ্গ অধ্যায়ভিত্তিক আলোচনা এবং সাজানো সেকশন (ব্যাখ্যা, উদাহরণ, সূত্রাবলী)
          </p>
        </div>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} errors={validationErrors} onClose={() => setAlert(null)} />

      <form onSubmit={handleSubmit}>
        <div className="row g-4">
          {/* Main Content Info */}
          <div className="col-lg-8">
            <div className="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
              <h5 className="fw-bold text-dark bangla-text mb-3">মূল বিবরণ ও ভূমিকা</h5>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">গাইড শিরোনাম <span className="text-danger">*</span></label>
                <input
                  type="text"
                  name="title"
                  className="form-control form-control-lg bangla-text"
                  placeholder="উদাঃ বিসিএস পরীক্ষার জন্য সন্ধির পূর্ণাঙ্গ নিয়ম ও শর্টকাট কৌশল"
                  value={formData.title}
                  onChange={handleChange}
                  required
                />
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">স্লাগ (ঐচ্ছিক)</label>
                <input
                  type="text"
                  name="slug"
                  className="form-control"
                  placeholder="উদাঃ mastering-sandhi-rules"
                  value={formData.slug}
                  onChange={handleChange}
                />
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">সারসংক্ষেপ বা উদ্দেশ্য (Summary)</label>
                <textarea
                  name="summary"
                  className="form-control bangla-text"
                  rows="2"
                  placeholder="এই গাইডে শিক্ষার্থীরা কী কী শিখবে তার সংক্ষিপ্ত রূপ..."
                  value={formData.summary}
                  onChange={handleChange}
                ></textarea>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">প্রধান বিষয়বস্তু / ভূমিকা (Content) <span className="text-danger">*</span></label>
                <textarea
                  name="content"
                  className="form-control bangla-text"
                  rows="6"
                  placeholder="অধ্যায়ের সাধারণ পরিচিতি ও ভূমিকা..."
                  value={formData.content}
                  onChange={handleChange}
                  required
                ></textarea>
              </div>
            </div>

            {/* Dynamic Structured Sections */}
            <div className="card border-0 shadow-sm rounded-4 p-4 bg-white">
              <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                  <h5 className="fw-bold text-dark bangla-text mb-0">
                    <i className="bi bi-layers-fill text-primary me-2"></i> গাইডের সেকশনসমূহ (Sections)
                  </h5>
                  <span className="small text-muted bangla-text">
                    ব্যাখ্যা, বাস্তব উদাহরণ, শর্টকাট সূত্র বা অনুশীলনের পৃথক সেকশন যুক্ত করুন
                  </span>
                </div>
                <button
                  type="button"
                  className="btn btn-outline-primary bangla-text rounded-pill px-3"
                  onClick={handleAddSection}
                >
                  <i className="bi bi-plus-circle me-1"></i> সেকশন যোগ করুন
                </button>
              </div>

              {sections.length === 0 ? (
                <div className="alert alert-light border text-center p-4 bangla-text text-muted rounded-3">
                  এখনও কোনো পৃথক সেকশন যোগ করা হয়নি। শিক্ষার্থীদের সুবিধার্থে বিষয়ভিত্তিক সেকশন যোগ করতে পারেন।
                </div>
              ) : (
                <div className="d-flex flex-column gap-3">
                  {sections.map((sec, idx) => (
                    <div key={idx} className="card border rounded-3 p-3 bg-light">
                      <div className="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span className="badge bg-dark rounded-pill px-3 py-2">
                          সেকশন #{idx + 1}
                        </span>
                        <div className="d-flex gap-1">
                          <button
                            type="button"
                            className="btn btn-sm btn-outline-secondary"
                            onClick={() => handleMoveSection(idx, -1)}
                            disabled={idx === 0}
                            title="উপরে নিন"
                          >
                            <i className="bi bi-arrow-up"></i>
                          </button>
                          <button
                            type="button"
                            className="btn btn-sm btn-outline-secondary"
                            onClick={() => handleMoveSection(idx, 1)}
                            disabled={idx === sections.length - 1}
                            title="নিচে নিন"
                          >
                            <i className="bi bi-arrow-down"></i>
                          </button>
                          <button
                            type="button"
                            className="btn btn-sm btn-outline-danger"
                            onClick={() => handleRemoveSection(idx)}
                            title="সেকশন মুছুন"
                          >
                            <i className="bi bi-trash"></i>
                          </button>
                        </div>
                      </div>

                      <div className="row g-3 mb-2">
                        <div className="col-md-8">
                          <label className="form-label small fw-semibold bangla-text">সেকশন শিরোনাম</label>
                          <input
                            type="text"
                            className="form-control bangla-text"
                            placeholder="উদাঃ ১. স্বরসন্ধির প্রধান ৫টি সূত্র"
                            value={sec.title}
                            onChange={(e) => handleSectionChange(idx, 'title', e.target.value)}
                            required
                          />
                        </div>
                        <div className="col-md-4">
                          <label className="form-label small fw-semibold bangla-text">সেকশনের ধরন (Type)</label>
                          <select
                            className="form-select bangla-text"
                            value={sec.section_type}
                            onChange={(e) => handleSectionChange(idx, 'section_type', e.target.value)}
                          >
                            <option value="explanation">ব্যাখ্যা (Explanation)</option>
                            <option value="example">উদাহরণ (Example)</option>
                            <option value="formula">সূত্র বা শর্টকাট (Formula)</option>
                            <option value="summary">সারসংক্ষেপ (Summary)</option>
                            <option value="practice">অনুশীলন (Practice)</option>
                          </select>
                        </div>
                      </div>

                      <div className="mb-1">
                        <label className="form-label small fw-semibold bangla-text">সেকশনের বিবরণ</label>
                        <textarea
                          className="form-control bangla-text"
                          rows="4"
                          placeholder="এই সেকশনের বিস্তারিত কন্টেন্ট..."
                          value={sec.content}
                          onChange={(e) => handleSectionChange(idx, 'content', e.target.value)}
                          required
                        ></textarea>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          {/* Right Sidebar: Topic & Publication Meta */}
          <div className="col-lg-4">
            <div className="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 sticky-top" style={{ top: '80px' }}>
              <h5 className="fw-bold text-dark bangla-text mb-3">শ্রেণীবিন্যাস ও প্রকাশনা</h5>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">বিষয় (Subject)</label>
                <select
                  className="form-select bangla-text"
                  value={selectedSubjectId}
                  onChange={handleSubjectChange}
                  required
                >
                  <option value="">বিষয় নির্বাচন করুন...</option>
                  {subjects.map((s) => (
                    <option key={s.id} value={s.id}>{s.name}</option>
                  ))}
                </select>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">টপিক বা অধ্যায় <span className="text-danger">*</span></label>
                <select
                  name="topic_id"
                  className="form-select bangla-text"
                  value={formData.topic_id}
                  onChange={handleChange}
                  required
                  disabled={!selectedSubjectId}
                >
                  <option value="">টপিক নির্বাচন করুন...</option>
                  {topics.map((t) => (
                    <option key={t.id} value={t.id}>{t.name}</option>
                  ))}
                </select>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold small">প্রকাশনা স্ট্যাটাস</label>
                <select
                  name="status"
                  className="form-select bangla-text"
                  value={formData.status}
                  onChange={handleChange}
                >
                  <option value="published">প্রকাশিত (Published)</option>
                  <option value="draft">ড্রাফট (Draft - সাধারণ ব্যবহারকারী দেখতে পারবে না)</option>
                  <option value="archived">আর্কাইভ (Archived)</option>
                </select>
              </div>

              <div className="mb-4">
                <label className="form-label bangla-text fw-semibold small">প্রকাশের তারিখ</label>
                <input
                  type="date"
                  name="published_at"
                  className="form-control"
                  value={formData.published_at}
                  onChange={handleChange}
                />
              </div>

              <div className="d-grid gap-2 pt-2 border-top">
                <button
                  type="submit"
                  className="btn btn-primary bangla-text fw-bold py-3 rounded-pill shadow-sm"
                  disabled={saving}
                >
                  {saving ? 'সংরক্ষণ হচ্ছে...' : isEditing ? 'আপডেট করুন' : 'সম্পূর্ণ গাইড প্রকাশ করুন'}
                </button>
                <Link to="/admin/study-guides" className="btn btn-light bangla-text rounded-pill">
                  বাতিল
                </Link>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  );
};

export default StudyGuideEditorPage;
