import api from './axios';

export const login = async (payload) => {
  // try {
  //   await api.get('/sanctum/csrf-cookie', { baseURL: '' });
  // } catch {
  //   // Bearer token auth works without CSRF cookie
  // }
  const { data } = await api.post('/auth/login', payload);
  return data;
};

export const logout = async () => {
  const { data } = await api.post('/auth/logout');
  return data;
};

export const fetchMe = async () => {
  const { data } = await api.get('/auth/me');
  return data;
};
