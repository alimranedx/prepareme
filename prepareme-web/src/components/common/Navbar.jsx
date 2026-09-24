import React from 'react';
import { Link, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

const Navbar = () => {
  const { user, isAuthenticated, isAdmin, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <nav className="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">
      <div className="container">
        <Link className="navbar-brand fw-bold d-flex align-items-center gap-2" to="/">
          <div className="navbar-brand-icon">
            <i className="bi bi-book-half fs-5"></i>
          </div>
          <span className="fs-5 tracking-tight text-white">PrepareMe<span className="text-gradient-emerald">.com</span></span>
        </Link>

        <button
          className="navbar-toggler border-0"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#mainNavbar"
          aria-controls="mainNavbar"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        <div className="collapse navbar-collapse" id="mainNavbar">
          <ul className="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
            <li className="nav-item">
              <NavLink className="nav-link bangla-text" to="/subjects">
                <i className="bi bi-book me-1"></i> বিষয় ও অধ্যায়
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink className="nav-link bangla-text" to="/exams">
                <i className="bi bi-mortarboard me-1"></i> চাকরি সিলেবাস
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink className="nav-link bangla-text" to="/previous-questions">
                <i className="bi bi-archive me-1"></i> বিগত প্রশ্নব্যাংক
              </NavLink>
            </li>
            <li className="nav-item">
              <NavLink className="nav-link bangla-text" to="/model-tests">
                <i className="bi bi-stopwatch me-1"></i> মডেল টেস্ট
              </NavLink>
            </li>

            {isAuthenticated && !isAdmin && (
              <>
                <li className="nav-item">
                  <NavLink className="nav-link bangla-text" to="/dashboard">
                    <i className="bi bi-speedometer2 me-1"></i> ড্যাশবোর্ড
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link bangla-text" to="/my/questions">
                    <i className="bi bi-journal-bookmark-fill me-1"></i> নিজস্ব নোটবুক
                  </NavLink>
                </li>
                <li className="nav-item">
                  <NavLink className="nav-link bangla-text" to="/my/ocr">
                    <i className="bi bi-camera-fill me-1"></i> OCR স্টুডিও
                  </NavLink>
                </li>
              </>
            )}

            {isAdmin && (
              <li className="nav-item">
                <NavLink className="nav-link text-warning bangla-text" to="/admin/dashboard">
                  <i className="bi bi-shield-lock-fill me-1"></i> এডমিন প্যানেল
                </NavLink>
              </li>
            )}
          </ul>

          <div className="d-flex align-items-center gap-2">
            {isAuthenticated ? (
              <div className="dropdown">
                <button
                  className="btn btn-hero-glass dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1 text-white"
                  type="button"
                  id="userDropdown"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i className="bi bi-person-circle fs-5 text-info"></i>
                  <span className="small fw-semibold">{user?.name || 'User'}</span>
                  {isAdmin && <span className="badge bg-warning text-dark ms-1">Admin</span>}
                </button>
                <ul className="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                  <li>
                    <h6 className="dropdown-header bangla-text">{user?.email}</h6>
                  </li>
                  <li>
                    <Link className="dropdown-item bangla-text" to="/profile">
                      <i className="bi bi-person-gear me-2 text-secondary"></i> প্রোফাইল সেটিংস
                    </Link>
                  </li>
                  {!isAdmin && (
                    <>
                      <li>
                        <Link className="dropdown-item bangla-text" to="/my/bookmarks">
                          <i className="bi bi-bookmark-star-fill me-2 text-secondary"></i> বুকমার্কসমূহ
                        </Link>
                      </li>
                      <li>
                        <Link className="dropdown-item bangla-text" to="/my/progress">
                          <i className="bi bi-graph-up me-2 text-secondary"></i> পড়াশোনার অগ্রগতি
                        </Link>
                      </li>
                    </>
                  )}
                  <li><hr className="dropdown-divider" /></li>
                  <li>
                    <button className="dropdown-item text-danger bangla-text" onClick={handleLogout}>
                      <i className="bi bi-box-arrow-right me-2"></i> লগআউট
                    </button>
                  </li>
                </ul>
              </div>
            ) : (
              <div className="d-flex gap-2">
                <Link to="/login" className="btn btn-hero-glass btn-sm bangla-text px-3 rounded-pill">
                  লগইন
                </Link>
                <Link to="/register" className="btn btn-hero-primary btn-sm bangla-text px-4 rounded-pill">
                  নিবন্ধন করুন
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
