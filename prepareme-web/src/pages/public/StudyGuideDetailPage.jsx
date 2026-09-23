import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import { useAuth } from '../../context/AuthContext';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const StudyGuideDetailPage = () => {
  const { id, slug } = useParams();
  const guideIdentifier = id || slug;
  const { isAuthenticated } = useAuth();

  const [guide, setGuide] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isBookmarked, setIsBookmarked] = useState(false);
  const [progressPercent, setProgressPercent] = useState(0);
  const [progressStatus, setProgressStatus] = useState('not_started');
  const [updatingAction, setUpdatingAction] = useState(false);
  const [toastMessage, setToastMessage] = useState(null);

  // Sibling topics & syllabus navigation state
  const [siblingTopics, setSiblingTopics] = useState([]);
  const [chapters, setChapters] = useState([]);
  const [selectedChapterId, setSelectedChapterId] = useState('all');
  const [topicSearch, setTopicSearch] = useState('');
  const [loadingTopics, setLoadingTopics] = useState(false);

  useEffect(() => {
    let isMounted = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    const fetchSubjectSyllabus = async (subjectSlug) => {
      setLoadingTopics(true);
      try {
        const [topicsRes, chaptersRes] = await Promise.all([
          apiClient.get(`/subjects/${subjectSlug}/topics`),
          apiClient.get(`/subjects/${subjectSlug}/chapters`).catch(() => ({ data: { data: [] } })),
        ]);
        if (isMounted) {
          setSiblingTopics(topicsRes.data.data || []);
          setChapters(chaptersRes.data.data || []);
        }
      } catch (err) {
        console.error('Failed to fetch syllabus topics', err);
      } finally {
        if (isMounted) {
          setLoadingTopics(false);
        }
      }
    };

    const fetchGuide = async () => {
      setLoading(true);
      setError(null);
      try {
        const response = await apiClient.get(`/study-guides/${guideIdentifier}`);
        const data = response.data.data;
        if (isMounted) {
          setGuide(data);
          setIsBookmarked(data.is_bookmarked || false);
          if (data.user_progress) {
            setProgressPercent(data.user_progress.progress_percent || 0);
            setProgressStatus(data.user_progress.status || 'not_started');
          }
          if (data.topic?.subject?.slug) {
            fetchSubjectSyllabus(data.topic.subject.slug);
          }
        }
      } catch (err) {
        console.error('Failed to fetch study guide', err);
        if (isMounted) {
          setError(
            err.response?.status === 404
              ? 'স্টাডি গাইডটি খুঁজে পাওয়া যায়নি।'
              : 'স্টাডি গাইড লোড করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'
          );
        }
      } finally {
        if (isMounted) {
          setLoading(false);
        }
      }
    };

    if (guideIdentifier) {
      fetchGuide();
    }

    return () => {
      isMounted = false;
    };
  }, [guideIdentifier]);

  const handleToggleBookmark = async () => {
    if (!isAuthenticated) {
      setToastMessage('বুকমার্ক করতে অনুগ্রহ করে প্রথমে লগইন করুন।');
      return;
    }

    setUpdatingAction(true);
    try {
      if (isBookmarked) {
        await apiClient.delete(`/my/bookmarks/${guide.id}`);
        setIsBookmarked(false);
        setToastMessage('বুকমার্ক থেকে সরিয়ে ফেলা হয়েছে।');
      } else {
        await apiClient.post('/my/bookmarks', { study_guide_id: guide.id });
        setIsBookmarked(true);
        setToastMessage('বুকমার্কে যুক্ত করা হয়েছে।');
      }
    } catch (err) {
      setToastMessage('বুকমার্ক আপডেট করা যায়নি।');
    } finally {
      setUpdatingAction(false);
    }
  };

  const handleUpdateProgress = async (newPercent) => {
    if (!isAuthenticated) {
      setToastMessage('অগ্রগতি সংরক্ষণ করতে অনুগ্রহ করে লগইন করুন।');
      return;
    }

    setUpdatingAction(true);
    try {
      const response = await apiClient.put(`/my/progress/${guide.id}`, {
        progress_percent: newPercent,
      });
      setProgressPercent(response.data.data.progress_percent);
      setProgressStatus(response.data.data.status);
      setToastMessage(
        newPercent >= 100
          ? 'অভিনন্দন! অধ্যায়টি সম্পূর্ণ পড়া হয়েছে।'
          : `পড়ার অগ্রগতি ${newPercent}% এ আপডেট করা হয়েছে।`
      );
    } catch (err) {
      setToastMessage('অগ্রগতি আপডেট করা যায়নি।');
    } finally {
      setUpdatingAction(false);
    }
  };

  const getSectionCalloutClass = (type) => {
    switch (type) {
      case 'formula':
        return 'study-callout-formula';
      case 'summary':
      case 'mnemonic':
        return 'study-callout-mnemonic';
      case 'practice':
      case 'example':
        return 'study-callout-previous-exam';
      case 'warning':
        return 'study-callout-warning';
      case 'explanation':
      default:
        return 'study-callout-concept';
    }
  };

  const getSectionBadge = (type) => {
    switch (type) {
      case 'formula':
        return (
          <span className="badge bg-success text-white rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-calculator me-1"></i>শর্টকাট সূত্র
          </span>
        );
      case 'summary':
        return (
          <span className="badge bg-warning text-dark rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-lightbulb me-1"></i>সারসংক্ষেপ ও ট্রিক
          </span>
        );
      case 'practice':
        return (
          <span className="badge bg-primary text-white rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-patch-question me-1"></i>বিগত সালের প্রশ্ন
          </span>
        );
      case 'example':
        return (
          <span className="badge bg-info text-dark rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-card-text me-1"></i>বাস্তব উদাহরণ
          </span>
        );
      case 'warning':
        return (
          <span className="badge bg-danger text-white rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-exclamation-octagon me-1"></i>সাধারণ ভুল ও সতর্কতা
          </span>
        );
      default:
        return (
          <span className="badge bg-secondary text-white rounded-pill px-3 py-1 fw-semibold">
            <i className="bi bi-file-text me-1"></i>মৌলিক আলোচনা
          </span>
        );
    }
  };

  // Sibling topics filtering
  const filteredSiblingTopics = siblingTopics.filter((topicItem) => {
    const matchesChapter =
      selectedChapterId === 'all' || topicItem.chapter_id === Number(selectedChapterId);
    const matchesSearch =
      !topicSearch.trim() ||
      topicItem.name.toLowerCase().includes(topicSearch.toLowerCase()) ||
      (topicItem.description && topicItem.description.toLowerCase().includes(topicSearch.toLowerCase()));
    return matchesChapter && matchesSearch;
  });

  // Current topic's study guides list (Playlist)
  const topicFromSibling = siblingTopics.find((t) => t.id === guide?.topic_id);
  const currentTopicGuides =
    guide?.topic?.study_guides && guide.topic.study_guides.length > 0
      ? guide.topic.study_guides
      : topicFromSibling?.study_guides || [];

  const currentTopicGuideIdx = currentTopicGuides.findIndex(
    (g) => g.id === guide?.id || g.slug === guideIdentifier
  );
  const prevTopicGuide = currentTopicGuideIdx > 0 ? currentTopicGuides[currentTopicGuideIdx - 1] : null;
  const nextTopicGuide =
    currentTopicGuideIdx >= 0 && currentTopicGuideIdx < currentTopicGuides.length - 1
      ? currentTopicGuides[currentTopicGuideIdx + 1]
      : null;

  // Flat list of all guides in subject for next / prev navigation across topics
  const allGuidesInSubject = (siblingTopics.length > 0 ? siblingTopics : (guide?.topic ? [guide.topic] : []))
    .filter((t) => t.study_guides && t.study_guides.length > 0)
    .flatMap((t) =>
      t.study_guides.map((g) => ({
        ...g,
        topicName: t.name,
        topicId: t.id,
      }))
    );

  const currentOverallGuideIdx = allGuidesInSubject.findIndex(
    (g) => g.id === guide?.id || g.slug === guideIdentifier
  );

  const prevOverallGuide =
    currentOverallGuideIdx > 0 ? allGuidesInSubject[currentOverallGuideIdx - 1] : null;
  const nextOverallGuide =
    currentOverallGuideIdx >= 0 && currentOverallGuideIdx < allGuidesInSubject.length - 1
      ? allGuidesInSubject[currentOverallGuideIdx + 1]
      : null;

  if (loading) return <LoadingSpinner fullPage text="স্টাডি গাইড লোড হচ্ছে..." />;
  if (error || !guide) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger border-0 rounded-4 shadow-sm bangla-text mx-auto p-4 mb-4" style={{ maxWidth: '540px' }}>
          <i className="bi bi-exclamation-circle-fill text-danger fs-2 d-block mb-2"></i>
          <h5 className="fw-bold mb-2">স্টাডি গাইড পাওয়া যায়নি</h5>
          <p className="mb-0 text-muted">{error || 'অনুরোধকৃত স্টাডি গাইডটি বিদ্যমান নেই বা সরানো হয়েছে।'}</p>
        </div>
        <Link to="/subjects" className="btn btn-primary bangla-text rounded-pill px-4">
          <i className="bi bi-arrow-left me-1"></i>সকল বিষয়সমূহে ফিরে যান
        </Link>
      </div>
    );
  }

  return (
    <div className="container py-4 py-lg-5">
      {/* Dynamic Accessible Breadcrumb with Instant Back Jumps */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text small bg-white p-3 rounded-4 shadow-sm border mb-0 flex-wrap align-items-center">
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
          {guide.topic?.subject && (
            <li className="breadcrumb-item">
              <Link
                to={`/subjects/${guide.topic.subject.slug}`}
                className="text-decoration-none text-primary"
                title={`${guide.topic.subject.name} এর মূল সিলেবাসে যান`}
              >
                {guide.topic.subject.name}
              </Link>
            </li>
          )}
          {guide.topic?.chapter && (
            <li className="breadcrumb-item">
              <Link
                to={`/subjects/${guide.topic.subject?.slug || 'bangla'}?chapter=${guide.topic.chapter.id}`}
                className="text-decoration-none text-primary"
                title={`${guide.topic.chapter.name} খণ্ডে ফিরে যান`}
              >
                {guide.topic.chapter.name}
              </Link>
            </li>
          )}
          {guide.topic && (
            <li className="breadcrumb-item">
              <Link
                to={`/subjects/${guide.topic.subject?.slug || 'bangla'}?chapter=${guide.topic.chapter_id || ''}#topic-${guide.topic.id}`}
                className="text-decoration-none text-secondary"
                title={`${guide.topic.name} টপিকে ফিরে যান`}
              >
                {guide.topic.name}
              </Link>
            </li>
          )}
          <li className="breadcrumb-item active fw-bold text-dark text-truncate" style={{ maxWidth: '300px' }} aria-current="page" title={guide.title}>
            {guide.title}
          </li>
        </ol>
      </nav>

      {/* Toast notification */}
      {toastMessage && (
        <div className="position-fixed bottom-0 end-0 p-3" style={{ zIndex: 1080 }}>
          <div className="alert alert-dark alert-dismissible fade show shadow-lg bangla-text" role="alert">
            <i className="bi bi-info-circle-fill me-2 text-info"></i>
            {toastMessage}
            <button
              type="button"
              className="btn-close btn-close-white"
              onClick={() => setToastMessage(null)}
            ></button>
          </div>
        </div>
      )}

      <div className="row g-4">
        {/* Main Content Column */}
        <div className="col-lg-8">
          {/* Top Topic Lessons Stepper Bar (When topic has multiple guides) */}
          {currentTopicGuides.length > 1 && (
            <div className="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white border-start border-4 border-primary">
              <div className="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <div className="d-flex align-items-center gap-2">
                  <span className="badge bg-primary text-white bangla-text px-2 py-1 rounded-pill small">
                    <i className="bi bi-collection-play-fill me-1"></i>টপিক পাঠক্রম
                  </span>
                  <span className="fw-bold bangla-text small text-dark">
                    {guide.topic?.name} ({currentTopicGuides.length}টি পাঠের মধ্যে {currentTopicGuideIdx + 1} নম্বর পাঠ)
                  </span>
                </div>
                <div className="d-flex align-items-center gap-2">
                  {prevTopicGuide && (
                    <Link
                      to={`/study-guides/${prevTopicGuide.slug || prevTopicGuide.id}`}
                      className="btn btn-sm btn-outline-secondary rounded-pill px-3 bangla-text d-flex align-items-center gap-1"
                      title="পূর্ববর্তী পাঠ"
                    >
                      <i className="bi bi-chevron-left"></i>
                      <span>আগের পাঠ</span>
                    </Link>
                  )}
                  {nextTopicGuide && (
                    <Link
                      to={`/study-guides/${nextTopicGuide.slug || nextTopicGuide.id}`}
                      className="btn btn-sm btn-primary rounded-pill px-3 bangla-text fw-semibold d-flex align-items-center gap-1 shadow-sm"
                      title="পরবর্তী পাঠ"
                    >
                      <span>পরের পাঠ</span>
                      <i className="bi bi-chevron-right"></i>
                    </Link>
                  )}
                </div>
              </div>

              {/* Horizontal Pill Steps */}
              <div className="d-flex gap-2 overflow-auto pb-1 pt-1" style={{ scrollbarWidth: 'thin' }}>
                {currentTopicGuides.map((g, idx) => {
                  const isCurrent = g.id === guide.id || g.slug === guideIdentifier;
                  return (
                    <Link
                      key={g.id}
                      to={`/study-guides/${g.slug || g.id}`}
                      className={`btn btn-sm rounded-pill text-nowrap bangla-text px-3 py-1 text-decoration-none d-flex align-items-center gap-2 transition ${
                        isCurrent
                          ? 'btn-primary text-white shadow-sm fw-bold'
                          : 'btn-light border text-dark'
                      }`}
                      style={{ fontSize: '0.8rem' }}
                    >
                      <span
                        className={`badge rounded-circle p-0 d-inline-flex align-items-center justify-content-center ${
                          isCurrent ? 'bg-white text-primary fw-bold' : 'bg-secondary text-white'
                        }`}
                        style={{ width: '18px', height: '18px', fontSize: '0.65rem' }}
                      >
                        {idx + 1}
                      </span>
                      <span className="text-truncate" style={{ maxWidth: '240px' }} title={g.title}>
                        {g.title}
                      </span>
                      {isCurrent && <i className="bi bi-check-circle-fill text-white small ms-1"></i>}
                    </Link>
                  );
                })}
              </div>
            </div>
          )}

          <article className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            {/* Guide Meta Header */}
            <div className="d-flex justify-content-between align-items-start gap-3 mb-4">
              <div>
                <div className="d-flex align-items-center gap-2 mb-2 flex-wrap">
                  <span className="badge bg-primary text-white bangla-text px-3 py-1 rounded-pill">
                    {guide.topic?.name}
                  </span>
                  <span className="badge-high-yield">
                    <i className="bi bi-stars"></i>৩-স্টার গুরুত্বপূর্ণ
                  </span>
                </div>
                <h1 className="fw-bold text-dark bangla-text display-6 mb-2" style={{ lineHeight: '1.4' }}>
                  {guide.title}
                </h1>
                <p className="text-muted small bangla-text mb-0">
                  <i className="bi bi-calendar3 me-1"></i> প্রকাশিত:{' '}
                  {guide.published_at ? new Date(guide.published_at).toLocaleDateString('bn-BD') : 'সাম্প্রতিক সংস্করণ'}
                  <span className="mx-2">•</span>
                  <i className="bi bi-clock me-1"></i> আনুমানিক পাঠ সময়: ৫-৭ মিনিট
                </p>
              </div>

              {/* Bookmark Button */}
              <button
                className={`btn btn-lg rounded-circle shadow-sm flex-shrink-0 ${
                  isBookmarked ? 'btn-warning text-white' : 'btn-outline-secondary'
                }`}
                onClick={handleToggleBookmark}
                disabled={updatingAction}
                title={isBookmarked ? 'বুকমার্ক থেকে মুছুন' : 'বুকমার্ক করুন'}
              >
                <i className={`bi ${isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark'}`}></i>
              </button>
            </div>

            {/* Summary Box */}
            {guide.summary && (
              <div className="study-callout study-callout-concept my-4">
                <h6 className="fw-bold text-primary bangla-text mb-2">
                  <i className="bi bi-lightbulb-fill me-2 text-warning"></i> অধ্যায় পরিচিতি ও পরীক্ষার গুরুত্ব:
                </h6>
                <p className="mb-0 bangla-text text-dark" style={{ lineHeight: '1.7' }}>
                  {guide.summary}
                </p>
              </div>
            )}

            {/* Main Guide Content */}
            <div className="study-guide-content bangla-text fs-5 mb-5" style={{ lineHeight: '1.9', color: '#0f172a' }}>
              {guide.content.split('\n').map((paragraph, idx) =>
                paragraph.trim() ? (
                  <p key={idx} className="mb-3">
                    {paragraph}
                  </p>
                ) : (
                  <div key={idx} className="my-2" />
                )
              )}
            </div>

            {/* Structured Sections */}
            {guide.sections && guide.sections.length > 0 && (
              <div className="mt-5 pt-4 border-top">
                <h4 className="fw-bold text-dark bangla-text mb-4 d-flex align-items-center gap-2">
                  <i className="bi bi-layers-fill text-primary"></i>
                  <span>বিশ্লেষণ, সূত্র ও বিগত সালের প্রশ্ন</span>
                </h4>

                <div className="d-flex flex-column gap-3">
                  {guide.sections.map((section) => (
                    <div
                      key={section.id}
                      id={`section-${section.id}`}
                      className={`study-callout ${getSectionCalloutClass(section.section_type)}`}
                    >
                      <div className="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 className="fw-bold bangla-text mb-0">
                          {section.title}
                        </h5>
                        {getSectionBadge(section.section_type)}
                      </div>

                      <div
                        className="bangla-text"
                        style={{ lineHeight: '1.85', whiteSpace: 'pre-line', fontSize: '1.05rem' }}
                      >
                        {section.content}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Interactive MCQ Self-Check Footer */}
            <div className="mt-5 p-4 rounded-4 bg-light border text-center bangla-text">
              <i className="bi bi-check2-circle fs-2 text-primary d-block mb-2"></i>
              <h5 className="fw-bold text-dark mb-2">পড়া শেষ? নিজেকে যাচাই করুন!</h5>
              <p className="text-muted small mb-3">
                এই অধ্যায়ের ওপর বিগত বিসিএস, ব্যাংক ও প্রাইমারি নিয়োগ পরীক্ষার প্রশ্নাবলী দিয়ে এখনই স্ব-মূল্যায়ন টেস্ট দিন।
              </p>
              <Link
                to={`/public-questions?study_guide_id=${guide.id}`}
                className="btn btn-primary rounded-pill px-4 bangla-text fw-semibold"
              >
                <i className="bi bi-patch-question me-1"></i>এই অধ্যায়ের MCQ অনুশীলন করুন
              </Link>
            </div>

            {/* Sequential Lesson Navigation (Previous / Next) */}
            {(prevOverallGuide || nextOverallGuide) && (
              <div className="mt-4 pt-3 border-top">
                <div className="row g-3">
                  {prevOverallGuide ? (
                    <div className="col-sm-6">
                      <Link
                        to={`/study-guides/${prevOverallGuide.slug || prevOverallGuide.id}`}
                        className="card h-100 border text-decoration-none p-3 rounded-4 bg-light transition-hover"
                      >
                        <div className="small text-muted bangla-text mb-1 d-flex align-items-center gap-1">
                          <i className="bi bi-arrow-left"></i>
                          <span>পূর্ববর্তী পাঠ ({prevOverallGuide.topicName})</span>
                        </div>
                        <div className="fw-bold text-dark bangla-text text-truncate">
                          {prevOverallGuide.title}
                        </div>
                      </Link>
                    </div>
                  ) : (
                    <div className="col-sm-6 d-none d-sm-block" />
                  )}

                  {nextOverallGuide && (
                    <div className="col-sm-6 text-sm-end">
                      <Link
                        to={`/study-guides/${nextOverallGuide.slug || nextOverallGuide.id}`}
                        className="card h-100 border text-decoration-none p-3 rounded-4 bg-light transition-hover"
                      >
                        <div className="small text-muted bangla-text mb-1 d-flex align-items-center justify-content-sm-end gap-1">
                          <span>পরবর্তী পাঠ ({nextOverallGuide.topicName})</span>
                          <i className="bi bi-arrow-right"></i>
                        </div>
                        <div className="fw-bold text-dark bangla-text text-truncate">
                          {nextOverallGuide.title}
                        </div>
                      </Link>
                    </div>
                  )}
                </div>
              </div>
            )}
          </article>
        </div>

        {/* Sidebar: Related Topics, Progress & Navigation */}
        <aside className="col-lg-4">
          <div className="sticky-top" style={{ top: '80px' }}>
            {/* 1. Current Topic Lesson Playlist Card */}
            {currentTopicGuides.length > 0 && (
              <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-start border-4 border-primary">
                <div className="d-flex justify-content-between align-items-center mb-2">
                  <h6 className="fw-bold bangla-text text-dark mb-0 d-flex align-items-center gap-2">
                    <i className="bi bi-collection-play-fill text-primary"></i>
                    <span>এই টপিকের পাঠসমূহ</span>
                  </h6>
                  <span className="badge bg-primary text-white rounded-pill small px-2">
                    {currentTopicGuides.length} টি পাঠ
                  </span>
                </div>
                <div className="text-muted small bangla-text mb-3 text-truncate" title={guide.topic?.name}>
                  টপিক: <strong className="text-dark">{guide.topic?.name}</strong>
                </div>

                <div className="list-group list-group-flush small bangla-text pe-1" style={{ maxHeight: '360px', overflowY: 'auto' }}>
                  {currentTopicGuides.map((g, idx) => {
                    const isCurrent = g.id === guide.id || g.slug === guideIdentifier;
                    return (
                      <Link
                        key={g.id}
                        to={`/study-guides/${g.slug || g.id}`}
                        className={`list-group-item list-group-item-action px-3 py-2 rounded-3 mb-2 border text-decoration-none transition d-flex align-items-center justify-content-between ${
                          isCurrent
                            ? 'bg-primary text-white border-primary shadow-sm fw-bold'
                            : 'bg-light border-light-subtle text-dark'
                        }`}
                      >
                        <div className="d-flex align-items-start gap-2 overflow-hidden me-2">
                          <span
                            className={`badge rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1 ${
                              isCurrent ? 'bg-white text-primary fw-bold' : 'bg-white text-muted border'
                            }`}
                            style={{ width: '22px', height: '22px', fontSize: '0.7rem' }}
                          >
                            {idx + 1}
                          </span>
                          <span className="text-truncate" title={g.title}>
                            {g.title}
                          </span>
                        </div>
                        {isCurrent ? (
                          <span className="badge bg-white text-primary px-2 py-1 rounded-pill flex-shrink-0" style={{ fontSize: '0.65rem' }}>
                            পড়ছেন
                          </span>
                        ) : (
                          <span className="badge bg-white text-primary border px-2 py-1 rounded-pill flex-shrink-0" style={{ fontSize: '0.65rem' }}>
                            পড়ুন <i className="bi bi-arrow-right ms-1"></i>
                          </span>
                        )}
                      </Link>
                    );
                  })}
                </div>
              </div>
            )}

            {/* 2. Reading Progress Card */}
            <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
              <h5 className="fw-bold bangla-text text-dark mb-3">
                <i className="bi bi-graph-up text-success me-2"></i> পড়ার অগ্রগতি
              </h5>

              <div className="progress mb-3" style={{ height: '10px', backgroundColor: '#e2e8f0' }}>
                <div
                  className={`progress-bar ${
                    progressPercent >= 100 ? 'bg-success' : 'bg-primary'
                  }`}
                  role="progressbar"
                  style={{ width: `${progressPercent}%`, transition: 'width 0.4s ease' }}
                  aria-valuenow={progressPercent}
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>

              <div className="d-flex justify-content-between align-items-center mb-3">
                <span className="small text-muted bangla-text">
                  স্ট্যাটাস:{' '}
                  <strong className="text-dark">
                    {progressStatus === 'completed'
                      ? 'সম্পূর্ণ হয়েছে'
                      : progressStatus === 'in_progress'
                      ? 'চলমান'
                      : 'শুরু হয়নি'}
                  </strong>
                </span>
                <span className="fw-bold text-primary">{progressPercent}%</span>
              </div>

              {/* Progress Buttons */}
              <div className="d-grid gap-2">
                <div className="btn-group w-100" role="group">
                  <button
                    type="button"
                    className={`btn btn-sm ${
                      progressPercent === 25 ? 'btn-primary' : 'btn-outline-primary'
                    }`}
                    onClick={() => handleUpdateProgress(25)}
                    disabled={updatingAction}
                  >
                    ২৫%
                  </button>
                  <button
                    type="button"
                    className={`btn btn-sm ${
                      progressPercent === 50 ? 'btn-primary' : 'btn-outline-primary'
                    }`}
                    onClick={() => handleUpdateProgress(50)}
                    disabled={updatingAction}
                  >
                    ৫০%
                  </button>
                  <button
                    type="button"
                    className={`btn btn-sm ${
                      progressPercent === 75 ? 'btn-primary' : 'btn-outline-primary'
                    }`}
                    onClick={() => handleUpdateProgress(75)}
                    disabled={updatingAction}
                  >
                    ৭৫%
                  </button>
                </div>

                <button
                  type="button"
                  className={`btn ${
                    progressPercent >= 100 ? 'btn-outline-success' : 'btn-success text-white'
                  } bangla-text fw-semibold py-2 rounded-3`}
                  onClick={() => handleUpdateProgress(100)}
                  disabled={updatingAction}
                >
                  <i className="bi bi-check2-circle me-1"></i> সম্পূর্ণ পড়া হয়েছে (১০০%)
                </button>
              </div>
            </div>

            {/* 3. Full Subject Syllabus & Other Topics Card */}
            <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
              <div className="d-flex justify-content-between align-items-center mb-3">
                <h6 className="fw-bold bangla-text text-dark mb-0 d-flex align-items-center gap-2">
                  <i className="bi bi-journal-bookmark-fill text-primary"></i>
                  <span>অন্যান্য টপিক ও পাঠ্যসূচি</span>
                </h6>
                <span className="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small px-2">
                  {filteredSiblingTopics.length} টি টপিক
                </span>
              </div>

              {/* Chapter filter pills if available */}
              {chapters.length > 0 && (
                <div
                  className="d-flex gap-1 mb-3 overflow-auto pb-1"
                  style={{ scrollbarWidth: 'thin' }}
                >
                  <button
                    type="button"
                    className={`btn btn-xs rounded-pill px-2 py-1 bangla-text text-nowrap ${
                      selectedChapterId === 'all'
                        ? 'btn-primary text-white'
                        : 'btn-outline-secondary'
                    }`}
                    style={{ fontSize: '0.75rem' }}
                    onClick={() => setSelectedChapterId('all')}
                  >
                    সকল ({siblingTopics.length})
                  </button>
                  {chapters.map((ch) => {
                    const chapterCount = siblingTopics.filter(
                      (t) => t.chapter_id === ch.id
                    ).length;
                    return (
                      <button
                        key={ch.id}
                        type="button"
                        className={`btn btn-xs rounded-pill px-2 py-1 bangla-text text-nowrap ${
                          selectedChapterId === ch.id
                            ? 'btn-primary text-white'
                            : 'btn-outline-secondary'
                        }`}
                        style={{ fontSize: '0.75rem' }}
                        onClick={() => setSelectedChapterId(ch.id)}
                      >
                        {ch.name} ({chapterCount})
                      </button>
                    );
                  })}
                </div>
              )}

              {/* Search in Syllabus */}
              <div className="input-group input-group-sm mb-3">
                <span className="input-group-text bg-light border-end-0">
                  <i className="bi bi-search text-muted small"></i>
                </span>
                <input
                  type="text"
                  className="form-control bg-light border-start-0 bangla-text"
                  placeholder="পাঠ্যসূচিতে টপিক খুঁজুন..."
                  value={topicSearch}
                  onChange={(e) => setTopicSearch(e.target.value)}
                />
                {topicSearch && (
                  <button
                    className="btn btn-outline-secondary border-start-0"
                    type="button"
                    onClick={() => setTopicSearch('')}
                  >
                    <i className="bi bi-x"></i>
                  </button>
                )}
              </div>

              {/* Scrollable Topics List */}
              {loadingTopics ? (
                <div className="py-4 text-center">
                  <LoadingSpinner small text="টপিক তালিকা লোড হচ্ছে..." />
                </div>
              ) : filteredSiblingTopics.length === 0 ? (
                <div className="py-3 text-center text-muted small bangla-text">
                  কোনো টপিক পাওয়া যায়নি
                </div>
              ) : (
                <div
                  className="list-group list-group-flush small bangla-text pe-1"
                  style={{ maxHeight: '360px', overflowY: 'auto' }}
                >
                  {filteredSiblingTopics.map((topicItem, idx) => {
                    const isCurrentTopic =
                      topicItem.id === guide.topic_id ||
                      topicItem.study_guides?.some(
                        (g) => g.id === guide.id || g.slug === guideIdentifier
                      );
                    const targetGuide = topicItem.study_guides?.[0];
                    const targetUrl = targetGuide
                      ? `/study-guides/${targetGuide.slug || targetGuide.id}`
                      : `/public-questions?topic_id=${topicItem.id}`;

                    return (
                      <Link
                        key={topicItem.id}
                        to={targetUrl}
                        className={`list-group-item list-group-item-action px-2 py-2 rounded-3 mb-1 border-0 d-flex align-items-center justify-content-between text-decoration-none transition ${
                          isCurrentTopic
                            ? 'bg-primary text-white shadow-sm'
                            : 'text-dark'
                        }`}
                        style={{
                          backgroundColor: isCurrentTopic ? undefined : '#f8fafc',
                        }}
                      >
                        <div className="d-flex align-items-center gap-2 overflow-hidden me-2">
                          <span
                            className={`badge rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 ${
                              isCurrentTopic
                                ? 'bg-white text-primary fw-bold'
                                : 'bg-white text-muted border'
                            }`}
                            style={{ width: '22px', height: '22px', fontSize: '0.7rem' }}
                          >
                            {idx + 1}
                          </span>
                          <span className="text-truncate" title={topicItem.name}>
                            {topicItem.name}
                          </span>
                        </div>

                        <div className="d-flex align-items-center gap-1 flex-shrink-0">
                          {isCurrentTopic ? (
                            <span className="badge bg-white text-primary px-2 py-1 rounded-pill" style={{ fontSize: '0.65rem' }}>
                              পড়ছেন
                            </span>
                          ) : targetGuide ? (
                            <span className="badge bg-light text-secondary border px-1" style={{ fontSize: '0.65rem' }}>
                              <i className="bi bi-book me-1"></i>পড়ুন
                            </span>
                          ) : (
                            <span className="badge bg-light text-secondary border px-1" style={{ fontSize: '0.65rem' }}>
                              <i className="bi bi-patch-question me-1"></i>MCQ
                            </span>
                          )}
                        </div>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Quick Section Navigation */}
            {guide.sections && guide.sections.length > 0 && (
              <div className="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 className="fw-bold bangla-text text-dark mb-3">
                  <i className="bi bi-list-nested text-primary me-2"></i> পরিচ্ছেদসমূহ
                </h6>
                <div className="list-group list-group-flush small bangla-text">
                  {guide.sections.map((sec) => (
                    <a
                      key={sec.id}
                      href={`#section-${sec.id}`}
                      className="list-group-item list-group-item-action px-0 py-2 border-0 text-decoration-none text-muted d-flex align-items-center justify-content-between"
                    >
                      <span className="text-truncate">
                        <i className="bi bi-chevron-right me-1 text-primary small"></i> {sec.title}
                      </span>
                    </a>
                  ))}
                </div>
              </div>
            )}
          </div>
        </aside>
      </div>
    </div>
  );
};

export default StudyGuideDetailPage;
