import React from 'react';

const LoadingSpinner = ({ text = 'লোড হচ্ছে...', fullPage = false, size = 'border' }) => {
  const spinner = (
    <div className="d-flex flex-column align-items-center justify-content-center p-4">
      <div className={`spinner-${size} text-primary`} role="status" style={{ width: '2.5rem', height: '2.5rem' }}>
        <span className="visually-hidden">Loading...</span>
      </div>
      {text && <p className="mt-3 text-muted mb-0 bangla-text">{text}</p>}
    </div>
  );

  if (fullPage) {
    return (
      <div className="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        {spinner}
      </div>
    );
  }

  return spinner;
};

export default LoadingSpinner;
