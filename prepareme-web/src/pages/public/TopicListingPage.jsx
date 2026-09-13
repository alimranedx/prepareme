import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';

const TopicListingPage = () => {
  const { slug } = useParams();
  const [subject, setSubject] = useState(null);
  const [topics, setTopics] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    const fetchTopics = async () => {
      setLoading(true);
      try {
        const response = await apiClient.get(`/subjects/${slug}/topics`);
        setSubject(response.data.subject);
        setTopics(response.data.data || []);
      } catch (err) {
        console.error('Failed to load topics', err);
      } finally {
        setLoading(false);
      }
    };

    fetchTopics();
  }, [slug]);

  const filteredTopics = topics.filter((topic) => {
    if (!searchQuery) return true;
    const query = searchQuery.toLowerCase();
    const matchesTopic = topic.name.toLowerCase().includes(query) || (topic.description && topic.description.toLowerCase().includes(query));
    const matchesSubtopics = topic.children && topic.children.some((child) => child.name.toLowerCase().includes(query));
    return matchesTopic || matchesSubtopics;
  });

  return (
    <div className="container py-5">
      {/* Breadcrumb */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text">
          <li className="breadcrumb-item"><Link to="/" className="text-decoration-none">হোম</Link></li>
          <li className="breadcrumb-item"><Link to="/subjects" className="text-decoration-none">বিষয়সমূহ</Link></li>
          <li className="breadcrumb-item active" aria-current="page">{subject?.name || 'টপিক তালিকা'}</li>
        </ol>
      </nav>

      {/* Subject Header */}
      <div className="bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
        <div className="row align-items-center gy-3">
          <div className="col-lg-8">
            <h2 className="fw-bold text-dark bangla-text mb-2">
              <i className="bi bi-book text-primary me-2"></i>
              {subject?.name}
            </h2>
            <p className="text-muted bangla-text mb-0">
              {subject?.description || 'এই বিষয়ের সকল অধ্যায়, সাবটপিক এবং বিস্তারিত স্টাডি গাইডসমূহ।'}
            </p>
          </div>
          <div className="col-lg-4">
            <div className="input-group">
              <span className="input-group-text bg-light border-end-0"><i className="bi bi-search"></i></span>
              <input
                type="text"
                className="form-control border-start-0 bangla-text"
                placeholder="টপিক বা অধ্যায় খুঁজুন..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </div>
          </div>
        </div>
      </div>

      {loading ? (
        <LoadingSpinner text="অধ্যায়সমূহ লোড হচ্ছে..." />
      ) : filteredTopics.length === 0 ? (
        <EmptyState
          title="কোন অধ্যায় বা টপিক পাওয়া যায়নি"
          message="আপনার অনুসন্ধানের সাথে মিলে এমন কোনো টপিক পাওয়া যায়নি।"
        />
      ) : (
        <div className="row g-4">
          {filteredTopics.map((topic) => (
            <div className="col-12" key={topic.id}>
              <div className="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div className="card-header bg-light border-bottom py-3 px-4">
                  <div className="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 className="fw-bold bangla-text text-dark mb-0 d-flex align-items-center gap-2">
                      <span className="badge bg-primary rounded-pill px-3 py-2">{topic.sort_order || 1}</span>
                      <span>{topic.name}</span>
                    </h5>
                    <div className="d-flex gap-2">
                      <span className="badge bg-white text-secondary border px-3 py-2 rounded-pill">
                        <i className="bi bi-file-text me-1 text-primary"></i>
                        {topic.study_guides_count || 0} টি গাইড
                      </span>
                      <span className="badge bg-white text-secondary border px-3 py-2 rounded-pill">
                        <i className="bi bi-patch-question me-1 text-success"></i>
                        {topic.public_questions_count || 0} টি প্রশ্ন
                      </span>
                    </div>
                  </div>
                  {topic.description && (
                    <p className="text-muted small bangla-text mt-2 mb-0">{topic.description}</p>
                  )}
                </div>

                <div className="card-body p-4">
                  {/* Nested Subtopics */}
                  {topic.children && topic.children.length > 0 && (
                    <div className="mb-4">
                      <h6 className="fw-bold bangla-text text-secondary mb-3">সাব-টপিকসমূহ:</h6>
                      <div className="row g-3">
                        {topic.children.map((subtopic) => (
                          <div className="col-md-6 col-lg-4" key={subtopic.id}>
                            <div className="p-3 rounded-3 bg-light border h-100">
                              <h6 className="fw-bold bangla-text text-dark mb-1">
                                <i className="bi bi-diagram-2 text-primary me-2"></i>
                                {subtopic.name}
                              </h6>
                              <p className="text-muted small bangla-text mb-2 line-clamp-2">
                                {subtopic.description || 'অধ্যায়ভিত্তিক আলোচনা'}
                              </p>
                              {subtopic.study_guides && subtopic.study_guides.length > 0 && (
                                <div className="mt-2">
                                  {subtopic.study_guides.map((guide) => (
                                    <Link
                                      key={guide.id}
                                      to={`/study-guides/${guide.slug || guide.id}`}
                                      className="btn btn-sm btn-outline-primary bangla-text w-100 rounded-pill mb-1"
                                    >
                                      <i className="bi bi-journal-text me-1"></i> {guide.title}
                                    </Link>
                                  ))}
                                </div>
                              )}
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Direct Study Guides for this topic */}
                  {topic.study_guides && topic.study_guides.length > 0 && (
                    <div>
                      <h6 className="fw-bold bangla-text text-secondary mb-3">স্টাডি গাইডসমূহ:</h6>
                      <div className="list-group list-group-flush rounded-3 border">
                        {topic.study_guides.map((guide) => (
                          <Link
                            key={guide.id}
                            to={`/study-guides/${guide.slug || guide.id}`}
                            className="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 bangla-text"
                          >
                            <div className="d-flex align-items-center gap-3">
                              <span className="p-2 bg-primary-subtle text-primary rounded-3">
                                <i className="bi bi-file-earmark-richtext fs-5"></i>
                              </span>
                              <div>
                                <h6 className="fw-bold text-dark mb-1">{guide.title}</h6>
                                <p className="text-muted small mb-0 line-clamp-1">{guide.summary}</p>
                              </div>
                            </div>
                            <span className="btn btn-sm btn-light border bangla-text rounded-pill">
                              পড়ুন <i className="bi bi-chevron-right ms-1"></i>
                            </span>
                          </Link>
                        ))}
                      </div>
                    </div>
                  )}

                  {(!topic.children || topic.children.length === 0) && (!topic.study_guides || topic.study_guides.length === 0) && (
                    <p className="text-muted small bangla-text mb-0 italic">
                      এই টপিকে কোনো স্টাডি উপাদান এখনও যুক্ত করা হয়নি।
                    </p>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default TopicListingPage;
