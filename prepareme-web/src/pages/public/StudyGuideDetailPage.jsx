import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import apiClient from '../../api/client';
import { useAuth } from '../../context/AuthContext';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import AlertMessage from '../../components/common/AlertMessage';

const StudyGuideDetailPage = () => {
  const { slug } = useParams();
  const { isAuthenticated } = useAuth();

  const [guide, setGuide] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isBookmarked, setIsBookmarked] = useState(false);
  const [progressPercent, setProgressPercent] = useState(0);
  const [progressStatus, setProgressStatus] = useState('not_started');
  const [updatingAction, setUpdatingAction] = useState(false);
  const [toastMessage, setToastMessage] = useState(null);

  useEffect(() => {
    const fetchGuide = async () => {
      setLoading(true);
      setError(null);
      try {
        const response = await apiClient.get(`/study-guides/${slug}`);
        const data = response.data.data;
        setGuide(data);
        setIsBookmarked(data.is_bookmarked || false);
        if (data.user_progress) {
          setProgressPercent(data.user_progress.progress_percent || 0);
          setProgressStatus(data.user_progress.status || 'not_started');
        }
      } catch (err) {
        setError(err.response?.data?.message || 'স্টাডি গাইডটি খুঁজে পাওয়া যায়নি।');
      } finally {
        setLoading(false);
      }
    };

    fetchGuide();
  }, [slug]);

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
      setToastMessage(newPercent >= 100 ? 'অভিনন্দন! অধ্যায়টি সম্পূর্ণ হয়েছে।' : `পড়ার অগ্রগতি ${newPercent}% এ আপডেট করা হয়েছে।`);
    } catch (err) {
      setToastMessage('অগ্রগতি আপডেট করা যায়নি।');
    } finally {
      setUpdatingAction(false);
    }
  };

  const getSectionBadge = (type) => {
    switch (type) {
      case 'explanation':
        return <span className="badge bg-primary-subtle text-primary">ব্যাখ্যা ও আলোচনা</span>;
      case 'example':
        return <span className="badge bg-info-subtle text-info">বাস্তব উদাহরণ</span>;
      case 'formula':
        return <span className="badge bg-warning-subtle text-warning-emphasis">শর্টকাট সূত্র</span>;
      case 'summary':
        return <span className="badge bg-secondary-subtle text-secondary">সারসংক্ষেপ</span>;
      case 'practice':
        return <span className="badge bg-success-subtle text-success">অনুশীলন ও বিগত সালের প্রশ্ন</span>;
      default:
        return <span className="badge bg-light text-dark border">আলোচনা</span>;
    }
  };

  if (loading) return <LoadingSpinner fullPage text="স্টাডি গাইড লোড হচ্ছে..." />;
  if (error || !guide) {
    return (
      <div className="container py-5 text-center">
        <div className="alert alert-danger bangla-text mx-auto" style={{ maxWidth: '500px' }}>
          {error || 'স্টাডি গাইডটি লোড করা সম্ভব হয়নি।'}
        </div>
        <Link to="/subjects" className="btn btn-primary bangla-text rounded-pill">
          সকল বিষয়সমূহে ফিরে যান
        </Link>
      </div>
    );
  }

  return (
    <div className="container py-5">
      {/* Breadcrumb */}
      <nav aria-label="breadcrumb" className="mb-4">
        <ol className="breadcrumb bangla-text">
          <li className="breadcrumb-item"><Link to="/" className="text-decoration-none">হোম</Link></li>
          <li className="breadcrumb-item">
            <Link to="/subjects" className="text-decoration-none">বিষয়সমূহ</Link>
          </li>
          {guide.topic?.subject && (
            <li className="breadcrumb-item">
              <Link to={`/subjects/${guide.topic.subject.slug}`} className="text-decoration-none">
                {guide.topic.subject.name}
              </Link>
            </li>
          )}
          <li className="breadcrumb-item active" aria-current="page">{guide.title}</li>
        </ol>
      </nav>

      {/* Floating feedback alert */}
      {toastMessage && (
        <div className="position-fixed bottom-0 end-0 p-3" style={{ zIndex: 1080 }}>
          <div className="alert alert-dark alert-dismissible fade show shadow-lg bangla-text" role="alert">
            <i className="bi bi-info-circle-fill me-2 text-info"></i>
            {toastMessage}
            <button type="button" className="btn-close btn-close-white" onClick={() => setToastMessage(null)}></button>
          </div>
        </div>
      )}

      <div className="row g-4">
        {/* Main Content Column */}
        <div className="col-lg-8">
          <div className="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            {/* Guide Meta Header */}
            <div className="d-flex justify-content-between align-items-start gap-3 mb-3">
              <div>
                <span className="badge bg-primary-subtle text-primary bangla-text px-3 py-2 rounded-pill mb-2">
                  {guide.topic?.name}
                </span>
                <h1 className="fw-bold text-dark bangla-text display-6 mb-2">{guide.title}</h1>
                <p className="text-muted small bangla-text">
                  <i className="bi bi-calendar3 me-1"></i> প্রকাশিত:{' '}
                  {guide.published_at ? new Date(guide.published_at).toLocaleDateString('bn-BD') : 'সম্প্রতি'}
                </p>
              </div>

              {/* Bookmark Button */}
              <button
                className={`btn btn-lg rounded-circle shadow-sm ${isBookmarked ? 'btn-warning text-white' : 'btn-outline-secondary'}`}
                onClick={handleToggleBookmark}
                disabled={updatingAction}
                title={isBookmarked ? 'বুকমার্ক থেকে মুছুন' : 'বুকমার্ক করুন'}
              >
                <i className={`bi ${isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark'}`}></i>
              </button>
            </div>

            {/* Summary Box */}
            {guide.summary && (
              <div className="p-3 bg-light border-start border-4 border-primary rounded-3 my-4">
                <h6 className="fw-bold text-primary bangla-text mb-1">
                  <i className="bi bi-lightbulb-fill me-1 text-warning"></i> অধ্যায় পরিচিতি ও উদ্দেশ্য:
                </h6>
                <p className="mb-0 text-muted bangla-text small">{guide.summary}</p>
              </div>
            )}

            {/* Main Guide Content */}
            <div className="study-guide-content bangla-text fs-5 mb-5" style={{ lineHeight: '1.9', color: '#1e293b' }}>
              {guide.content.split('\n').map((paragraph, idx) => (
                paragraph.trim() ? <p key={idx} className="mb-3">{paragraph}</p> : <br key={idx} />
              ))}
            </div>

            {/* Structured Sections */}
            {guide.sections && guide.sections.length > 0 && (
              <div className="mt-5 pt-4 border-top">
                <h4 className="fw-bold text-dark bangla-text mb-4">
                  <i className="bi bi-layers-fill text-primary me-2"></i> বিস্তারিত অংশ ও বিশ্লেষণ
                </h4>

                <div className="d-flex flex-column gap-4">
                  {guide.sections.map((section, idx) => (
                    <div key={section.id} id={`section-${section.id}`} className="card border rounded-4 shadow-sm p-4">
                      <div className="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 className="fw-bold text-dark bangla-text mb-0">
                          {section.title}
                        </h5>
                        {getSectionBadge(section.section_type)}
                      </div>

                      <div className="bangla-text text-secondary" style={{ lineHeight: '1.8', whiteSpace: 'pre-line' }}>
                        {section.content}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>

        {/* Sidebar: Progress & Navigation */}
        <div className="col-lg-4">
          <div className="sticky-top" style={{ top: '80px' }}>
            {/* Reading Progress Card */}
            <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
              <h5 className="fw-bold bangla-text text-dark mb-3">
                <i className="bi bi-graph-up text-success me-2"></i> পড়ার অগ্রগতি
              </h5>

              <div className="progress mb-3" style={{ height: '12px' }}>
                <div
                  className={`progress-bar progress-bar-striped progress-bar-animated ${progressPercent >= 100 ? 'bg-success' : 'bg-primary'}`}
                  role="progressbar"
                  style={{ width: `${progressPercent}%` }}
                  aria-valuenow={progressPercent}
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>

              <div className="d-flex justify-content-between align-items-center mb-3">
                <span className="small text-muted bangla-text">
                  স্ট্যাটাস:{' '}
                  <strong>
                    {progressStatus === 'completed' ? 'সম্পূর্ণ হয়েছে' : progressStatus === 'in_progress' ? 'চলমান' : 'শুরু হয়নি'}
                  </strong>
                </span>
                <span className="fw-bold text-primary">{progressPercent}%</span>
              </div>

              {/* Progress Buttons */}
              <div className="d-grid gap-2">
                <div className="btn-group w-100" role="group">
                  <button
                    type="button"
                    className={`btn btn-sm ${progressPercent === 25 ? 'btn-primary' : 'btn-outline-primary'}`}
                    onClick={() => handleUpdateProgress(25)}
                    disabled={updatingAction}
                  >
                    ২৫%
                  </button>
                  <button
                    type="button"
                    className={`btn btn-sm ${progressPercent === 50 ? 'btn-primary' : 'btn-outline-primary'}`}
                    onClick={() => handleUpdateProgress(50)}
                    disabled={updatingAction}
                  >
                    ৫০%
                  </button>
                  <button
                    type="button"
                    className={`btn btn-sm ${progressPercent === 75 ? 'btn-primary' : 'btn-outline-primary'}`}
                    onClick={() => handleUpdateProgress(75)}
                    disabled={updatingAction}
                  >
                    ৭৫%
                  </button>
                </div>

                <button
                  type="button"
                  className={`btn ${progressPercent >= 100 ? 'btn-outline-success' : 'btn-success'} bangla-text fw-semibold py-2 rounded-3`}
                  onClick={() => handleUpdateProgress(100)}
                  disabled={updatingAction}
                >
                  <i className="bi bi-check2-circle me-1"></i> সম্পূর্ণ পড়া হয়েছে (১০০%)
                </button>
              </div>
            </div>

            {/* Quick Section Navigation */}
            {guide.sections && guide.sections.length > 0 && (
              <div className="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 className="fw-bold bangla-text text-secondary mb-3">
                  <i className="bi bi-list-ul me-1"></i> এই গাইডের পরিচ্ছেদসমূহ
                </h6>
                <div className="list-group list-group-flush small bangla-text">
                  {guide.sections.map((sec, i) => (
                    <a
                      key={sec.id}
                      href={`#section-${sec.id}`}
                      className="list-group-item list-group-item-action px-0 py-2 border-0 text-decoration-none text-muted"
                    >
                      <i className="bi bi-chevron-right me-1 text-primary"></i> {sec.title}
                    </a>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default StudyGuideDetailPage;
