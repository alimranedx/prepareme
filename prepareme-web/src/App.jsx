import React from 'react';
import { Routes, Route, Navigate, Link } from 'react-router-dom';

// Layouts
import AppLayout from './layouts/AppLayout';
import AdminLayout from './layouts/AdminLayout';

// Route Guards
import { AdminRoute, UserRoute, GuestRoute } from './routes/RouteGuards';

// Public Pages
import HomePage from './pages/public/HomePage';
import SubjectsPage from './pages/public/SubjectsPage';
import TopicListingPage from './pages/public/TopicListingPage';
import StudyGuideDetailPage from './pages/public/StudyGuideDetailPage';
import PublicQuestionsPage from './pages/public/PublicQuestionsPage';
import ExamsHubPage from './pages/public/ExamsHubPage';
import ExamSyllabusDetailPage from './pages/public/ExamSyllabusDetailPage';
import PreviousQuestionsPage from './pages/public/PreviousQuestionsPage';
import ModelTestsListingPage from './pages/public/ModelTestsListingPage';
import ModelTestSimulatorPage from './pages/public/ModelTestSimulatorPage';
import ModelTestResultPage from './pages/public/ModelTestResultPage';
import MistakeRevisionPage from './pages/public/MistakeRevisionPage';

// Auth Pages
import LoginPage from './pages/auth/LoginPage';
import RegisterPage from './pages/auth/RegisterPage';
import ForgotPasswordPage from './pages/auth/ForgotPasswordPage';
import ResetPasswordPage from './pages/auth/ResetPasswordPage';
import ProfilePage from './pages/auth/ProfilePage';

// User Notebook & Personal Pages
import UserDashboardPage from './pages/user/UserDashboardPage';
import PersonalQuestionsPage from './pages/user/PersonalQuestionsPage';
import PersonalQuestionFormPage from './pages/user/PersonalQuestionFormPage';
import BookmarksPage from './pages/user/BookmarksPage';
import ReadingProgressPage from './pages/user/ReadingProgressPage';
import OcrUploadPage from './pages/user/OcrUploadPage';
import OcrViewerPage from './pages/user/OcrViewerPage';

// Admin Pages
import AdminDashboardPage from './pages/admin/AdminDashboardPage';
import UserManagementPage from './pages/admin/UserManagementPage';
import SubjectManagementPage from './pages/admin/SubjectManagementPage';
import TopicManagementPage from './pages/admin/TopicManagementPage';
import StudyGuideManagementPage from './pages/admin/StudyGuideManagementPage';
import StudyGuideEditorPage from './pages/admin/StudyGuideEditorPage';
import PublicQuestionManagementPage from './pages/admin/PublicQuestionManagementPage';
import OcrMonitoringPage from './pages/admin/OcrMonitoringPage';

// 404 Fallback Component
const NotFound = () => (
  <div className="container py-5 text-center my-5">
    <div className="display-1 fw-bold text-primary mb-3">404</div>
    <h2 className="fw-bold bangla-text text-dark mb-2">পৃষ্ঠাটি খুঁজে পাওয়া যায়নি</h2>
    <p className="text-muted bangla-text mb-4">
      আপনি যে পৃষ্ঠাটি খুঁজছেন সেটি স্থানান্তরিত বা মুছে ফেলা হতে পারে।
    </p>
    <Link to="/" className="btn btn-primary rounded-pill px-4 bangla-text">
      <i className="bi bi-house-door me-2"></i>হোমপেজে ফিরে যান
    </Link>
  </div>
);

function App() {
  return (
    <Routes>
      {/* Public & User Area (Wrapped in AppLayout with public Navbar & Footer) */}
      <Route element={<AppLayout />}>
        {/* Unrestricted Public Routes */}
        <Route path="/" element={<HomePage />} />
        <Route path="/subjects" element={<SubjectsPage />} />
        <Route path="/subjects/:slug" element={<TopicListingPage />} />
        <Route path="/subjects/:slug/topics" element={<TopicListingPage />} />
        <Route path="/study-guides/:id" element={<StudyGuideDetailPage />} />
        <Route path="/study-guides/view/:slug" element={<StudyGuideDetailPage />} />
        <Route path="/public-questions" element={<PublicQuestionsPage />} />

        {/* Exams & Syllabus Hub */}
        <Route path="/exams" element={<ExamsHubPage />} />
        <Route path="/exams/:slug" element={<ExamSyllabusDetailPage />} />

        {/* Question Bank & Previous Years Archives */}
        <Route path="/previous-questions" element={<PreviousQuestionsPage />} />

        {/* Model Test & Exam Simulator */}
        <Route path="/model-tests" element={<ModelTestsListingPage />} />
        <Route path="/model-tests/:slug/take" element={<ModelTestSimulatorPage />} />
        <Route path="/model-tests/attempts/:id/result" element={<ModelTestResultPage />} />
        <Route path="/model-tests/attempts/:id/mistakes" element={<MistakeRevisionPage />} />

        {/* Guest Only Routes (Login, Register, Password Reset) */}
        <Route element={<GuestRoute />}>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/forgot-password" element={<ForgotPasswordPage />} />
          <Route path="/reset-password" element={<ResetPasswordPage />} />
        </Route>

        {/* Authenticated User Routes (Personal Notebook, OCR, Progress, Bookmarks, Profile) */}
        <Route element={<UserRoute />}>
          <Route path="/profile" element={<ProfilePage />} />
          <Route path="/dashboard" element={<UserDashboardPage />} />
          <Route path="/my/questions" element={<PersonalQuestionsPage />} />
          <Route path="/my/questions/create" element={<PersonalQuestionFormPage />} />
          <Route path="/my/questions/:id/edit" element={<PersonalQuestionFormPage />} />
          <Route path="/my/bookmarks" element={<BookmarksPage />} />
          <Route path="/my/progress" element={<ReadingProgressPage />} />
          <Route path="/my/ocr" element={<OcrUploadPage />} />
          <Route path="/my/ocr/:id" element={<OcrViewerPage />} />
        </Route>

        {/* Catch-all 404 inside public layout */}
        <Route path="*" element={<NotFound />} />
      </Route>

      {/* Admin Panel Routes (Protected by AdminRoute and wrapped in AdminLayout) */}
      <Route element={<AdminRoute />}>
        <Route element={<AdminLayout />}>
          <Route path="/admin" element={<Navigate to="/admin/dashboard" replace />} />
          <Route path="/admin/dashboard" element={<AdminDashboardPage />} />
          <Route path="/admin/users" element={<UserManagementPage />} />
          <Route path="/admin/subjects" element={<SubjectManagementPage />} />
          <Route path="/admin/topics" element={<TopicManagementPage />} />
          <Route path="/admin/study-guides" element={<StudyGuideManagementPage />} />
          <Route path="/admin/study-guides/create" element={<StudyGuideEditorPage />} />
          <Route path="/admin/study-guides/:id/edit" element={<StudyGuideEditorPage />} />
          <Route path="/admin/public-questions" element={<PublicQuestionManagementPage />} />
          <Route path="/admin/ocr-documents" element={<OcrMonitoringPage />} />
        </Route>
      </Route>
    </Routes>
  );
}

export default App;
