import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { toast } from 'sonner';
import { Plus, Trash2 } from 'lucide-react';
import {
  fetchUsers,
  fetchReference,
  createStudent,
  createTeacher,
  createHeadTeacher,
  deleteUser,
} from '@/api/admin';
import { Button, Input, Label, Card, Badge, Select, Skeleton } from '@/components/ui';
import { ROLE_LABELS, ACCOUNT_TYPE_OPTIONS } from '@/constants/roles';

const passwordSchema = z
  .string()
  .min(6)
  .max(18)
  .regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/, 'Must include upper, lower, number; no spaces');

const studentSchema = z.object({
  account_type: z.literal('student'),
  first_name: z.string().min(1),
  middle_name: z.string().optional(),
  last_name: z.string().min(1),
  gender: z.enum(['Male', 'Female']),
  birthdate: z.string().min(1),
  lrn: z.string().length(12),
  username: z.string().min(3),
  password: passwordSchema,
  school_year_id: z.coerce.number(),
  semester_id: z.coerce.number(),
  grade_level_id: z.coerce.number(),
  strand_id: z.coerce.number(),
  section_id: z.coerce.number(),
  adviser_id: z.coerce.number().optional(),
});

const staffSchema = z.object({
  account_type: z.enum(['teacher', 'head_teacher']),
  first_name: z.string().min(1),
  last_name: z.string().min(1),
  employee_id_no: z.string().min(1),
  username: z.string().min(3),
  password: passwordSchema,
  department: z.string().optional(),
});

const formSchema = z.discriminatedUnion('account_type', [studentSchema, staffSchema]);

