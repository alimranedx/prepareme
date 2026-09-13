import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import AlertMessage from '../../components/common/AlertMessage';

const ForgotPasswordPage = () => {
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);
  const [error, setError] = useState(null);
  const [resetToken, setResetToken] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setMessage(null);

    try {
      const response = await apiClient.post('/auth/forgot-password', { email });
      setMessage(response.data.message);
      if (response.data.data?.reset_token) {
        setResetToken(response.data.data.reset_token);
      }
    } catch (err) {
      setError(err.response?.data?.message || 'পাসওয়ার্ড রিসেট লিংক প্রেরণে সমস্যা হয়েছে।');
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
                <div className="bg-warning-subtle text-warning p-3 rounded-circle d-inline-block mb-3">
                  <i className="bi bi-key-fill fs-2"></i>
                </div>
                <h3 className="fw-bold bangla-text text-dark">পাসওয়ার্ড পুনরুদ্ধার</h3>
                <p className="text-muted small bangla-text">
                  আপনার নিবন্ধিত ইমেইল ঠিকানা প্রদান করুন
                </p>
              </div>

              <AlertMessage type="success" message={message} onClose={() => setMessage(null)} />
              <AlertMessage type="danger" message={error} onClose={() => setError(null)} />

              {resetToken ? (
                <div className="alert alert-info bangla-text p-3 rounded-3">
                  <h6 className="fw-bold mb-1">রিসেট লিংক তৈরি হয়েছে (লোকাল পরিবেশ)</h6>
                  <p className="small mb-3">আপনি নিচের বাটনে ক্লিক করে সরাসরি পাসওয়ার্ড পরিবর্তন করতে পারেন:</p>
                  <Link
                    to={`/reset-password?token=${resetToken}&email=${encodeURIComponent(email)}`}
                    className="btn btn-sm btn-primary w-100"
                  >
                    নতুন পাসওয়ার্ড সেট করুন
                  </Link>
                </div>
              ) : (
                <form onSubmit={handleSubmit}>
                  <div className="mb-3">
                    <label className="form-label bangla-text fw-semibold">ইমেইল ঠিকানা</label>
                    <input
                      type="email"
                      className="form-control form-control-lg rounded-3"
                      placeholder="name@example.com"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      required
                    />
                  </div>

                  <button
                    type="submit"
                    className="btn btn-primary btn-lg w-100 rounded-3 bangla-text fw-bold mt-2"
                    disabled={loading}
                  >
                    {loading ? 'প্রেরণ করা হচ্ছে...' : 'রিসেট লিংক পাঠান'}
                  </button>
                </form>
              )}

              <div className="text-center mt-4 pt-2">
                <Link to="/login" className="text-decoration-none small bangla-text">
                  <i className="bi bi-arrow-left me-1"></i> লগইন পাতায় ফিরে যান
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ForgotPasswordPage;
