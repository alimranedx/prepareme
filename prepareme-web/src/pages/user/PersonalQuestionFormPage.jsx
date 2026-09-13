import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';

const PersonalQuestionFormPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEditing = !!id;

  const [formData, setFormData] = useState({
    subject_id: '',
    topic_id: '',
    question: '',
    answer: '',
    explanation: '',
    source_title: '',
    source_page: '',
    tags: '',
  });

  const [subjects, setSubjects] = useState([]);
  const [topics, setTopics] = useState([]);
  const [loading, setLoading] = useState(isEditing);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState(null);

  // Load subjects
  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {
        console.error('Failed to load subjects', err);
      }
    };
    fetchSubjects();
  }, []);

  // Load existing question if editing
  useEffect(() => {
    if (!isEditing) return;

    const fetchQuestion = async () => {
      try {
        const response = await apiClient.get(`/my/questions/${id}`);
        const q = response.data.data;
        setFormData({
          subject_id: q.subject_id || '',
          topic_id: q.topic_id || '',
          question: q.question || '',
          answer: q.answer || '',
          explanation: q.explanation || '',
          source_title: q.source_title || '',
          source_page: q.source_page || '',
          tags: q.tags?.map((t) => t.name).join(', ') || '',
        });

        if (q.subject_id) {
          const tRes = await apiClient.get(`/subjects/${q.subject_id}/topics`);
          setTopics(tRes.data.data || []);
        }
      } catch (err) {
        setError(err.response?.data?.message || 'প্রশ্নটি লোড করা যায়নি।');
      } finally {
        setLoading(false);
      }
    };

    fetchQuestion();
  }, [id, isEditing]);

  const handleSubjectChange = async (e) => {
    const subjectId = e.target.value;
    setFormData((prev) => ({ ...prev, subject_id: subjectId, topic_id: '' }));
    if (!subjectId) {
      setTopics([]);
      return;
    }
    try {
      const response = await apiClient.get(`/subjects/${subjectId}/topics`);
      setTopics(response.data.data || []);
    } catch (err) {
      console.error('Failed to load topics', err);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setError(null);
    setValidationErrors(null);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setError(null);
    setValidationErrors(null);

    const tagsArray = formData.tags
      ? formData.tags.split(',').map((t) => t.trim()).filter(Boolean)
      : [];

    const payload = {
      ...formData,
      subject_id: formData.subject_id || null,
      topic_id: formData.topic_id || null,
      tags: tagsArray,
    };

    try {
      if (isEditing) {
        await apiClient.put(`/my/questions/${id}`, payload);
      } else {
        await apiClient.post('/my/questions', payload);
      }
      navigate('/my/questions');
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'প্রশ্ন সংরক্ষণ করতে সমস্যা হয়েছে।');
      }
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <LoadingSpinner fullPage text="তথ্য লোড হচ্ছে..." />;

  return (
    <div className="container py-5">
      <div className="row justify-content-center">
        <div className="col-lg-8">
          <nav aria-label="breadcrumb" className="mb-4">
            <ol className="breadcrumb bangla-text">
              <li className="breadcrumb-item"><Link to="/dashboard" className="text-decoration-none">ড্যাশবোর্ড</Link></li>
              <li className="breadcrumb-item"><Link to="/my/questions" className="text-decoration-none">ব্যক্তিগত নোটবুক</Link></li>
              <li className="breadcrumb-item active" aria-current="page">
                {isEditing ? 'প্রশ্ন সম্পাদনা' : 'নতুন প্রশ্ন তৈরি'}
              </li>
            </ol>
          </nav>

          <div className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <h3 className="fw-bold text-dark bangla-text mb-4">
              <i className="bi bi-pencil-fill text-primary me-2"></i>
              {isEditing ? 'ব্যক্তিগত প্রশ্ন সম্পাদনা করুন' : 'ব্যক্তিগত নোটবুকে নতুন প্রশ্ন লিখুন'}
            </h3>

            <AlertMessage type="danger" message={error} errors={validationErrors} onClose={() => setError(null)} />

            <form onSubmit={handleSubmit}>
              <div className="row g-3 mb-3">
                <div className="col-md-6">
                  <label className="form-label bangla-text fw-semibold">বিষয় (ঐচ্ছিক)</label>
                  <select
                    name="subject_id"
                    className="form-select bangla-text"
                    value={formData.subject_id}
                    onChange={handleSubjectChange}
                  >
                    <option value="">বিষয় নির্বাচন করুন...</option>
                    {subjects.map((sub) => (
                      <option key={sub.id} value={sub.id}>{sub.name}</option>
                    ))}
                  </select>
                </div>

                <div className="col-md-6">
                  <label className="form-label bangla-text fw-semibold">টপিক বা অধ্যায় (ঐচ্ছিক)</label>
                  <select
                    name="topic_id"
                    className="form-select bangla-text"
                    value={formData.topic_id}
                    onChange={handleChange}
                    disabled={!formData.subject_id}
                  >
                    <option value="">টপিক নির্বাচন করুন...</option>
                    {topics.map((top) => (
                      <option key={top.id} value={top.id}>{top.name}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold">প্রশ্ন <span className="text-danger">*</span></label>
                <textarea
                  name="question"
                  className="form-control bangla-text"
                  rows="3"
                  placeholder="আপনার প্রশ্নটি লিখুন..."
                  value={formData.question}
                  onChange={handleChange}
                  required
                ></textarea>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold">উত্তর <span className="text-danger">*</span></label>
                <textarea
                  name="answer"
                  className="form-control bangla-text"
                  rows="3"
                  placeholder="সঠিক উত্তরটি লিখুন..."
                  value={formData.answer}
                  onChange={handleChange}
                  required
                ></textarea>
              </div>

              <div className="mb-3">
                <label className="form-label bangla-text fw-semibold">ব্যক্তিগত ব্যাখ্যা বা নোট (ঐচ্ছিক)</label>
                <textarea
                  name="explanation"
                  className="form-control bangla-text"
                  rows="3"
                  placeholder="প্রশ্নটি মনে রাখার কৌশল বা অতিরিক্ত তথ্য..."
                  value={formData.explanation}
                  onChange={handleChange}
                ></textarea>
              </div>

              <div className="row g-3 mb-3">
                <div className="col-md-7">
                  <label className="form-label bangla-text fw-semibold">বই বা উৎসের নাম (ঐচ্ছিক)</label>
                  <input
                    type="text"
                    name="source_title"
                    className="form-control bangla-text"
                    placeholder="উদাঃ MP3 বাংলা, অগ্রদূত বা বিসিএস প্রশ্নব্যাংক"
                    value={formData.source_title}
                    onChange={handleChange}
                  />
                </div>
                <div className="col-md-5">
                  <label className="form-label bangla-text fw-semibold">পৃষ্ঠা নম্বর (ঐচ্ছিক)</label>
                  <input
                    type="text"
                    name="source_page"
                    className="form-control bangla-text"
                    placeholder="উদাঃ পৃষ্ঠা ১২৮"
                    value={formData.source_page}
                    onChange={handleChange}
                  />
                </div>
              </div>

              <div className="mb-4">
                <label className="form-label bangla-text fw-semibold">ট্যাগ (কমা দিয়ে আলাদা করুন)</label>
                <input
                  type="text"
                  name="tags"
                  className="form-control bangla-text"
                  placeholder="উদাঃ বিসিএস, রিভিশন, সমাস, গুরুত্বপূর্ণ"
                  value={formData.tags}
                  onChange={handleChange}
                />
              </div>

              <div className="d-flex justify-content-end gap-2 pt-3 border-top">
                <Link to="/my/questions" className="btn btn-light bangla-text px-4 rounded-pill">
                  বাতিল
                </Link>
                <button
                  type="submit"
                  className="btn btn-primary bangla-text fw-bold px-4 rounded-pill"
                  disabled={saving}
                >
                  {saving ? 'সংরক্ষণ হচ্ছে...' : isEditing ? 'আপডেট করুন' : 'সংরক্ষণ করুন'}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PersonalQuestionFormPage;
