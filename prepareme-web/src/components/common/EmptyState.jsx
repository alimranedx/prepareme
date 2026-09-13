import React from 'react';
import { Link } from 'react-router-dom';

const EmptyState = ({
  icon = 'bi-inbox',
  title = 'কোন তথ্য পাওয়া যায়নি',
  message = 'বর্তমানে প্রদর্শন করার মতো কোনো উপাদান নেই।',
  actionText,
  actionLink,
  onAction,
}) => {
  return (
    <div className="card text-center p-5 border-0 shadow-sm rounded-4 my-3">
      <div className="card-body">
        <div className="mb-3 text-secondary" style={{ fontSize: '3.5rem' }}>
          <i className={`bi ${icon}`}></i>
        </div>
        <h5 className="card-title fw-bold bangla-text text-dark">{title}</h5>
        <p className="card-text text-muted bangla-text mx-auto" style={{ maxWidth: '480px' }}>
          {message}
        </p>
        {actionText && actionLink && (
          <Link to={actionLink} className="btn btn-primary mt-2 bangla-text px-4 py-2">
            {actionText}
          </Link>
        )}
        {actionText && onAction && (
          <button onClick={onAction} className="btn btn-primary mt-2 bangla-text px-4 py-2">
            {actionText}
          </button>
        )}
      </div>
    </div>
  );
};

export default EmptyState;
