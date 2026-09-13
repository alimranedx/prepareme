import React from 'react';

const AlertMessage = ({ type = 'danger', message, errors = null, onClose }) => {
  if (!message && !errors) return null;

  return (
    <div className={`alert alert-${type} alert-dismissible fade show shadow-sm`} role="alert">
      <div className="d-flex align-items-start">
        <div className="me-2">
          {type === 'danger' && <i className="bi bi-exclamation-triangle-fill"></i>}
          {type === 'success' && <i className="bi bi-check-circle-fill"></i>}
          {type === 'warning' && <i className="bi bi-exclamation-circle-fill"></i>}
          {type === 'info' && <i className="bi bi-info-circle-fill"></i>}
        </div>
        <div className="flex-grow-1 bangla-text">
          {message && <div>{message}</div>}
          {errors && typeof errors === 'object' && (
            <ul className="mb-0 ps-3 mt-1 small">
              {Object.entries(errors).map(([field, msgs]) => (
                <li key={field}>
                  {Array.isArray(msgs) ? msgs.join(', ') : msgs}
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>
      {onClose && (
        <button
          type="button"
          className="btn-close"
          aria-label="Close"
          onClick={onClose}
        ></button>
      )}
    </div>
  );
};

export default AlertMessage;
