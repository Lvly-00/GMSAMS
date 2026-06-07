import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { toast } from 'sonner';
import { Plus, Trash2, EyeOff, Eye } from 'lucide-react';
import {
  fetchSubjects,
  fetchReference,
  createSubject,
  deleteSubject,
  bulkSubjectAction,
} from '@/api/admin';
import { Button, Input, Label, Card, Badge, Select, Skeleton } from '@/components/ui';

const subjectSchema = z.object({
  name: z.string().min(1),
  code: z.string().min(1),
  grade_level_id: z.coerce.number(),
  strand_id: z.coerce.number(),
  teacher_id: z.string().uuid(),
});

export default function AdminSubjectsPage() {
  const queryClient = useQueryClient();
  const [showForm, setShowForm] = useState(false);
  const [selected, setSelected] = useState([]);
  const [includeHidden, setIncludeHidden] = useState(false);
  const [search, setSearch] = useState('');

  const { data: subjectsData, isLoading } = useQuery({
    queryKey: ['admin', 'subjects', includeHidden, search],
    queryFn: () =>
      fetchSubjects({ include_hidden: includeHidden, search: search || undefined }),
  });

  const { data: reference } = useQuery({
    queryKey: ['admin', 'reference'],
    queryFn: fetchReference,
    enabled: showForm,
  });

  const form = useForm({ resolver: zodResolver(subjectSchema) });

  const createMutation = useMutation({
    mutationFn: createSubject,
    onSuccess: (res) => {
      toast.success(res.message);
      queryClient.invalidateQueries({ queryKey: ['admin', 'subjects'] });
      setShowForm(false);
      form.reset();
    },
    onError: (err) => {
      const msg = err.response?.data?.message || Object.values(err.response?.data?.errors || {}).flat()[0];
      toast.error(msg || 'Failed to create subject.');
    },
  });

  const bulkMutation = useMutation({
    mutationFn: bulkSubjectAction,
    onSuccess: (res) => {
      toast.success(res.message);
      queryClient.invalidateQueries({ queryKey: ['admin', 'subjects'] });
      setSelected([]);
    },
    onError: () => toast.error('Bulk action failed.'),
  });

  const deleteMutation = useMutation({
    mutationFn: deleteSubject,
    onSuccess: (res) => {
      toast.success(res.message);
      queryClient.invalidateQueries({ queryKey: ['admin', 'subjects'] });
    },
  });

  const subjects = subjectsData?.data || [];

  const toggleSelect = (id) => {
    setSelected((prev) => (prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]));
  };

  const toggleAll = () => {
    setSelected(selected.length === subjects.length ? [] : subjects.map((s) => s.id));
  };

  const runBulk = (action) => {
    if (selected.length === 0) {
      toast.warning('Select at least one subject.');
      return;
    }
    if (action === 'delete' && !window.confirm(`Delete ${selected.length} subject(s)?`)) return;
    bulkMutation.mutate({ subject_ids: selected, action });
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold">Subject Management</h2>
          <p className="text-sm text-muted-foreground">Create, hide, and assign teachers to subjects</p>
        </div>
        <Button onClick={() => setShowForm(!showForm)} className="gap-2">
          <Plus size={16} />
          Create Subject
        </Button>
      </div>

      {showForm && (
        <Card>
          <h3 className="mb-4 font-semibold">New Subject</h3>
          <form onSubmit={form.handleSubmit((v) => createMutation.mutate(v))} className="grid gap-4 md:grid-cols-2">
            <div>
              <Label>Subject Name</Label>
              <input className="w-full" {...form.register('name')} />
            </div>
            <div>
              <Label>Subject Code</Label>
              <input {...form.register('code')} />
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
                    {s.code}
                  </option>
                ))}
              </Select>
            </div>
            <div className="md:col-span-2">
              <Label>Assigned Teacher</Label>
              <Select {...form.register('teacher_id')}>
                <option value="">Select…</option>
                {reference?.teachers?.map((t) => (
                  <option key={t.id} value={t.id}>
                    {t.first_name} {t.last_name} ({t.employee_id_no})
                  </option>
                ))}
              </Select>
            </div>
            <div className="md:col-span-2 flex gap-2">
              <Button type="submit" disabled={createMutation.isPending}>
                Create Subject
              </Button>
              <Button type="button" variant="outline" onClick={() => setShowForm(false)}>
                Cancel
              </Button>
            </div>
          </form>
        </Card>
      )}

      <Card>
        <div className="mb-4 flex flex-wrap items-center gap-3">
          <input
            placeholder="Search subjects…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="max-w-xs"
          />
          <label className="flex items-center gap-2 text-sm">
            <input type="checkbox" checked={includeHidden} onChange={(e) => setIncludeHidden(e.target.checked)} />
            Show hidden
          </label>
          {selected.length > 0 && (
            <>
              <Button variant="outline" size="sm" onClick={() => runBulk('hide')} className="gap-1">
                <EyeOff size={14} /> Hide
              </Button>
              <Button variant="outline" size="sm" onClick={() => runBulk('unhide')} className="gap-1">
                <Eye size={14} /> Unhide
              </Button>
              <Button variant="danger" size="sm" onClick={() => runBulk('delete')} className="gap-1">
                <Trash2 size={14} /> Delete
              </Button>
            </>
          )}
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
                  <th className="pb-2">
                    <input type="checkbox" checked={selected.length === subjects.length && subjects.length > 0} onChange={toggleAll} />
                  </th>
                  <th className="pb-2">Code</th>
                  <th className="pb-2">Name</th>
                  <th className="pb-2">Grade</th>
                  <th className="pb-2">Strand</th>
                  <th className="pb-2">Status</th>
                  <th className="pb-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                {subjects.map((s) => (
                  <tr key={s.id} className="border-b last:border-0">
                    <td className="py-3">
                      <input type="checkbox" checked={selected.includes(s.id)} onChange={() => toggleSelect(s.id)} />
                    </td>
                    <td>{s.code}</td>
                    <td>{s.name}</td>
                    <td>{s.grade_level?.name}</td>
                    <td>{s.strand?.code}</td>
                    <td>
                      <Badge variant={s.is_hidden ? 'warning' : 'success'}>
                        {s.is_hidden ? 'Hidden' : 'Visible'}
                      </Badge>
                    </td>
                    <td>
                      <Button
                        variant="ghost"
                        className="text-red-600"
                        onClick={() => {
                          if (window.confirm(`Delete subject "${s.name}"?`)) deleteMutation.mutate(s.id);
                        }}
                      >
                        <Trash2 size={16} />
                      </Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </Card>
    </div>
  );
}
