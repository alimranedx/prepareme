import React from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <footer className="mt-auto py-5" style={{ background: '#0f172a', color: '#94a3b8', borderTop: '1px solid rgba(255, 255, 255, 0.08)' }}>
      <div className="container py-2">
        <div className="row gy-4">
          <div className="col-md-4">
            <h5 className="fw-bold text-white d-flex align-items-center gap-2 mb-3">
              <div className="navbar-brand-icon" style={{ width: 32, height: 32 }}>
                <i className="bi bi-book-half fs-6"></i>
              </div>
              <span>PrepareMe<span className="text-gradient-emerald">.com</span></span>
            </h5>
            <p className="small bangla-text mb-3" style={{ color: '#94a3b8', lineHeight: 1.7 }}>
              বাংলাদেশের বিসিএস, সরকারি ও বেসরকারি চাকরি পরীক্ষার সর্বোচ্চ প্রস্তুতির জন্য আধুনিক, বিষয়ভিত্তিক এবং ব্যক্তিগত ডিজিটাল প্ল্যাটফর্ম।
            </p>
            <div className="small" style={{ color: '#64748b' }}>
              &copy; {new Date().getFullYear()} PrepareMe.com — সর্বস্বত্ব সংরক্ষিত।
            </div>
          </div>

          <div className="col-md-4">
            <h6 className="fw-bold text-white bangla-text mb-3">গুরুত্বপূর্ণ বিষয়সমূহ</h6>
            <ul className="list-unstyled small mb-0 bangla-text">
              <li className="mb-2"><Link to="/subjects/bangla" className="text-decoration-none" style={{ color: '#94a3b8' }}>বাংলা ভাষা ও সাহিত্য</Link></li>
              <li className="mb-2"><Link to="/subjects/english" className="text-decoration-none" style={{ color: '#94a3b8' }}>English Language & Literature</Link></li>
              <li className="mb-2"><Link to="/subjects/mathematics" className="text-decoration-none" style={{ color: '#94a3b8' }}>গাণিতিক যুক্তি ও মানসিক দক্ষতা</Link></li>
              <li className="mb-2"><Link to="/subjects/general-knowledge" className="text-decoration-none" style={{ color: '#94a3b8' }}>সাধারণ জ্ঞান (বাংলাদেশ ও আন্তর্জাতিক)</Link></li>
              <li className="mb-2"><Link to="/subjects/ict" className="text-decoration-none" style={{ color: '#94a3b8' }}>তথ্য ও যোগাযোগ প্রযুক্তি (ICT)</Link></li>
            </ul>
          </div>

          <div className="col-md-4">
            <h6 className="fw-bold text-white bangla-text mb-3">বিশেষ সুবিধাসমূহ</h6>
            <ul className="list-unstyled small mb-0 bangla-text">
              <li className="mb-2"><span style={{ color: '#cbd5e1' }}><i className="bi bi-check2-circle text-emerald-400 me-2" style={{ color: '#34d399' }}></i> ব্যক্তিগত নিজস্ব প্রশ্ন-উত্তর নোটবুক</span></li>
              <li className="mb-2"><span style={{ color: '#cbd5e1' }}><i className="bi bi-check2-circle text-emerald-400 me-2" style={{ color: '#34d399' }}></i> বইয়ের ছবি থেকে বাংলা-ইংরেজি OCR টেক্সট কনভার্টার</span></li>
              <li className="mb-2"><span style={{ color: '#cbd5e1' }}><i className="bi bi-check2-circle text-emerald-400 me-2" style={{ color: '#34d399' }}></i> পড়াশোনার অগ্রগতি ও বুকমার্ক ট্র্যাকিং</span></li>
              <li className="mb-2"><span style={{ color: '#cbd5e1' }}><i className="bi bi-check2-circle text-emerald-400 me-2" style={{ color: '#34d399' }}></i> অধ্যায়ভিত্তিক সমৃদ্ধ পাবলিক প্রশ্নব্যাংক</span></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
