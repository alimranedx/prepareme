import React, { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import AlertMessage from '../../components/common/AlertMessage';

const LoginPage = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  const queryParams = new URLSearchParams(location.search);
  const isExpired = queryParams.get('expired') === '1';

  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(isExpired ? 'আপনার সেশনের মেয়াদ শেষ হয়েছে। অনুগ্রহ করে পুনরায় লগইন করুন।' : null);
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
      const user = await login(formData.email, formData.password);
      if (user.role === 'admin') {
        navigate('/admin/dashboard');
      } else {
        navigate('/dashboard');
      }
    } catch (err) {
      if (err.response?.status === 422) {
        setValidationErrors(err.response.data.errors);
      } else if (err.response?.status === 403) {
        setError(err.response.data.message || 'আপনার অ্যাকাউন্টটি স্থগিত বা ব্লক করা হয়েছে।');
      } else {
        setError(err.response?.data?.message || 'লগইন ব্যর্থ হয়েছে। অনুগ্রহ করে আপনার তথ্য যাচাই করুন।');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleDemoFill = (role) => {
    if (role === 'admin') {
      setFormData({ email: 'admin@prepareme.com', password: 'Password123!' });
    } else {
      setFormData({ email: 'user1@prepareme.com', password: 'Password123!' });
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
                  <i className="bi bi-box-arrow-in-right fs-2"></i>
                </div>
                <h3 className="fw-bold bangla-text text-dark">অ্যাকাউন্টে প্রবেশ করুন</h3>
                <p className="text-muted small bangla-text">
                  PrepareMe.com এ আপনার ব্যক্তিগত প্রস্তুতি চালিয়ে যান
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
                  <div className="d-flex justify-content-between">
                    <label className="form-label bangla-text fw-semibold">পাসওয়ার্ড</label>
                    <Link to="/forgot-password" className="small text-decoration-none bangla-text">
                      পাসওয়ার্ড ভুলে গেছেন?
                    </Link>
                  </div>
                  <input
                    type="password"
                    name="password"
                    className="form-control form-control-lg rounded-3"
                    placeholder="••••••••"
                    value={formData.password}
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
                      যাচাই হচ্ছে...
                    </span>
                  ) : (
                    'লগইন করুন'
                  )}
                </button>
              </form>

              {/* Demo Login Shortcuts */}
              <div className="mt-4 pt-3 border-top text-center">
                <p className="text-muted small bangla-text mb-2">দ্রুত টেস্ট করার জন্য ডেমো অ্যাকাউন্ট:</p>
                <div className="d-flex justify-content-center gap-2">
                  <button
                    type="button"
                    className="btn btn-sm btn-outline-secondary bangla-text rounded-pill"
                    onClick={() => handleDemoFill('user')}
                  >
                    <i className="bi bi-person me-1"></i> সাধারণ পরীক্ষার্থী (User)
                  </button>
                  <button
                    type="button"
                    className="btn btn-sm btn-outline-warning text-dark bangla-text rounded-pill"
                    onClick={() => handleDemoFill('admin')}
                  >
                    <i className="bi bi-shield-lock me-1"></i> এডমিন (Admin)
                  </button>
                </div>
              </div>

              <div className="text-center mt-4 pt-2">
                <p className="text-muted small bangla-text mb-0">
                  অ্যাকাউন্ট নেই?{' '}
                  <Link to="/register" className="text-primary fw-semibold text-decoration-none">
                    নতুন অ্যাকাউন্ট তৈরি করুন
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

export default LoginPage;
