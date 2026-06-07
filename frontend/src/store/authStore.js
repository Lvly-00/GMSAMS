import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useAuthStore = create(
  persist(
    (set) => ({
      token: null,
      user: null,
      setAuth: (token, user) => set({ token, user }),
      logout: () => set({ token: null, user: null }),
    }),
    { name: 'gmsams-auth' },
  ),
);

export const getDashboardPath = (roleName) => {
  const map = {
    admin: '/admin/dashboard',
    head_teacher: '/head-teacher/dashboard',
    teacher: '/teacher/dashboard',
    student: '/student/dashboard',
  };
  return map[roleName] || '/login';
};
