import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import {
  LayoutDashboard,
  BookOpen,
  ClipboardCheck,
  AlertCircle,
  Archive,
  LogOut,
} from 'lucide-react';import { toast } from 'sonner';
import { logout } from '@/api/auth';
import { useAuthStore } from '@/store/authStore';
import { Button } from '@/components/ui';
import { cn } from '@/lib/utils';

const navItems = [
  { to: '/teacher/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { to: '/teacher/gradebook', label: 'Gradebook', icon: BookOpen },
  { to: '/teacher/academic-concerns', label: 'Academic Concerns', icon: AlertCircle },
  { to: '/teacher/archives', label: 'Archives', icon: Archive },
];

export default function TeacherLayout() {
  const navigate = useNavigate();
  const { user, logout: clearAuth } = useAuthStore();

  const handleLogout = async () => {
    if (!window.confirm('Are you sure you want to log out?')) return;
    try {
      await logout();
      clearAuth();
      toast.success('Logged out successfully.');
      navigate('/login');
    } catch {
      clearAuth();
      navigate('/login');
    }
  };

  return (
    <div className="flex min-h-screen">
      <aside className="flex w-64 flex-col border-r bg-white">
        <div className="border-b p-6">
          <h1 className="text-lg font-bold text-primary">GMSAMS</h1>
          <p className="text-xs text-muted-foreground">Teacher Portal</p>
        </div>
        <nav className="flex-1 space-y-1 p-4">
          {navItems.map(({ to, label, icon: Icon }) => (
            <NavLink
              key={to}
              to={to}
              className={({ isActive }) =>
                cn(
                  'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition',
                  isActive ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-100',
                )
              }
            >
              <Icon size={18} />
              {label}
            </NavLink>
          ))}
        </nav>
        <div className="border-t p-4">
          <p className="mb-2 truncate text-sm font-medium">{user?.username}</p>
          <Button variant="outline" className="w-full gap-2" onClick={handleLogout}>
            <LogOut size={16} />
            Logout
          </Button>
        </div>
      </aside>
      <main className="flex-1 overflow-auto p-8">
        <Outlet />
      </main>
    </div>
  );
}
