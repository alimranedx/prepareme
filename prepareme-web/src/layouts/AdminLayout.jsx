import React from 'react';
import { Outlet, NavLink, Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import ScrollToTopButton from '../components/common/ScrollToTopButton';

const AdminLayout = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="d-flex flex-column min-vh-100">
      {/* Admin Top Navbar */}
      <nav className="navbar navbar-dark bg-dark sticky-top shadow-sm px-3">
        <Link className="navbar-brand fw-bold d-flex align-items-center gap-2" to="/admin/dashboard">
          <span className="badge bg-warning text-dark px-2 py-1 rounded">Admin</span>
          <span>PrepareMe<span className="text-primary">.com</span></span>
        </Link>

        <div className="d-flex align-items-center gap-3">
          <Link to="/" className="btn btn-outline-light btn-sm bangla-text" target="_blank">
            <i className="bi bi-box-arrow-up-right me-1"></i> পাবলিক সাইট দেখুন
          </Link>
          <div className="text-light small bangla-text d-none d-md-block">
            {user?.name} ({user?.email})
          </div>
          <button onClick={handleLogout} className="btn btn-sm btn-outline-danger bangla-text">
            <i className="bi bi-box-arrow-right me-1"></i> লগআউট
          </button>
        </div>
      </nav>

      <div className="container-fluid flex-grow-1 d-flex flex-column flex-md-row p-0">
        {/* Sidebar */}
        <div className="admin-sidebar p-3 border-end">
          <div className="text-secondary small fw-bold px-3 mb-2 text-uppercase">সিস্টেম ব্যবস্থাপনা</div>
          <ul className="nav flex-column mb-auto bangla-text">
            <li className="nav-item">
              <NavLink to="/admin/dashboard" end className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-speedometer2"></i> ড্যাশবোর্ড
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink to="/admin/users" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-people-fill"></i> ব্যবহারকারীগণ
              </NavLink>
            </li>

            <div className="text-secondary small fw-bold px-3 mt-3 mb-2 text-uppercase">পাঠ্যক্রম ও প্রশ্ন</div>
            <li className="nav-item">
              <NavLink to="/admin/subjects" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-collection-fill"></i> বিষয়সমূহ (Subjects)
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink to="/admin/topics" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-diagram-3-fill"></i> টপিক ও সাবটপিক
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink to="/admin/study-guides" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-file-earmark-richtext-fill"></i> স্টাডি গাইড
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink to="/admin/public-questions" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-patch-question-fill"></i> পাবলিক প্রশ্নব্যাংক
              </NavLink>
            </li>

            <div className="text-secondary small fw-bold px-3 mt-3 mb-2 text-uppercase">মনিটরিং</div>
            <li className="nav-item">
              <NavLink to="/admin/ocr-documents" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>
                <i className="bi bi-file-earmark-binary-fill"></i> OCR প্রসেসিং লগ
              </NavLink>
            </li>
          </ul>
        </div>

        {/* Admin Content Area */}
        <div className="flex-grow-1 p-4 bg-light overflow-auto">
          <Outlet />
        </div>
      </div>
      <ScrollToTopButton />
    </div>
  );
};

export default AdminLayout;
