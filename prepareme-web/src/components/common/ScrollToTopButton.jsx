import React, { useState, useEffect } from 'react';

const ScrollToTopButton = () => {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const toggleVisibility = () => {
      if (window.scrollY > 260) {
        setIsVisible(true);
      } else {
        setIsVisible(false);
      }
    };

    window.addEventListener('scroll', toggleVisibility, { passive: true });
    // Run once on mount in case user lands halfway down
    toggleVisibility();

    return () => {
      window.removeEventListener('scroll', toggleVisibility);
    };
  }, []);

  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  };

  return (
    <button
      type="button"
      onClick={scrollToTop}
      aria-label="পৃষ্ঠার শীর্ষে যান"
      title="পৃষ্ঠার শীর্ষে যান (Scroll to Top)"
      className={`btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center position-fixed ${
        isVisible ? 'opacity-100 visible' : 'opacity-0 invisible'
      }`}
      style={{
        right: '28px',
        bottom: '28px',
        width: '48px',
        height: '48px',
        zIndex: 1060,
        transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        transform: isVisible ? 'translateY(0) scale(1)' : 'translateY(16px) scale(0.85)',
        boxShadow: '0 6px 20px rgba(37, 99, 235, 0.4)',
      }}
    >
      <i className="bi bi-arrow-up fs-5 fw-bold text-white"></i>
    </button>
  );
};

export default ScrollToTopButton;
