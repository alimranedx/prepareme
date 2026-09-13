import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import AlertMessage from '../../components/common/AlertMessage';

const RegisterPage = () => {
  const { register } = useAuth();
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState(null);

  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
    setError(null);
    setValidationErrors(null);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setValidationErrors(null);

    try {
      await register(formData.name, formData.email, formData.password, formData.password_confirmation);
      navigate('/dashboard');
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'নিবন্ধন সম্পন্ন করা সম্ভব হয়নি। পুনরায় চেষ্টা করুন।');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container py-5">
      <div className="row justify-content-center">
        <div className="col-md-6 col-lg-5">
          <div className="card border-0 shadow-sm rounded-4 p-4">
            <div className="card-body">
              <div className="text-center mb-4">
                <div className="bg-primary-subtle text-primary p-3 rounded-circle d-inline-block mb-3">
                  <i className="bi bi-person-plus fs-2"></i>
                </div>
                <h3 className="fw-bold bangla-text text-dark">বিনামূল্যে নিবন্ধন করুন</h3>
                <p className="text-muted small bangla-text">
                  আপনার চাকরির পরীক্ষার লক্ষ্য অর্জনে আজই যুক্ত হোন
                </p>
              </div>

              <AlertMessage
                type="danger"
                message={error}
                errors={validationErrors}
                onClose={() => setError(null)}
              />

              <form onSubmit={handleSubmit}>
                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">আপনার পূর্ণ নাম</label>
                  <input
                    type="text"
                    name="name"
                    className="form-control form-control-lg rounded-3"
                    placeholder="উদাঃ মোঃ রাকিবুল হাসান"
                    value={formData.name}
                    onChange={handleChange}
                    required
                  />
                </div>

                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">ইমেইল ঠিকানা</label>
                  <input
                    type="email"
                    name="email"
                    className="form-control form-control-lg rounded-3"
                    placeholder="name@example.com"
                    value={formData.email}
                    onChange={handleChange}
                    required
                  />
                </div>

                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">পাসওয়ার্ড (কমপক্ষে ৮ অক্ষর)</label>
                  <input
                    type="password"
                    name="password"
                    className="form-control form-control-lg rounded-3"
                    placeholder="••••••••"
                    value={formData.password}
                    onChange={handleChange}
                    required
                    minLength="8"
                  />
                </div>

                <div className="mb-3">
                  <label className="form-label bangla-text fw-semibold">পাসওয়ার্ড নিশ্চিত করুন</label>
                  <input
                    type="password"
                    name="password_confirmation"
                    className="form-control form-control-lg rounded-3"
                    placeholder="••••••••"
                    value={formData.password_confirmation}
                    onChange={handleChange}
                    required
                  />
                </div>

                <button
                  type="submit"
                  className="btn btn-primary btn-lg w-100 rounded-3 bangla-text fw-bold mt-2"
                  disabled={loading}
                >
                  {loading ? (
                    <span>
                      <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                      অ্যাকাউন্ট তৈরি হচ্ছে...
                    </span>
                  ) : (
                    'নিবন্ধন সম্পন্ন করুন'
                  )}
                </button>
              </form>

              <div className="text-center mt-4 pt-2">
                <p className="text-muted small bangla-text mb-0">
                  ইতোমধ্যে অ্যাকাউন্ট আছে?{' '}
                  <Link to="/login" className="text-primary fw-semibold text-decoration-none">
                    লগইন করুন
                  </Link>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;
