import api from './axios';

export const fetchDashboard = async () => {
  const { data } = await api.get('/admin/dashboard');
  return data;
};

export const fetchReference = async (params = {}) => {
  const { data } = await api.get('/admin/reference', { params });
  return data;
};

export const fetchUsers = async (params = {}) => {
  const { data } = await api.get('/admin/users', { params });
  return data;
};

export const fetchUser = async (id) => {
  const { data } = await api.get(`/admin/users/${id}`);
  return data;
};

export const createStudent = async (payload) => {
  const { data } = await api.post('/admin/users/students', payload);
  return data;
};

export const createTeacher = async (payload) => {
  const { data } = await api.post('/admin/users/teachers', payload);
  return data;
};

export const createHeadTeacher = async (payload) => {
  const { data } = await api.post('/admin/users/head-teachers', payload);
  return data;
};

export const updateStudent = async (id, payload) => {
  const { data } = await api.put(`/admin/users/${id}/student`, payload);
  return data;
};

export const updateTeacher = async (id, payload) => {
  const { data } = await api.put(`/admin/users/${id}/teacher`, payload);
  return data;
};

export const deleteUser = async (id) => {
  const { data } = await api.delete(`/admin/users/${id}`);
  return data;
};

export const fetchSubjects = async (params = {}) => {
  const { data } = await api.get('/admin/subjects', { params });
  return data;
};

export const createSubject = async (payload) => {
  const { data } = await api.post('/admin/subjects', payload);
  return data;
};

export const updateSubject = async (id, payload) => {
  const { data } = await api.put(`/admin/subjects/${id}`, payload);
  return data;
};

export const deleteSubject = async (id) => {
  const { data } = await api.delete(`/admin/subjects/${id}`);
  return data;
};

export const bulkSubjectAction = async (payload) => {
  const { data } = await api.post('/admin/subjects/bulk', payload);
  return data;
};
