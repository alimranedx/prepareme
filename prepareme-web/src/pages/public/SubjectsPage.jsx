import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';

const SUBJECT_ICONS = {
  bangla: { icon: 'bi-pen-fill', bg: '#eff6ff', text: '#1e40af' },
  english: { icon: 'bi-book-half', bg: '#f5f3ff', text: '#6b21a8' },
  mathematics: { icon: 'bi-calculator-fill', bg: '#f0fdf4', text: '#15803d' },
  'general-knowledge': { icon: 'bi-globe-americas', bg: '#fffbeb', text: '#b45309' },
  ict: { icon: 'bi-cpu-fill', bg: '#ecfeff', text: '#0e7490' },
  'technical-subjects': { icon: 'bi-gear-wide-connected', bg: '#fdf2f8', text: '#be185d' },
};

const SubjectsPage = () => {
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let isMounted = true;
    const fetchSubjects = async () => {
      try {
        const response = await apiClient.get('/subjects');
        if (isMounted) {
          setSubjects(response.data.data || []);
        }
      } catch (err) {
        console.error('Failed to load subjects', err);
      } finally {
        if (isMounted) {
          setLoading(false);
        }
      }
    };

    fetchSubjects();
    return () => {
      isMounted = false;
    };
  }, []);

  return (
    <div className="container py-4 py-lg-5">
      {/* Header */}
      <div className="text-center mb-5">
        <div className="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary-subtle text-primary fw-semibold small mb-2 bangla-text">
          <i className="bi bi-mortarboard-fill"></i> চাকরি পরীক্ষার পূর্ণাঙ্গ পাঠ্যক্রম
        </div>
        <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
          চাকরি পরীক্ষার বিষয়ভিত্তিক সিলেবাস
        </h1>
        <p className="text-muted bangla-text mx-auto fs-6" style={{ maxWidth: '680px', lineHeight: '1.8' }}>
          বিসিএস, বাংলাদেশ ব্যাংক ও সকল বাণিজ্যিক ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ এবং এনটিআরসিএ পরীক্ষার জন্য বিষয়ভিত্তিক পূর্ণাঙ্গ গাইড ও প্রশ্নব্যাংক।
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
          {subjects.map((subject) => {
            const visual = SUBJECT_ICONS[subject.slug] || {
              icon: 'bi-journal-bookmark-fill',
              bg: '#f8fafc',
              text: '#1e3a8a',
            };

            return (
              <div className="col-md-6 col-lg-4" key={subject.id}>
                <div className="card h-100 border-0 shadow-sm rounded-4 card-hover p-4 bg-white d-flex flex-column">
                  <div className="card-body p-0 d-flex flex-column">
                    {/* Top Row: Icon + Counters */}
                    <div className="d-flex justify-content-between align-items-start mb-3">
                      <div
                        className="rounded-3 p-3 fs-3 d-flex align-items-center justify-content-center"
                        style={{ backgroundColor: visual.bg, color: visual.text, width: '56px', height: '56px' }}
                      >
                        <i className={`bi ${visual.icon}`}></i>
                      </div>
                      <div className="d-flex flex-column align-items-end gap-1">
                        <span className="badge bg-light text-dark border px-2 py-1 small rounded-pill">
                          <i className="bi bi-diagram-3 me-1 text-primary"></i>
                          {subject.topics_count || 0} টি টপিক
                        </span>
                        <span className="badge bg-light text-dark border px-2 py-1 small rounded-pill">
                          <i className="bi bi-patch-question me-1 text-success"></i>
                          {subject.public_questions_count || 0} টি প্রশ্ন
                        </span>
                      </div>
                    </div>

                    {/* Title & Description */}
                    <h3 className="fw-bold text-dark bangla-text fs-5 mb-2">
                      {subject.name}
                    </h3>
                    <p className="text-muted small bangla-text flex-grow-1 line-clamp-3 mb-4" style={{ lineHeight: '1.7' }}>
                      {subject.description || 'অধ্যায়ভিত্তিক গুরুত্বপূর্ণ আলোচনা, শর্টকাট কৌশল ও বিগত সালের প্রশ্নোত্তর।'}
                    </p>

                    {/* Action Button */}
                    <div className="mt-auto pt-3 border-top">
                      <Link
                        to={`/subjects/${subject.slug || subject.id}`}
                        className="btn btn-primary bangla-text w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center gap-2"
                      >
                        <span>অধ্যায়সমূহ ও স্টাডি গাইড</span>
                        <i className="bi bi-arrow-right"></i>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default SubjectsPage;
