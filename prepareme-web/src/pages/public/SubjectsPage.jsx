import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';

const SubjectsPage = () => {
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        setSubjects(response.data.data || []);
      } catch (err) {
        console.error('Failed to load subjects', err);
      } finally {
        setLoading(false);
      }
    };

    fetchSubjects();
  }, []);

  return (
    <div className="container py-5">
      {/* Header */}
      <div className="text-center mb-5">
        <span className="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold bangla-text mb-2">
          সিলেবাস ও পাঠ্যক্রম
        </span>
        <h2 className="fw-bold text-dark bangla-text">চাকরি পরীক্ষার বিষয়সমূহ</h2>
        <p className="text-muted bangla-text mx-auto" style={{ maxWidth: '600px' }}>
          বিসিএস, ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ সহ সকল সরকারি ও বেসরকারি নিয়োগ পরীক্ষার জন্য প্রণীত পূর্ণাঙ্গ বিষয়ভিত্তিক সিলেবাস
        </p>
      </div>

      {loading ? (
        <LoadingSpinner text="বিষয়সমূহ লোড করা হচ্ছে..." />
      ) : subjects.length === 0 ? (
        <EmptyState
          title="কোন বিষয় পাওয়া যায়নি"
          message="বর্তমানে কোনো প্রকাশিত বিষয় পাওয়া যায়নি।"
        />
      ) : (
        <div className="row g-4">
          {subjects.map((subject) => (
            <div className="col-md-6 col-lg-4" key={subject.id}>
              <div className="card h-100 border-0 shadow-sm rounded-4 card-hover p-3">
                <div className="card-body d-flex flex-column">
                  <div className="d-flex justify-content-between align-items-center mb-3">
                    <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                      <i className="bi bi-book-half"></i>
                    </div>
                    <div className="d-flex flex-column align-items-end">
                      <span className="badge bg-light text-dark border mb-1">
                        <i className="bi bi-diagram-3 me-1 text-primary"></i>
                        {subject.topics_count || 0} টি টপিক
                      </span>
                      <span className="badge bg-light text-dark border">
                        <i className="bi bi-patch-question me-1 text-success"></i>
                        {subject.public_questions_count || 0} টি প্রশ্ন
                      </span>
                    </div>
                  </div>

                  <h4 className="fw-bold text-dark bangla-text mb-2">{subject.name}</h4>
                  <p className="text-muted small bangla-text flex-grow-1 line-clamp-3">
                    {subject.description || 'অধ্যায়ভিত্তিক গুরুত্বপূর্ণ আলোচনা, শর্টকাট কৌশল ও প্রশ্নোত্তর।'}
                  </p>

                  <div className="mt-3 pt-3 border-top">
                    <Link
                      to={`/subjects/${subject.slug || subject.id}`}
                      className="btn btn-primary bangla-text w-100 rounded-pill"
                    >
                      অধ্যায়সমূহ ও স্টাডি গাইড <i className="bi bi-arrow-right ms-1"></i>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default SubjectsPage;
