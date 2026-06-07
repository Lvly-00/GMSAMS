import { Navigate } from 'react-router-dom';
import { useAuthStore, getDashboardPath } from '@/store/authStore';

export default function ProtectedRoute({ children, allowedRoles = [] }) {
  const { token, user } = useAuthStore();

  if (!token) {
    return <Navigate to="/login" replace />;
  }

  if (allowedRoles.length > 0 && !allowedRoles.includes(user?.role?.name)) {
    return <Navigate to={getDashboardPath(user?.role?.name)} replace />;
  }

  return children;
}
