import React from 'react';

const ConfirmModal = ({
  isOpen,
  title = 'নিশ্চিত করুন',
  message = 'আপনি কি এই কাজটি করতে নিশ্চিত?',
  confirmText = 'হ্যাঁ, নিশ্চিত',
  cancelText = 'বাতিল',
  confirmVariant = 'danger',
  onConfirm,
  onCancel,
  loading = false,
}) => {
  if (!isOpen) return null;

  return (
    <div
      className="modal fade show d-block"
      tabIndex="-1"
      style={{ backgroundColor: 'rgba(15, 23, 42, 0.65)' }}
    >
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content shadow-lg border-0 rounded-4">
          <div className="modal-header border-0 pb-0">
            <h5 className="modal-title fw-bold text-dark bangla-text">{title}</h5>
            <button
              type="button"
              className="btn-close"
              onClick={onCancel}
              disabled={loading}
              aria-label="Close"
            ></button>
          </div>
          <div className="modal-body py-4">
            <p className="text-muted mb-0 bangla-text" style={{ fontSize: '1.05rem' }}>
              {message}
            </p>
          </div>
          <div className="modal-footer border-0 pt-0">
            <button
              type="button"
              className="btn btn-light bangla-text px-4"
              onClick={onCancel}
              disabled={loading}
            >
              {cancelText}
            </button>
            <button
              type="button"
              className={`btn btn-${confirmVariant} bangla-text px-4`}
              onClick={onConfirm}
              disabled={loading}
            >
              {loading ? 'প্রসেসিং...' : confirmText}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ConfirmModal;
