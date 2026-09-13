import React, { useState, useEffect } from 'react';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import Pagination from '../../components/common/Pagination';
import ConfirmModal from '../../components/common/ConfirmModal';
import AlertMessage from '../../components/common/AlertMessage';

const UserManagementPage = () => {
  const [users, setUsers] = useState([]);
  const [meta, setMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [roleFilter, setRoleFilter] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [alert, setAlert] = useState(null);

  // Status Change Modal
  const [selectedUser, setSelectedUser] = useState(null);
  const [updating, setUpdating] = useState(false);

  const fetchUsers = async (page = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (search) params.append('search', search);
      if (roleFilter) params.append('role', roleFilter);
      if (statusFilter) params.append('status', statusFilter);
      params.append('page', page);

      const response = await apiClient.get(`/admin/users?${params.toString()}`);
      setUsers(response.data.data || []);
      setMeta(response.data.meta || null);
    } catch (err) {
      console.error('Failed to load users', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers(1);
  }, [roleFilter, statusFilter]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchUsers(1);
  };

  const handleStatusToggleConfirm = async () => {
    if (!selectedUser) return;
    setUpdating(true);
    const newStatus = selectedUser.status === 'active' ? 'blocked' : 'active';
    try {
      await apiClient.put(`/admin/users/${selectedUser.id}/status`, {
        status: newStatus,
      });
      setAlert({
        type: 'success',
        message: `ব্যবহারকারী (${selectedUser.email}) এর স্ট্যাটাস '${newStatus}' এ পরিবর্তন করা হয়েছে।`,
      });
      setSelectedUser(null);
      fetchUsers(meta?.current_page || 1);
    } catch (err) {
      setAlert({
        type: 'danger',
        message: err.response?.data?.message || 'স্ট্যাটাস আপডেট করতে সমস্যা হয়েছে।',
      });
    } finally {
      setUpdating(false);
    }
  };

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <h3 className="fw-bold text-dark bangla-text mb-0">ব্যবহারকারী ব্যবস্থাপনা</h3>
          <p className="text-muted bangla-text small mb-0 mt-1">
            প্ল্যাটফর্মের সকল পরীক্ষার্থী ও অ্যাডমিনিস্ট্রেটরদের তালিকা ও অ্যাকাউন্ট নিয়ন্ত্রণ
          </p>
        </div>
      </div>

      <AlertMessage type={alert?.type} message={alert?.message} onClose={() => setAlert(null)} />

      {/* Filter Bar */}
      <div className="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div className="row g-3 align-items-center">
          <div className="col-md-3">
            <select
              className="form-select bangla-text"
              value={roleFilter}
              onChange={(e) => setRoleFilter(e.target.value)}
            >
              <option value="">সকল রোল (All Roles)</option>
              <option value="user">পরীক্ষার্থী (User)</option>
              <option value="admin">এডমিন (Admin)</option>
            </select>
          </div>

          <div className="col-md-3">
            <select
              className="form-select bangla-text"
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
            >
              <option value="">সকল স্ট্যাটাস</option>
              <option value="active">সক্রিয় (Active)</option>
              <option value="blocked">স্থগিত (Blocked)</option>
            </select>
          </div>

          <div className="col-md-6">
            <form onSubmit={handleSearchSubmit}>
              <div className="input-group">
                <input
                  type="text"
                  className="form-control bangla-text"
                  placeholder="নাম বা ইমেইল লিখে খুঁজুন..."
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

      {/* Users Table */}
      <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        {loading ? (
          <div className="p-5">
            <LoadingSpinner text="ব্যবহারকারী তথ্য লোড হচ্ছে..." />
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 bangla-text">
              <thead className="table-light">
                <tr>
                  <th className="ps-4">আইডি</th>
                  <th>নাম ও ইমেইল</th>
                  <th>রোল</th>
                  <th>স্ট্যাটাস</th>
                  <th>সর্বশেষ লগইন</th>
                  <th>যোগদানের তারিখ</th>
                  <th className="text-end pe-4">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                {users.map((u) => (
                  <tr key={u.id}>
                    <td className="ps-4 text-muted small">#{u.id}</td>
                    <td>
                      <div className="fw-bold text-dark">{u.name}</div>
                      <div className="small text-muted">{u.email}</div>
                    </td>
                    <td>
                      <span className={`badge ${u.role === 'admin' ? 'bg-warning text-dark' : 'bg-primary-subtle text-primary'}`}>
                        {u.role}
                      </span>
                    </td>
                    <td>
                      <span className={`badge ${u.status === 'active' ? 'bg-success' : 'bg-danger'}`}>
                        {u.status === 'active' ? 'সক্রিয়' : 'স্থগিত'}
                      </span>
                    </td>
                    <td className="small text-muted">
                      {u.last_login_at ? new Date(u.last_login_at).toLocaleDateString('bn-BD') : 'তথ্য নেই'}
                    </td>
                    <td className="small text-muted">
                      {new Date(u.created_at).toLocaleDateString('bn-BD')}
                    </td>
                    <td className="text-end pe-4">
                      {u.role !== 'admin' && (
                        <button
                          type="button"
                          className={`btn btn-sm ${u.status === 'active' ? 'btn-outline-danger' : 'btn-outline-success'} rounded-pill px-3`}
                          onClick={() => setSelectedUser(u)}
                        >
                          {u.status === 'active' ? (
                            <>
                              <i className="bi bi-slash-circle me-1"></i> ব্লক করুন
                            </>
                          ) : (
                            <>
                              <i className="bi bi-check-circle me-1"></i> সক্রিয় করুন
                            </>
                          )}
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <div className="p-3">
          <Pagination meta={meta} onPageChange={(p) => fetchUsers(p)} />
        </div>
      </div>

      {/* Confirmation Modal */}
      <ConfirmModal
        isOpen={!!selectedUser}
        title={selectedUser?.status === 'active' ? 'অ্যাকাউন্ট ব্লক করবেন?' : 'অ্যাকাউন্ট সক্রিয় করবেন?'}
        message={`আপনি কি নিশ্চিতভাবে ${selectedUser?.name} (${selectedUser?.email}) এর অ্যাকাউন্ট ${selectedUser?.status === 'active' ? 'স্থগিত (Block)' : 'পুনরায় সক্রিয় (Active)'} করতে চান?`}
        confirmText={selectedUser?.status === 'active' ? 'হ্যাঁ, ব্লক করুন' : 'হ্যাঁ, সক্রিয় করুন'}
        confirmVariant={selectedUser?.status === 'active' ? 'danger' : 'success'}
        loading={updating}
        onConfirm={handleStatusToggleConfirm}
        onCancel={() => setSelectedUser(null)}
      />
    </div>
  );
};

export default UserManagementPage;
