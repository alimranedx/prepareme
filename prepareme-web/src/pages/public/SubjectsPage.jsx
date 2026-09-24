import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';

const SUBJECT_ICONS = {
  bangla: { icon: 'bi-pen-fill', bg: '#e0e7ff', text: '#4f46e5' },
  english: { icon: 'bi-book-half', bg: '#f3e8ff', text: '#7e22ce' },
  mathematics: { icon: 'bi-calculator-fill', bg: '#dcfce7', text: '#15803d' },
  'general-knowledge': { icon: 'bi-globe-americas', bg: '#fef3c7', text: '#b45309' },
  'bangladesh-affairs': { icon: 'bi-flag-fill', bg: '#ecfdf5', text: '#047857' },
  'international-affairs': { icon: 'bi-globe-americas', bg: '#eff6ff', text: '#1d4ed8' },
  'mental-ability': { icon: 'bi-puzzle-fill', bg: '#fff7ed', text: '#c2410c' },
  'general-science': { icon: 'bi-lightbulb-fill', bg: '#e0f2fe', text: '#0369a1' },
  ict: { icon: 'bi-cpu-fill', bg: '#ecfeff', text: '#0e7490' },
  'geography-environment': { icon: 'bi-tree-fill', bg: '#f0fdf4', text: '#166534' },
  'ethics-governance': { icon: 'bi-shield-check', bg: '#e0e7ff', text: '#4338ca' },
  'technical-subjects': { icon: 'bi-gear-wide-connected', bg: '#fdf2f8', text: '#be185d' },
};

const SubjectsPage = () => {
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

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

  const filteredSubjects = subjects.filter((s) => {
    if (!searchQuery.trim()) return true;
    const q = searchQuery.toLowerCase();
    return (
      s.name.toLowerCase().includes(q) ||
      (s.description && s.description.toLowerCase().includes(q))
    );
  });

  return (
    <div className="container py-4 py-lg-5">
      {/* Header */}
      <div className="text-center mb-5">
        <div className="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-indigo-subtle text-primary fw-semibold small mb-2 bangla-text" style={{ background: '#e0e7ff', color: '#4f46e5' }}>
          <i className="bi bi-mortarboard-fill"></i> চাকরি পরীক্ষার পূর্ণাঙ্গ বিষয়ভিত্তিক সিলেবাস
        </div>
        <h1 className="fw-extrabold text-dark bangla-text display-6 mb-3" style={{ fontWeight: 800 }}>
          বিষয়ভিত্তিক সিলেবাস ও ডিজিটাল মাস্টার বইসমূহ
        </h1>
        <p className="text-muted bangla-text mx-auto fs-6" style={{ maxWidth: '720px', lineHeight: '1.8' }}>
          বিসিএস প্রিলিমিনারির ১০টি বিষয়, বাংলাদেশ ব্যাংক, সাধারণ ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ এবং এনটিআরসিএ পরীক্ষার বিষয়ভিত্তিক সম্পূর্ণ অধ্যায় ও স্টাডি গাইড।
        </p>

        {/* Search Bar */}
        <div className="mt-4 mx-auto" style={{ maxWidth: '480px' }}>
          <div className="input-group shadow-sm rounded-pill overflow-hidden border">
            <span className="input-group-text bg-white border-0 ps-3 text-muted">
              <i className="bi bi-search"></i>
            </span>
            <input
              type="text"
              className="form-control border-0 bangla-text py-2 pe-3"
              placeholder="বিষয় বা সিলেবাস খুঁজুন (যেমন: বাংলা, সংবিধান, গণিত...)"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
            {searchQuery && (
              <button className="btn btn-white border-0 pe-3 text-muted" onClick={() => setSearchQuery('')}>
                <i className="bi bi-x"></i>
              </button>
            )}
          </div>
        </div>
      </div>

      {loading ? (
        <LoadingSpinner text="বিষয়সমূহ লোড করা হচ্ছে..." />
      ) : filteredSubjects.length === 0 ? (
        <EmptyState
          title="কোন বিষয় পাওয়া যায়নি"
          message={
            searchQuery
              ? `"${searchQuery}" এর সাথে সম্পর্কিত কোনো বিষয় পাওয়া যায়নি।`
              : 'বর্তমানে কোনো প্রকাশিত বিষয় পাওয়া যায়নি।'
          }
        />
      ) : (
        <div className="row g-4">
          {filteredSubjects.map((subject) => {
            const visual = SUBJECT_ICONS[subject.slug] || {
              icon: 'bi-journal-bookmark-fill',
              bg: '#e0e7ff',
              text: '#4f46e5',
            };

            return (
              <div className="col-md-6 col-lg-4" key={subject.id}>
                <div className="card h-100 border-0 shadow-sm rounded-4 card-hover p-4 bg-white d-flex flex-column">
                  <div className="card-body p-0 d-flex flex-column">
                    {/* Top Row: Icon + Counters */}
                    <div className="d-flex justify-content-between align-items-start mb-3">
                      <div
                        className="rounded-4 p-3 fs-3 d-flex align-items-center justify-content-center shadow-sm"
                        style={{ backgroundColor: visual.bg, color: visual.text, width: '58px', height: '58px' }}
                      >
                        <i className={`bi ${visual.icon}`}></i>
                      </div>
                      <div className="d-flex flex-column align-items-end gap-1">
                        <span className="badge bg-light text-dark border px-2.5 py-1 small rounded-pill">
                          <i className="bi bi-diagram-3 me-1 text-primary"></i>
                          {subject.topics_count || 0} টি টপিক
                        </span>
                        <span className="badge bg-light text-dark border px-2.5 py-1 small rounded-pill">
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
                        className="btn btn-outline-primary bangla-text w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center gap-2"
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