export default function AdminAccountsPage() {
  const queryClient = useQueryClient();
  const [roleFilter, setRoleFilter] = useState('');
  const [search, setSearch] = useState('');
  const [showForm, setShowForm] = useState(false);

  const { data: usersData, isLoading } = useQuery({
    queryKey: ['admin', 'users', roleFilter, search],
    queryFn: () => fetchUsers({ role: roleFilter || undefined, search: search || undefined }),
  });

  const { data: reference } = useQuery({
    queryKey: ['admin', 'reference'],
    queryFn: fetchReference,
    enabled: showForm,
  });

  const form = useForm({
    resolver: zodResolver(formSchema),
    defaultValues: { account_type: 'student', gender: 'Male' },
  });

  const accountType = form.watch('account_type');
  const schoolYearId = form.watch('school_year_id');
  const gradeLevelId = form.watch('grade_level_id');
  const strandId = form.watch('strand_id');

  const { data: sectionsRef } = useQuery({
    queryKey: ['admin', 'reference', 'sections', schoolYearId, gradeLevelId, strandId],
    queryFn: () =>
      fetchReference({
        school_year_id: schoolYearId,
        grade_level_id: gradeLevelId,
        strand_id: strandId,
      }),
    enabled: showForm && accountType === 'student' && !!schoolYearId && !!gradeLevelId && !!strandId,
    select: (d) => d.sections,
  });

  const createMutation = useMutation({
    mutationFn: async (values) => {
      const { account_type, ...payload } = values;
      if (account_type === 'student') return createStudent(payload);
      if (account_type === 'head_teacher') return createHeadTeacher(payload);
      return createTeacher(payload);
    },
    onSuccess: (res) => {
      toast.success(res.message);
      queryClient.invalidateQueries({ queryKey: ['admin', 'users'] });
      setShowForm(false);
      form.reset({ account_type: 'student', gender: 'Male' });
    },
    onError: (err) => {
      const errors = err.response?.data?.errors;
      const msg = errors ? Object.values(errors).flat()[0] : err.response?.data?.message;
      toast.error(msg || 'Failed to create account.');
    },
  });

  const deleteMutation = useMutation({
    mutationFn: deleteUser,
    onSuccess: (res) => {
      toast.success(res.message);
      queryClient.invalidateQueries({ queryKey: ['admin', 'users'] });
    },
    onError: () => toast.error('Failed to delete account.'),
  });

  const users = usersData?.data || [];

  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold">Account Management</h2>
          <p className="text-sm text-muted-foreground">Create and manage student, teacher, and head teacher accounts</p>
        </div>
        <Button onClick={() => setShowForm(!showForm)} className="gap-2">
          <Plus size={16} />
          Create Account
        </Button>
      </div>

      {showForm && (
        <Card>
          <h3 className="mb-4 font-semibold">New Account</h3>
          <form onSubmit={form.handleSubmit((v) => createMutation.mutate(v))} className="grid gap-4 md:grid-cols-2">
            <div>
              <Label>Account Type</Label>
              <Select {...form.register('account_type')}>
                {ACCOUNT_TYPE_OPTIONS.map((o) => (
                  <option key={o.value} value={o.value}>
                    {o.label}
                  </option>
                ))}
              </Select>
            </div>
            <div>
              <Label>Username</Label>
              <Input {...form.register('username')} />
            </div>
            <div>
              <Label>First Name</Label>
              <Input {...form.register('first_name')} />
            </div>
            <div>
              <Label>Last Name</Label>
              <Input {...form.register('last_name')} />
            </div>
            {accountType === 'student' && (
              <>
                <div>
                  <Label>LRN (12 digits)</Label>
                  <Input {...form.register('lrn')} maxLength={12} />
                </div>
                <div>
                  <Label>Gender</Label>
                  <Select {...form.register('gender')}>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </Select>
                </div>
                <div>
                  <Label>Birthdate</Label>
                  <Input type="date" {...form.register('birthdate')} />
                </div>
                <div>
                  <Label>School Year</Label>
                  <Select {...form.register('school_year_id')}>
                    <option value="">Select…</option>
                    {reference?.school_years?.map((sy) => (
                      <option key={sy.id} value={sy.id}>
                        {sy.label}
                      </option>
                    ))}
                  </Select>
                </div>
                <div>
                  <Label>Semester</Label>
                  <Select {...form.register('semester_id')}>
                    <option value="">Select…</option>
                    {reference?.semesters?.map((s) => (
                      <option key={s.id} value={s.id}>
                        {s.name}
                      </option>
                    ))}
                  </Select>
                </div>
                <div>
                  <Label>Grade Level</Label>
                  <Select {...form.register('grade_level_id')}>
                    <option value="">Select…</option>
                    {reference?.grade_levels?.map((g) => (
                      <option key={g.id} value={g.id}>
                        {g.name}
                      </option>
                    ))}
                  </Select>
                </div>
                <div>
                  <Label>Strand</Label>
                  <Select {...form.register('strand_id')}>
                    <option value="">Select…</option>
                    {reference?.strands?.map((s) => (
                      <option key={s.id} value={s.id}>
                        {s.code} — {s.name}
                      </option>
                    ))}
                  </Select>
                </div>
                <div>
                  <Label>Section</Label>
                  <Select {...form.register('section_id')}>
                    <option value="">Select…</option>
                    {(sectionsRef || []).map((s) => (
                      <option key={s.id} value={s.id}>
                        {s.name}
                      </option>
                    ))}
                  </Select>
                </div>
                <div>
                  <Label>Adviser (optional)</Label>
                  <Select {...form.register('adviser_id')}>
                    <option value="">None</option>
                    {reference?.teachers?.map((t) => (
                      <option key={t.id} value={t.id}>
                        {t.first_name} {t.last_name}
                      </option>
                    ))}
                  </Select>
                </div>
              </>
            )}
            {accountType !== 'student' && (
              <div>
                <Label>Employee ID</Label>
                <Input {...form.register('employee_id_no')} />
              </div>
            )}
            <div>
              <Label>Password</Label>
              <Input type="password" {...form.register('password')} />
            </div>
            <div className="md:col-span-2 flex gap-2">
              <Button type="submit" disabled={createMutation.isPending}>
                {createMutation.isPending ? 'Creating…' : 'Create Account'}
              </Button>
              <Button type="button" variant="outline" onClick={() => setShowForm(false)}>
                Cancel
              </Button>
            </div>
          </form>
        </Card>
      )}

      <Card>
        <div className="mb-4 flex flex-wrap gap-3">
          <Input
            placeholder="Search username, name, ID…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="max-w-xs"
          />
          <Select value={roleFilter} onChange={(e) => setRoleFilter(e.target.value)} className="max-w-xs">
            <option value="">All roles</option>
            <option value="student">Students</option>
            <option value="teacher">Teachers</option>
            <option value="head_teacher">Head Teachers</option>
          </Select>
        </div>

        {isLoading ? (
          <div className="space-y-2">
            {[...Array(5)].map((_, i) => (
              <Skeleton key={i} className="h-12" />
            ))}
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b text-left text-muted-foreground">
                  <th className="pb-2">Name</th>
                  <th className="pb-2">Username</th>
                  <th className="pb-2">Role</th>
                  <th className="pb-2">Status</th>
                  <th className="pb-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                {users.map((u) => {
                  const name =
                    u.student?.full_name || u.teacher?.full_name || u.username;
                  return (
                    <tr key={u.id} className="border-b last:border-0">
                      <td className="py-3">{name}</td>
                      <td>{u.username}</td>
                      <td>{ROLE_LABELS[u.role?.name] || u.role?.name}</td>
                      <td>
                        <Badge variant={u.is_active ? 'success' : 'danger'}>
                          {u.is_active ? 'Active' : 'Inactive'}
                        </Badge>
                      </td>
                      <td>
                        <Button
                          variant="ghost"
                          className="text-red-600"
                          onClick={() => {
                            if (window.confirm(`Delete account "${u.username}"?`)) {
                              deleteMutation.mutate(u.id);
                            }
                          }}
                        >
                          <Trash2 size={16} />
                        </Button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </Card>
    </div>
  );
}
