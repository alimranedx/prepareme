import React, { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import apiClient from '../../api/client';
import AlertMessage from '../../components/common/AlertMessage';

const ResetPasswordPage = () => {
  const navigate = useNavigate();
  const location = useLocation();

  const queryParams = new URLSearchParams(location.search);
  const initialToken = queryParams.get('token') || '';
  const initialEmail = queryParams.get('email') || '';

  const [formData, setFormData] = useState({
    token: initialToken,
    email: initialEmail,
    password: '',
    password_confirmation: '',
  });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
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
      await apiClient.post('/auth/reset-password', formData);
      setSuccess(true);
      setTimeout(() => {
        navigate('/login');
      }, 2500);
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'পাসওয়ার্ড পরিবর্তন সম্ভব হয়নি। টোকেন যাচাই করুন।');
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
                <div className="bg-success-subtle text-success p-3 rounded-circle d-inline-block mb-3">
                  <i className="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h3 className="fw-bold bangla-text text-dark">নতুন পাসওয়ার্ড সেট করুন</h3>
                <p className="text-muted small bangla-text">
                  আপনার অ্যাকাউন্টের জন্য একটি শক্তিশালী নতুন পাসওয়ার্ড লিখুন
                </p>
              </div>

              {success ? (
                <div className="alert alert-success text-center bangla-text p-4 rounded-3">
                  <i className="bi bi-check-circle-fill fs-1 text-success d-block mb-2"></i>
                  <h5 className="fw-bold">পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে!</h5>
                  <p className="small mb-0">আপনাকে লগইন পাতায় রিডাইরেক্ট করা হচ্ছে...</p>
                </div>
              ) : (
                <>
                  <AlertMessage
                    type="danger"
                    message={error}
                    errors={validationErrors}
                    onClose={() => setError(null)}
                  />

                  <form onSubmit={handleSubmit}>
                    <input type="hidden" name="token" value={formData.token} />

                    <div className="mb-3">
                      <label className="form-label bangla-text fw-semibold">ইমেইল ঠিকানা</label>
                      <input
                        type="email"
                        name="email"
                        className="form-control form-control-lg rounded-3"
                        value={formData.email}
                        onChange={handleChange}
                        required
                      />
                    </div>

                    <div className="mb-3">
                      <label className="form-label bangla-text fw-semibold">নতুন পাসওয়ার্ড (কমপক্ষে ৮ অক্ষর)</label>
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
                      <label className="form-label bangla-text fw-semibold">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
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
                      className="btn btn-success btn-lg w-100 rounded-3 bangla-text fw-bold mt-2"
                      disabled={loading}
                    >
                      {loading ? 'সংরক্ষণ হচ্ছে...' : 'পাসওয়ার্ড আপডেট করুন'}
                    </button>
                  </form>
                </>
              )}

              <div className="text-center mt-4 pt-2">
                <Link to="/login" className="text-decoration-none small bangla-text">
                  লগইন পাতায় ফিরে যান
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ResetPasswordPage;
