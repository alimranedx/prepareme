import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import AlertMessage from '../../components/common/AlertMessage';

const ProfilePage = () => {
  const { user, updateProfile } = useAuth();

  const [formData, setFormData] = useState({
    name: user?.name || '',
    email: user?.email || '',
    password: '',
    password_confirmation: '',
  });

  const [loading, setLoading] = useState(false);
  const [successMessage, setSuccessMessage] = useState(null);
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState(null);

  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
    setError(null);
    setSuccessMessage(null);
    setValidationErrors(null);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setSuccessMessage(null);
    setValidationErrors(null);

    try {
      const payload = {
        name: formData.name,
        email: formData.email,
      };
      if (formData.password) {
        payload.password = formData.password;
        payload.password_confirmation = formData.password_confirmation;
      }

      await updateProfile(payload);
      setSuccessMessage('আপনার প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
      setFormData((prev) => ({ ...prev, password: '', password_confirmation: '' }));
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'প্রোফাইল আপডেট করতে সমস্যা হয়েছে।');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container py-5">
      <div className="row justify-content-center">
        <div className="col-md-8 col-lg-6">
          <div className="card border-0 shadow-sm rounded-4 p-4">
            <div className="card-body">
              <div className="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                <div className="bg-primary text-white p-3 rounded-circle fs-3">
                  <i className="bi bi-person-fill"></i>
                </div>
                <div>
                  <h4 className="fw-bold bangla-text text-dark mb-0">{user?.name}</h4>
                  <div className="text-muted small">
                    {user?.email} •{' '}
                    <span className="badge bg-primary-subtle text-primary">
                      {user?.role === 'admin' ? 'Administrator' : 'Job Candidate'}
                    </span>
                  </div>
                </div>
              </div>

              <AlertMessage type="success" message={successMessage} onClose={() => setSuccessMessage(null)} />
              <AlertMessage type="danger" message={error} errors={validationErrors} onClose={() => setError(null)} />

              <form onSubmit={handleSubmit}>
                <h6 className="fw-bold bangla-text mb-3 text-secondary">ব্যক্তিগত তথ্য</h6>

                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">নাম</label>
                  <input
                    type="text"
                    name="name"
                    className="form-control rounded-3"
                    value={formData.name}
                    onChange={handleChange}
                    required
                  />
                </div>

                <div className="mb-4">
                  <label className="form-label bangla-text fw-semibold">ইমেইল ঠিকানা</label>
                  <input
                    type="email"
                    name="email"
                    className="form-control rounded-3"
                    value={formData.email}
                    onChange={handleChange}
                    required
                  />
                </div>

                <h6 className="fw-bold bangla-text mb-3 text-secondary pt-2 border-top">
                  পাসওয়ার্ড পরিবর্তন (পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)
                </h6>

                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">নতুন পাসওয়ার্ড</label>
                  <input
                    type="password"
                    name="password"
                    className="form-control rounded-3"
                    placeholder="••••••••"
                    value={formData.password}
                    onChange={handleChange}
                    minLength="8"
                  />
                </div>

                <div className="mb-4">
                  <label className="form-label bangla-text fw-semibold">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                  <input
                    type="password"
                    name="password_confirmation"
                    className="form-control rounded-3"
                    placeholder="••••••••"
                    value={formData.password_confirmation}
                    onChange={handleChange}
                  />
                </div>

                <button
                  type="submit"
                  className="btn btn-primary bangla-text fw-bold px-4 py-2 rounded-3 w-100"
                  disabled={loading}
                >
                  {loading ? 'সংরক্ষণ হচ্ছে...' : 'পরিবর্তনসমূহ সংরক্ষণ করুন'}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;
