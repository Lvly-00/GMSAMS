import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Toaster } from 'sonner';

import ProtectedRoute from '@/components/shared/ProtectedRoute';

// Layouts
import AdminLayout from '@/components/shared/AdminLayout';
import HeadTeacherLayout from '@/components/shared/HeadTeacherLayout';
import TeacherLayout from '@/components/shared/TeacherLayout';
import StudentLayout from '@/components/shared/StudentLayout';

import LoginPage from '@/pages/auth/LoginPage';

// ADMIN
import AdminDashboardPage from '@/pages/admin/DashboardPage';
import AdminAccountsPage from '@/pages/admin/AccountsPage';
import AdminSubjectsPage from '@/pages/admin/SubjectsPage';
import ActivityLogsPage from '@/pages/admin/ActivityLogsPage';

// STUDENT
import StudentDashboardPage from '@/pages/student/DashboardPage';
import AcademicFormPage from '@/pages/student/AcademicForm';
import ProfilePage from '@/pages/student/ProfilePage';
import SubjectPage from '@/pages/student/SubjectPage';

// TEACHER
import TeacherDashboardPage from '@/pages/teacher/DashboardPage';
import GradebookPage from '@/pages/teacher/GradebookPage';
import ClassRecordPage from '@/pages/teacher/ClassRecordPage';
import AcademicConcernsPage from '@/pages/teacher/AcademicConcernsPage';
import TeacherArchivesPage from '@/pages/teacher/ArchivesPage';

// HEAD TEACHER
import HeadTeacherDashboardPage from '@/pages/head-teacher/DashboardPage';
import ClassMonitoringPage from '@/pages/head-teacher/ClassMonitoringPage';
import ClassRecordsPage from '@/pages/head-teacher/ClassRecordsPage';
import DocumentGenerationPage from '@/pages/head-teacher/DocumentGenerationPage';
import HeadTeacherArchivesPage from '@/pages/head-teacher/ArchivesPage';


import { ROLES } from '@/constants/roles';
import { useAuthStore, getDashboardPath } from '@/store/authStore';

const queryClient = new QueryClient({
  defaultOptions: { queries: { retry: 1, staleTime: 30_000 } },
});

function HomeRedirect() {
  const { token, user } = useAuthStore();

  if (!token) return <Navigate to="/login" replace />;

  return <Navigate to={getDashboardPath(user?.role?.name)} replace />;
}

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <Routes>

          {/* ROOT */}
          <Route path="/" element={<HomeRedirect />} />

          {/* LOGIN */}
          <Route path="/login" element={<LoginPage />} />

          {/* ================= ADMIN ================= */}
          <Route
            path="/admin"
            element={
              <ProtectedRoute allowedRoles={[ROLES.ADMIN]}>
                <AdminLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="dashboard" element={<AdminDashboardPage />} />
            <Route path="accounts" element={<AdminAccountsPage />} />
            <Route path="subjects" element={<AdminSubjectsPage />} />
            <Route path="activity-logs" element={<ActivityLogsPage />} />
          </Route>

          {/* ================= STUDENT ================= */}
          <Route
            path="/student"
            element={
              <ProtectedRoute allowedRoles={[ROLES.STUDENT]}>
                <StudentLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="dashboard" element={<StudentDashboardPage />} />
            <Route path="academic-form-request" element={<AcademicFormPage />} />
            <Route path="profile" element={<ProfilePage />} />
            <Route
              path="/student/subject-overview/:subjectId"
              element={<SubjectPage />}
            />          </Route>

          {/* ================= TEACHER ================= */}
          <Route
            path="/teacher"
            element={
              <ProtectedRoute allowedRoles={[ROLES.TEACHER]}>
                <TeacherLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="dashboard" element={<TeacherDashboardPage />} />
            <Route path="gradebook" element={<GradebookPage />} />
            <Route path="class-record" element={<ClassRecordPage />} />
            <Route path="academic-concerns" element={<AcademicConcernsPage />} />
            <Route path="archives" element={<TeacherArchivesPage />} />

          </Route>

          {/* ================= HEAD TEACHER ================= */}
          <Route
            path="/head-teacher"
            element={
              <ProtectedRoute allowedRoles={[ROLES.HEAD_TEACHER]}>
                <HeadTeacherLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="dashboard" element={<HeadTeacherDashboardPage />} />
            <Route path="class-monitoring" element={<ClassMonitoringPage />} />
            <Route path="class-records" element={<ClassRecordsPage />} />
            <Route path="generated-documents" element={<DocumentGenerationPage />} />
            <Route path="archives" element={<HeadTeacherArchivesPage />} />
            <Route path="activity-logs" element={<ActivityLogsPage />} />
          </Route>

          {/* FALLBACK */}
          <Route path="*" element={<Navigate to="/" replace />} />

        </Routes>
      </BrowserRouter>

      <Toaster richColors position="top-right" />
    </QueryClientProvider>
  );
}