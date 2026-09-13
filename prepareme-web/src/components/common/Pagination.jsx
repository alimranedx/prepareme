import React from 'react';

const Pagination = ({ meta, onPageChange }) => {
  if (!meta || meta.last_page <= 1) return null;

  const { current_page, last_page } = meta;
  const pages = [];

  const maxVisiblePages = 5;
  let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(last_page, startPage + maxVisiblePages - 1);

  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return (
    <nav aria-label="Page navigation" className="mt-4">
      <ul className="pagination justify-content-center">
        <li className={`page-item ${current_page === 1 ? 'disabled' : ''}`}>
          <button
            className="page-link"
            onClick={() => onPageChange(current_page - 1)}
            disabled={current_page === 1}
            aria-label="Previous"
          >
            <span aria-hidden="true">&laquo; পূর্ববর্তী</span>
          </button>
        </li>

        {startPage > 1 && (
          <>
            <li className="page-item">
              <button className="page-link" onClick={() => onPageChange(1)}>
                1
              </button>
            </li>
            {startPage > 2 && <li className="page-item disabled"><span className="page-link">...</span></li>}
          </>
        )}

        {pages.map((p) => (
          <li key={p} className={`page-item ${p === current_page ? 'active' : ''}`}>
            <button className="page-link" onClick={() => onPageChange(p)}>
              {p}
            </button>
          </li>
        ))}

        {endPage < last_page && (
          <>
            {endPage < last_page - 1 && <li className="page-item disabled"><span className="page-link">...</span></li>}
            <li className="page-item">
              <button className="page-link" onClick={() => onPageChange(last_page)}>
                {last_page}
              </button>
            </li>
          </>
        )}

        <li className={`page-item ${current_page === last_page ? 'disabled' : ''}`}>
          <button
            className="page-link"
            onClick={() => onPageChange(current_page + 1)}
            disabled={current_page === last_page}
            aria-label="Next"
          >
            <span aria-hidden="true">পরবর্তী &raquo;</span>
          </button>
        </li>
      </ul>
    </nav>
  );
};

export default Pagination;
