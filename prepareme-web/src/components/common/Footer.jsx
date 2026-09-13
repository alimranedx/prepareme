import React from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <footer className="bg-white border-top mt-auto py-4">
      <div className="container">
        <div className="row gy-4">
          <div className="col-md-4">
            <h5 className="fw-bold text-primary d-flex align-items-center gap-2">
              <i className="bi bi-book-half"></i> PrepareMe.com
            </h5>
            <p className="text-muted small bangla-text mb-2">
              বাংলাদেশের বিসিএস, সরকারি ও বেসরকারি চাকরি পরীক্ষার সর্বোচ্চ প্রস্তুতির জন্য আধুনিক, বিষয়ভিত্তিক এবং ব্যক্তিগত ডিজিটাল প্ল্যাটফর্ম।
            </p>
            <div className="text-secondary small">
              &copy; {new Date().getFullYear()} PrepareMe.com — সর্বস্বত্ব সংরক্ষিত।
            </div>
          </div>

          <div className="col-md-4">
            <h6 className="fw-bold text-dark bangla-text mb-3">গুরুত্বপূর্ণ বিষয়সমূহ</h6>
            <ul className="list-unstyled small mb-0 bangla-text">
              <li className="mb-2"><Link to="/subjects/bangla" className="text-decoration-none text-muted">বাংলা ভাষা ও সাহিত্য</Link></li>
              <li className="mb-2"><Link to="/subjects/english" className="text-decoration-none text-muted">English Language & Literature</Link></li>
              <li className="mb-2"><Link to="/subjects/mathematics" className="text-decoration-none text-muted">গাণিতিক যুক্তি ও মানসিক দক্ষতা</Link></li>
              <li className="mb-2"><Link to="/subjects/general-knowledge" className="text-decoration-none text-muted">সাধারণ জ্ঞান (বাংলাদেশ ও আন্তর্জাতিক)</Link></li>
              <li className="mb-2"><Link to="/subjects/ict" className="text-decoration-none text-muted">তথ্য ও যোগাযোগ প্রযুক্তি (ICT)</Link></li>
            </ul>
          </div>

          <div className="col-md-4">
            <h6 className="fw-bold text-dark bangla-text mb-3">বিশেষ সুবিধাসমূহ</h6>
            <ul className="list-unstyled small mb-0 bangla-text">
              <li className="mb-2"><span className="text-muted"><i className="bi bi-check2-circle text-success me-1"></i> ব্যক্তিগত নিজস্ব প্রশ্ন-উত্তর নোটবুক</span></li>
              <li className="mb-2"><span className="text-muted"><i className="bi bi-check2-circle text-success me-1"></i> বইয়ের ছবি থেকে বাংলা-ইংরেজি OCR টেক্সট কনভার্টার</span></li>
              <li className="mb-2"><span className="text-muted"><i className="bi bi-check2-circle text-success me-1"></i> পড়াশোনার অগ্রগতি ও বুকমার্ক ট্র্যাকিং</span></li>
              <li className="mb-2"><span className="text-muted"><i className="bi bi-check2-circle text-success me-1"></i> অধ্যায়ভিত্তিক সমৃদ্ধ পাবলিক প্রশ্নব্যাংক</span></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
