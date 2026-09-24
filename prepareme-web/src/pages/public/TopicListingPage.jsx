import React, { useState, useEffect } from 'react';
import { useParams, Link, useSearchParams } from 'react-router-dom';
import apiClient from '../../api/client';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import EmptyState from '../../components/common/EmptyState';

const EXAM_FILTERS = [
  { id: 'all', label: 'সকল সিলেবাস', icon: 'bi-grid-fill' },
  { id: 'bcs', label: 'বিসিএস প্রিলিমিনারি', icon: 'bi-award-fill' },
  { id: 'bank', label: 'ব্যাংক জব প্রস্তুতি', icon: 'bi-bank' },
  { id: 'primary', label: 'প্রাইমারি শিক্ষক', icon: 'bi-mortarboard-fill' },
  { id: 'ntrca', label: 'শিক্ষক নিবন্ধন (NTRCA)', icon: 'bi-card-checklist' },
];

const TopicListingPage = () => {
  const { slug, subjectSlug } = useParams();
  const activeSlug = slug || subjectSlug;
  const [searchParams, setSearchParams] = useSearchParams();

  const [subject, setSubject] = useState(null);
  const [chapters, setChapters] = useState([]);
  const [topics, setTopics] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilter, setActiveFilter] = useState('all');
  const [selectedChapterId, setSelectedChapterId] = useState(searchParams.get('chapter') || 'all');

  // Sync selectedChapterId when URL search params change
  useEffect(() => {
    const chapFromUrl = searchParams.get('chapter') || 'all';
    setSelectedChapterId(chapFromUrl);
  }, [searchParams]);

  // Topic previous questions accordion/modal state
  const [activeTopicQuestions, setActiveTopicQuestions] = useState(null);
  const [loadingQuestions, setLoadingQuestions] = useState(false);
  const [topicQuestionsData, setTopicQuestionsData] = useState([]);

  // Handler for chapter selection that updates state and URL search params
  const handleChapterSelect = (chapterId) => {
    const newId = String(chapterId);
    setSelectedChapterId(newId);
    const newParams = new URLSearchParams(searchParams);
    if (newId === 'all') {
      newParams.delete('chapter');
    } else {
      newParams.set('chapter', newId);
    }
    setSearchParams(newParams);
  };

  useEffect(() => {
    let isMounted = true;

    const fetchData = async () => {
      setLoading(true);
      setError(null);
      try {
        // Fetch topics and chapters concurrently
        const [topicsRes, chaptersRes] = await Promise.all([
          apiClient.get(`/subjects/${activeSlug}/topics`),
          apiClient.get(`/subjects/${activeSlug}/chapters`).catch(() => ({ data: { data: [] } })),
        ]);

        if (isMounted) {
          setSubject(topicsRes.data.subject || null);
          setTopics(topicsRes.data.data || []);
          setChapters(chaptersRes.data.data || []);
        }
      } catch (err) {
        console.error('Failed to load topics and chapters', err);
        if (isMounted) {
          setError(
            err.response?.status === 404
              ? 'বিষয়টি খুঁজে পাওয়া যায়নি।'
              : 'টপিক ও সিলেবাস লোড করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'
          );
        }
      } finally {
        if (isMounted) {
          setLoading(false);
        }
      }
    };

    if (activeSlug) {
      fetchData();
    }

    return () => {
      isMounted = false;
    };
  }, [activeSlug]);

  // Load previous exam questions for a specific topic
  const handleToggleTopicQuestions = async (topicId) => {
    if (activeTopicQuestions === topicId) {
      setActiveTopicQuestions(null);
      return;
    }

    setActiveTopicQuestions(topicId);
    setLoadingQuestions(true);
    setTopicQuestionsData([]);

    try {
      const res = await apiClient.get(`/topics/${topicId}/previous-questions`);
      setTopicQuestionsData(res.data.data || []);
    } catch (err) {
      console.error('Failed to load topic questions', err);
    } finally {
      setLoadingQuestions(false);
    }
  };

  // Filter topics based on search, chapter, and exam
  const filteredTopics = topics.filter((topic) => {
    // 1. Chapter Filter
    if (selectedChapterId !== 'all') {
      if (topic.chapter_id !== parseInt(selectedChapterId, 10)) {
        return false;
      }
    }

    // 2. Search Query filter
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      const matchesTopic =
        topic.name.toLowerCase().includes(q) ||
        (topic.description && topic.description.toLowerCase().includes(q));
      const matchesChildren =
        topic.children &&
        topic.children.some(
          (c) =>
            c.name.toLowerCase().includes(q) ||
            (c.description && c.description.toLowerCase().includes(q))
        );
      if (!matchesTopic && !matchesChildren) return false;
    }

    // 3. Exam category filter
    if (activeFilter === 'bcs') {
      return true;
    }
    if (activeFilter === 'bank') {
      if (['english', 'mathematics', 'ict', 'general-knowledge'].includes(subject?.slug)) {
        return true;
      }
      if (subject?.slug === 'bangla') {
        return (
          topic.chapter?.slug === 'bangla-grammar-and-composition' ||
          topic.chapter?.name?.includes('ব্যাকরণ') ||
          ['poetry-and-poets', 'bangla-prose-and-essays', 'bangla-novels', 'literary-titles-and-pseudonyms', 'famous-books-and-authors'].includes(topic.slug) ||
          Boolean(topic.is_high_yield)
        );
      }
      return true;
    }
    if (activeFilter === 'primary' || activeFilter === 'ntrca') {
      return true;
    }

    return true;
  });

  // Smooth scroll to anchor topic if present in URL
  useEffect(() => {
    if (!loading && window.location.hash) {
      const targetId = window.location.hash.replace('#', '');
      const el = document.getElementById(targetId);
      if (el) {
        setTimeout(() => {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
      }
    }
  }, [loading]);

  const totalGuidesCount = topics.reduce(
    (acc, t) =>
      acc +
      (t.study_guides_count || (t.study_guides ? t.study_guides.length : 0)) +
      (t.children ? t.children.reduce((cAcc, c) => cAcc + (c.study_guides?.length || 0), 0) : 0),
    0
  );

  const totalQuestionsCount = topics.reduce(
    (acc, t) => acc + (t.public_questions_count || 0),
    0
  );

  return (
    <div className="container py-4 py-lg-5">
      {/* Dynamic Accessible Breadcrumb with Instant Back Jumps */}
      {(() => {
        const selectedChapter = chapters.find((c) => String(c.id) === String(selectedChapterId));
        return (
          <nav aria-label="breadcrumb" className="mb-4">
            <ol className="breadcrumb bangla-text small bg-white p-3 rounded-4 shadow-sm border mb-0 flex-wrap">
              <li className="breadcrumb-item">
                <Link to="/" className="text-decoration-none text-muted d-inline-flex align-items-center">
                  <i className="bi bi-house-door me-1"></i>হোম
                </Link>
              </li>
              <li className="breadcrumb-item">
                <Link to="/subjects" className="text-decoration-none text-muted">
                  বিষয়সমূহ
                </Link>
              </li>
              {selectedChapter ? (
                <>
                  <li className="breadcrumb-item">
                    <button
                      type="button"
                      className="btn btn-link p-0 text-decoration-none text-primary bangla-text fw-normal border-0 align-baseline"
                      onClick={() => handleChapterSelect('all')}
                      title="সকল খণ্ড ও টপিকে ফিরে যান"
                    >
                      {subject?.name || 'টপিক ও সিলেবাস'}
                    </button>
                  </li>
                  <li className="breadcrumb-item active fw-bold text-dark" aria-current="page">
                    {selectedChapter.name}
                  </li>
                </>
              ) : (
                <li className="breadcrumb-item active fw-bold text-dark" aria-current="page">
                  {subject?.name || 'টপিক ও সিলেবাস'}
                </li>
              )}
            </ol>
          </nav>
        );
      })()}

      {/* Error Banner */}
      {error && (
        <div className="alert alert-danger border-0 rounded-4 shadow-sm p-4 bangla-text mb-4">
          <div className="d-flex align-items-center gap-3">
            <i className="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
            <div className="flex-grow-1">
              <h5 className="fw-bold mb-1">ত্রুটি ঘটেছে</h5>
              <p className="mb-0">{error}</p>
            </div>
            <Link to="/subjects" className="btn btn-outline-danger btn-sm rounded-pill px-3">
              সকল বিষয় দেখুন
            </Link>
          </div>
        </div>
      )}

      {/* Hero Subject Header */}
      {subject && (
        <div className="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white">
          <div className="row align-items-center gy-4">
            <div className="col-lg-8">
              <div className="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span className="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold">
                  <i className="bi bi-book-half me-1"></i>ডিজিটাল টেক্সটবুক
                </span>
                <span className="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                  <i className="bi bi-patch-check-fill me-1"></i>বিসিএস ও ব্যাংক প্রস্তুতি
                </span>
              </div>
              <h1 className="fw-bold text-dark bangla-text display-6 mb-3">
                {subject.name}
              </h1>
              <p className="text-muted bangla-text fs-6 mb-4" style={{ lineHeight: '1.8' }}>
                {subject.description ||
                  'বিসিএস, সরকারি ও বেসরকারি ব্যাংক, প্রাথমিক সহকারী শিক্ষক নিয়োগ এবং এনটিআরসিএ পরীক্ষার পূর্ণাঙ্গ বিষয়ভিত্তিক আলোচনা ও অনুশীলন।'}
              </p>
              
              {/* High-Contrast Quick Stats */}
              <div className="d-flex flex-wrap gap-3">
                {chapters.length > 0 && (
                  <div className="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light border">
                    <i className="bi bi-journals text-primary fs-5"></i>
                    <span className="bangla-text small fw-bold text-dark">
                      {chapters.length} টি প্রধান পত্র / অংশ
                    </span>
                  </div>
                )}
                <div className="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light border">
                  <i className="bi bi-diagram-3-fill text-info fs-5"></i>
                  <span className="bangla-text small fw-bold text-dark">
                    {topics.length} টি টপিক
                  </span>
                </div>
                <div className="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light border">
                  <i className="bi bi-journal-text text-success fs-5"></i>
                  <span className="bangla-text small fw-bold text-dark">
                    {totalGuidesCount} টি স্টাডি গাইড
                  </span>
                </div>
                <div className="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light border">
                  <i className="bi bi-patch-question-fill text-warning fs-5"></i>
                  <span className="bangla-text small fw-bold text-dark">
                    {totalQuestionsCount} টি বিগত সালের প্রশ্ন
                  </span>
                </div>
              </div>
            </div>

            {/* Search Input */}
            <div className="col-lg-4">
              <div className="p-4 rounded-4 bg-light border">
                <label className="fw-bold bangla-text text-dark mb-2 small d-block">
                  অধ্যায় বা টপিক খুঁজুন
                </label>
                <div className="input-group">
                  <span className="input-group-text bg-white border-end-0 text-muted">
                    <i className="bi bi-search"></i>
                  </span>
                  <input
                    type="text"
                    className="form-control border-start-0 bangla-text bg-white"
                    placeholder="যেমন: সন্ধি, শতকরা, চর্যাপদ..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                  />
                  {searchQuery && (
                    <button
                      className="btn btn-outline-secondary border-start-0 bg-white"
                      type="button"
                      onClick={() => setSearchQuery('')}
                    >
                      <i className="bi bi-x"></i>
                    </button>
                  )}
                </div>
                <div className="mt-2 text-muted small bangla-text">
                  খুঁজুন বিষয়, সূত্র বা পরীক্ষার শর্টকাট
                </div>
              </div>
            </div>
          </div>

          {/* Chapter Tabs Navigation (Digital Textbook Experience) */}
          {chapters.length > 0 && (
            <div className="mt-4 pt-4 border-top">
              <div className="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div className="d-flex align-items-center gap-2">
                  <i className="bi bi-journal-bookmark-fill text-primary fs-5"></i>
                  <h6 className="bangla-text fw-bold text-dark mb-0">পত্র বা খণ্ড নির্বাচন করুন:</h6>
                </div>
                <span className="badge bg-light text-muted border bangla-text">
                  মোট {chapters.length}টি প্রধান অংশ ও {topics.length}টি টপিক
                </span>
              </div>
              <div className="d-flex gap-2 flex-wrap" role="tablist">
                <button
                  type="button"
                  className={`btn bangla-text rounded-4 px-3 py-2 fw-semibold d-flex align-items-center gap-2 ${selectedChapterId === 'all' ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-light'}`}
                  onClick={() => handleChapterSelect('all')}
                >
                  <i className="bi bi-grid-fill"></i>
                  সকল পত্র ও খণ্ড ({topics.length})
                </button>
                {chapters.map((chap) => (
                  <button
                    key={chap.id}
                    type="button"
                    className={`btn bangla-text rounded-4 px-3 py-2 fw-semibold d-flex align-items-center gap-2 ${selectedChapterId === String(chap.id) ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-light'}`}
                    onClick={() => handleChapterSelect(chap.id)}
                  >
                    <i className={chap.slug?.includes('grammar') ? "bi bi-pencil-square" : "bi bi-book"}></i>
                    {chap.name}
                    <span className={`badge rounded-pill ms-1 ${selectedChapterId === String(chap.id) ? 'bg-white text-primary' : 'bg-secondary text-white'}`}>
                      {chap.topics_count || 0}
                    </span>
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Exam Filter Pills */}
          <div className="mt-3 pt-3 border-top">
            <div className="d-flex align-items-center gap-2 flex-wrap">
              <span className="bangla-text small fw-bold text-muted me-2">পরীক্ষার ফোকাস:</span>
              {EXAM_FILTERS.map((filter) => (
                <button
                  key={filter.id}
                  type="button"
                  className={`exam-pill bangla-text ${activeFilter === filter.id ? 'active' : ''}`}
                  onClick={() => setActiveFilter(filter.id)}
                >
                  <i className={`bi ${filter.icon} me-1`}></i>
                  {filter.label}
                </button>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Main Content Area */}
      {loading ? (
        <LoadingSpinner text="অধ্যায় ও সিলেবাস প্রস্তুত করা হচ্ছে..." />
      ) : filteredTopics.length === 0 ? (
        <EmptyState
          title="কোন টপিক খুঁজে পাওয়া যায়নি"
          message={
            searchQuery
              ? `"${searchQuery}" এর সাথে সম্পর্কিত কোনো টপিক বা অধ্যায় পাওয়া যায়নি।`
              : 'নির্বাচিত পত্র বা ফিল্টারের জন্য বর্তমানে কোনো টপিক তালিকাভুক্ত নেই।'
          }
        />
      ) : (
        <div className="row g-4">
          {filteredTopics.map((topic, index) => {
            const topicGuides = topic.study_guides || [];
            const subtopics = topic.children || [];
            const isHighYield = topic.is_high_yield;
            const isQuestionsOpen = activeTopicQuestions === topic.id;
            const isNewChapterSection =
              selectedChapterId === 'all' &&
              (index === 0 || filteredTopics[index - 1]?.chapter_id !== topic.chapter_id);

            return (
              <React.Fragment key={topic.id}>
                {isNewChapterSection && topic.chapter && (
                  <div className="col-12 mt-4 pt-2">
                    <div className="p-4 rounded-4 bg-dark text-white d-flex align-items-center justify-content-between shadow-sm flex-wrap gap-3 border-start border-primary border-5">
                      <div className="d-flex align-items-center gap-3">
                        <span className="badge bg-primary text-white rounded-circle p-3 fs-4">
                          <i className={topic.chapter?.slug?.includes('grammar') ? "bi bi-pencil-square" : "bi bi-book-half"}></i>
                        </span>
                        <div>
                          <div className="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span className="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 small fw-bold">
                              {topic.chapter?.slug?.includes('grammar') ? '২য় খণ্ড: ব্যাকরণ ও নির্মিতি' : '১ম খণ্ড: বাংলা সাহিত্য'}
                            </span>
                            <h3 className="fw-bold bangla-text text-white mb-0 fs-4">
                              {topic.chapter.name}
                            </h3>
                          </div>
                          <p className="small text-white-50 bangla-text mb-0">
                            {topic.chapter.description}
                          </p>
                        </div>
                      </div>
                      <button 
                        type="button" 
                        className="btn btn-sm btn-outline-light rounded-pill px-3 bangla-text fw-semibold"
                        onClick={() => handleChapterSelect(topic.chapter_id)}
                      >
                        শুধু এই খণ্ডটি দেখুন <i className="bi bi-arrow-right ms-1"></i>
                      </button>
                    </div>
                  </div>
                )}
                <div className="col-12">
                  <div id={`topic-${topic.id}`} className={`card border-0 shadow-sm rounded-4 overflow-hidden bg-white ${isHighYield ? 'border-start border-primary border-4' : ''}`}>
                  {/* Topic Card Header */}
                  <div className="card-header bg-white border-bottom p-4">
                    <div className="d-flex justify-content-between align-items-start flex-wrap gap-3">
                      <div className="d-flex align-items-start gap-3">
                        <span className="badge bg-primary text-white rounded-3 px-3 py-2 fs-6 fw-bold">
                          {String(index + 1).padStart(2, '0')}
                        </span>
                        <div>
                          <div className="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h4 className="fw-bold bangla-text text-dark mb-0">
                              {topic.name}
                            </h4>
                            {isHighYield && (
                              <span className="badge-high-yield">
                                <i className="bi bi-stars"></i>৩-স্টার সর্বাধিক গুরুত্বপূর্ণ
                              </span>
                            )}
                            {topic.chapter && (
                              <span className="badge bg-light text-muted border small">
                                {topic.chapter.name}
                              </span>
                            )}
                          </div>
                          {topic.description && (
                            <p className="text-muted small bangla-text mb-0 mt-1" style={{ maxWidth: '750px' }}>
                              {topic.description}
                            </p>
                          )}
                        </div>
                      </div>

                      {/* 3-Step Learning Workflow Actions */}
                      <div className="d-flex align-items-center gap-2 flex-wrap">
                        {topicGuides.length > 0 ? (
                          <Link
                            to={`/study-guides/${topicGuides[0].slug || topicGuides[0].id}`}
                            className="btn btn-primary btn-sm rounded-pill px-3 bangla-text fw-semibold shadow-sm"
                            title="১ম ধাপ: এই টপিকের সম্পূর্ণ স্টাডি গাইড পড়ুন"
                          >
                            <i className="bi bi-book-half me-1"></i>১. স্টাডি গাইড পড়ুন
                          </Link>
                        ) : (
                          <span className="badge bg-light text-muted border bangla-text px-3 py-2 rounded-pill">
                            <i className="bi bi-journal-text me-1"></i>গাইড সংকলন চলছে
                          </span>
                        )}

                        <button
                          type="button"
                          className={`btn btn-sm rounded-pill px-3 bangla-text fw-semibold ${isQuestionsOpen ? 'btn-warning text-dark fw-bold' : 'btn-outline-primary'}`}
                          onClick={() => handleToggleTopicQuestions(topic.id)}
                          title="২য় ধাপ: এই টপিক থেকে বিগত বছরের বিসিএস ও পিএসসি প্রশ্নাবলি দেখুন"
                        >
                          <i className="bi bi-patch-question-fill me-1"></i>
                          ২. বিগত প্রশ্ন {isQuestionsOpen ? 'লুকান' : 'সমাধান'}
                        </button>

                        <Link
                          to={`/public-questions?topic_id=${topic.id}`}
                          className="btn btn-outline-success btn-sm rounded-pill px-3 bangla-text fw-semibold"
                          title="৩য় ধাপ: এই টপিকের ওপর কুইজ টেস্ট দিয়ে নিজেকে যাচাই করুন"
                        >
                          <i className="bi bi-stopwatch me-1"></i>৩. কুইজ টেস্ট
                        </Link>
                      </div>
                    </div>
                  </div>

                  {/* Previous Questions Drawer (If Opened) */}
                  {isQuestionsOpen && (
                    <div className="bg-light p-4 border-bottom border-warning-subtle">
                      <div className="d-flex align-items-center justify-content-between mb-3">
                        <div className="d-flex align-items-center gap-2">
                          <i className="bi bi-patch-check-fill text-warning fs-5"></i>
                          <h6 className="fw-bold bangla-text text-dark mb-0">
                            এই টপিক থেকে বিগত বছরের চাকরি পরীক্ষার প্রশ্ন ও ব্যাখ্যা:
                          </h6>
                        </div>
                        <button
                          type="button"
                          className="btn-close"
                          aria-label="Close"
                          onClick={() => setActiveTopicQuestions(null)}
                        ></button>
                      </div>

                      {loadingQuestions ? (
                        <div className="py-4 text-center">
                          <div className="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                          <span className="bangla-text text-muted small">বিগত সালের প্রশ্ন লোড হচ্ছে...</span>
                        </div>
                      ) : topicQuestionsData.length === 0 ? (
                        <div className="alert alert-info border-0 rounded-3 bangla-text mb-0">
                          <i className="bi bi-info-circle me-2"></i>
                          এই নির্দিষ্ট টপিক থেকে সরাসরি কোনো বিগত প্রশ্ন এখনও ট্যাগ করা হয়নি। শীঘ্রই আরও প্রশ্ন যুক্ত করা হবে।
                        </div>
                      ) : (
                        <div className="d-flex flex-column gap-3">
                          {topicQuestionsData.map((q, qIdx) => (
                            <div key={q.id} className="card border-0 shadow-sm rounded-3 p-3 bg-white">
                              <div className="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <span className="fw-bold text-dark bangla-text fs-6">
                                  {qIdx + 1}. {q.question}
                                </span>
                                {q.previous_exam_tag && (
                                  <span className="badge bg-primary text-white rounded-pill px-3 py-1 small text-nowrap">
                                    {q.previous_exam_tag}
                                  </span>
                                )}
                              </div>

                              {/* Options grid */}
                              {q.options && (
                                <div className="row g-2 mb-3">
                                  {Object.entries(q.options).map(([optKey, optVal]) => (
                                    <div className="col-md-6" key={optKey}>
                                      <div className={`p-2 rounded-2 border small bangla-text ${optKey === q.correct_option ? 'bg-success-subtle border-success fw-bold text-success-emphasis' : 'bg-light'}`}>
                                        <span className="fw-bold me-2">{optKey})</span>
                                        {optVal}
                                        {optKey === q.correct_option && (
                                          <i className="bi bi-check-circle-fill text-success ms-2"></i>
                                        )}
                                      </div>
                                    </div>
                                  ))}
                                </div>
                              )}

                              {/* Detailed Explanation */}
                              {q.explanation && (
                                <div className="p-3 rounded-2 bg-light-subtle border-start border-3 border-success bangla-text small">
                                  <span className="fw-bold text-success d-block mb-1">
                                    <i className="bi bi-lightbulb-fill me-1"></i>ব্যাখ্যা ও টেকনিক:
                                  </span>
                                  <p className="text-dark mb-0" style={{ lineHeight: '1.7' }}>
                                    {q.explanation}
                                  </p>
                                </div>
                              )}
                            </div>
                          ))}
                        </div>
                      )}
                    </div>
                  )}

                  {/* Topic Body */}
                  <div className="card-body p-4">
                    {/* Subtopics Section (if available) */}
                    {subtopics.length > 0 && (
                      <div className="mb-4">
                        <div className="d-flex align-items-center gap-2 mb-3">
                          <i className="bi bi-layers-fill text-primary"></i>
                          <h6 className="fw-bold bangla-text text-dark mb-0">
                            গুরুত্বপূর্ণ উপ-অধ্যায় ও পাঠ্যসূচি:
                          </h6>
                        </div>

                        <div className="row g-3">
                          {subtopics.map((subtopic) => {
                            const subGuides = subtopic.study_guides || [];
                            const parentGuide = topicGuides[0];
                            const guideToUse = subGuides.length > 0 ? subGuides[0] : parentGuide;

                            return (
                              <div className="col-md-6 col-lg-4" key={subtopic.id}>
                                <div className="p-3 rounded-4 bg-light border h-100 d-flex flex-column hover-lift">
                                  <div className="d-flex align-items-center justify-content-between mb-2">
                                    <h6 className="fw-bold bangla-text text-dark mb-0 d-flex align-items-center gap-1">
                                      <i className="bi bi-journal-bookmark text-primary me-1"></i>
                                      {subtopic.name}
                                    </h6>
                                    {subtopic.is_high_yield && (
                                      <span className="badge-high-yield small py-0.5">
                                        <i className="bi bi-star-fill me-1"></i>৩-স্টার
                                      </span>
                                    )}
                                  </div>
                                  <p className="text-muted small bangla-text flex-grow-1 mb-3 line-clamp-2" style={{ lineHeight: '1.6' }}>
                                    {subtopic.description || `${subtopic.name} সংক্রান্ত বিসিএস ও পিএসসি পরীক্ষার প্রয়োজনীয় আলোচনা ও প্রশ্নোত্তর।`}
                                  </p>

                                  {/* 3-Step Interactive Action Row for Subtopics */}
                                  <div className="d-flex flex-column gap-2 mt-auto pt-2 border-top">
                                    {guideToUse ? (
                                      <Link
                                        to={`/study-guides/${guideToUse.slug || guideToUse.id}`}
                                        className="btn btn-primary btn-sm bangla-text rounded-3 fw-semibold d-flex align-items-center justify-content-between shadow-xs"
                                        title={`${subtopic.name} এর স্টাডি গাইড পড়ুন`}
                                      >
                                        <span><i className="bi bi-book-half me-1"></i>১. স্টাডি গাইড পড়ুন</span>
                                        <i className="bi bi-arrow-right small"></i>
                                      </Link>
                                    ) : (
                                      <Link
                                        to={`/public-questions?topic_id=${subtopic.id}`}
                                        className="btn btn-primary btn-sm bangla-text rounded-3 fw-semibold d-flex align-items-center justify-content-between shadow-xs"
                                        title={`${subtopic.name} এর মূল বিষয়বস্তু ও প্রশ্নোত্তর পড়ুন`}
                                      >
                                        <span><i className="bi bi-book-half me-1"></i>১. স্টাডি গাইড পড়ুন</span>
                                        <i className="bi bi-arrow-right small"></i>
                                      </Link>
                                    )}

                                    <div className="d-flex gap-2">
                                      <Link
                                        to={`/public-questions?topic_id=${subtopic.id}`}
                                        className="btn btn-light border btn-sm bangla-text rounded-3 flex-grow-1 text-dark fw-semibold text-nowrap small d-flex align-items-center justify-content-center"
                                        title="এই টপিকের বিগত প্রশ্ন সমাধান করুন"
                                      >
                                        <i className="bi bi-patch-question-fill text-warning me-1"></i>২. বিগত প্রশ্ন
                                      </Link>

                                      <Link
                                        to={`/public-questions?topic_id=${subtopic.id}&question_type=mcq`}
                                        className="btn btn-light border btn-sm bangla-text rounded-3 flex-grow-1 text-success fw-semibold text-nowrap small d-flex align-items-center justify-content-center"
                                        title="এই টপিকের কুইজ টেস্ট দিন"
                                      >
                                        <i className="bi bi-stopwatch text-success me-1"></i>৩. কুইজ টেস্ট
                                      </Link>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            );
                          })}
                        </div>
                      </div>
                    )}

                    {/* Direct Guides on Parent Topic */}
                    {topicGuides.length > 0 && (
                      <div>
                        <div className="d-flex align-items-center gap-2 mb-3">
                          <i className="bi bi-book-half text-success"></i>
                          <h6 className="fw-bold bangla-text text-dark mb-0">
                            প্রধান স্টাডি গাইডসমূহ:
                          </h6>
                        </div>

                        <div className="list-group list-group-flush rounded-4 border overflow-hidden">
                          {topicGuides.map((guide) => (
                            <Link
                              key={guide.id}
                              to={`/study-guides/${guide.slug || guide.id}`}
                              className="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 p-md-4 bangla-text border-bottom"
                            >
                              <div className="d-flex align-items-start gap-3">
                                <div className="p-3 bg-primary-subtle text-primary rounded-3 fs-5">
                                  <i className="bi bi-journal-bookmark-fill"></i>
                                </div>
                                <div>
                                  <div className="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 className="fw-bold text-dark mb-0 fs-6">
                                      {guide.title}
                                    </h6>
                                    <span className="badge bg-success-subtle text-success small rounded-pill">
                                      পড়া আবশ্যক
                                    </span>
                                  </div>
                                  <p className="text-muted small mb-0 line-clamp-2">
                                    {guide.summary || 'পরীক্ষার জন্য প্রয়োজনীয় বিস্তারিত আলোচনা ও প্রশ্নোত্তর।'}
                                  </p>
                                </div>
                              </div>
                              <span className="btn btn-sm btn-primary rounded-pill px-3 bangla-text d-none d-sm-inline-flex align-items-center">
                                পড়ুন <i className="bi bi-arrow-right ms-1"></i>
                              </span>
                            </Link>
                          ))}
                        </div>
                      </div>
                    )}

                    {/* Quick Study Briefing if no dedicated guides */}
                    {subtopics.length === 0 && topicGuides.length === 0 && (
                      <div className="p-4 rounded-4 bg-light border bangla-text">
                        <div className="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                          <div className="d-flex align-items-center gap-2">
                            <i className="bi bi-lightbulb-fill text-warning fs-5"></i>
                            <h6 className="fw-bold text-dark mb-0">অধ্যায় সারসংক্ষেপ ও প্রস্তুতি নির্দেশিকা:</h6>
                          </div>
                          <Link
                            to={`/public-questions?topic_id=${topic.id}`}
                            className="btn btn-outline-primary btn-sm rounded-pill px-3"
                          >
                            <i className="bi bi-patch-question me-1"></i>এমসিকিউ অনুশীলন করুন
                          </Link>
                        </div>
                        <p className="text-muted small mb-0" style={{ lineHeight: '1.8' }}>
                          {topic.description || 'বিসিএস ও ব্যাংক নিয়োগ পরীক্ষায় এই অধ্যায় থেকে নিয়মিত প্রশ্ন এসে থাকে। গুরুত্বপূর্ণ সূত্র ও বিগত প্রশ্নাবলি নিয়মিত রিভিশন দিন।'}
                        </p>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </React.Fragment>
          );
          })}
        </div>
      )}
    </div>
  );
};

export default TopicListingPage;
