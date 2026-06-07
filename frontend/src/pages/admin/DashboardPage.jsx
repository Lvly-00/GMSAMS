import { useQuery } from '@tanstack/react-query';
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer, CartesianGrid } from 'recharts';
import { fetchDashboard } from '@/api/admin';
import { Link } from 'react-router-dom';
import { Card, Skeleton, Badge, } from '@/components/ui';

function StatCard({ title, value, subtitle }) {
  return (
    <Card>
      <p className="text-sm text-muted-foreground">{title}</p>
      <p className="mt-2 text-3xl font-bold">{value}</p>
      {subtitle && <p className="mt-1 text-xs text-muted-foreground">{subtitle}</p>}
    </Card>
  );
}

export default function AdminDashboardPage() {
  const { data, isLoading, isError } = useQuery({
    queryKey: ['admin', 'dashboard'],
    queryFn: fetchDashboard,
  });

  if (isLoading) {
    return (
      <div className="space-y-6">
        <Skeleton className="h-8 w-48" />
        <div className="grid gap-4 md:grid-cols-4">
          {[...Array(4)].map((_, i) => (
            <Skeleton key={i} className="h-28" />
          ))}
        </div>
      </div>
    );
  }

  if (isError) {
    return <p className="text-red-600">Failed to load dashboard.</p>;
  }

  const { stats, activity_logs } = data;
  const roleChartData = [
    { name: 'Students', count: stats.users_by_role.students },
    { name: 'Teachers', count: stats.users_by_role.teachers },
    { name: 'Head Teachers', count: stats.users_by_role.head_teachers },
    { name: 'Admins', count: stats.users_by_role.admins },
  ];

  console.log(activity_logs?.data);

  return (
    <div className="space-y-8">
      <div>
        <h2 className="text-2xl font-bold">Admin Dashboard</h2>
        <p className="text-sm text-muted-foreground">System overview and activity feed</p>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <StatCard title="Students" value={stats.users_by_role.students} />
        <StatCard title="Teachers" value={stats.users_by_role.teachers} />
        <StatCard title="Head Teachers" value={stats.users_by_role.head_teachers} />
        <StatCard
          title="Active Accounts"
          value={stats.account_status.active}
          subtitle={`${stats.account_status.inactive} inactive`}
        />
      </div>

      <div className="grid gap-6 lg:grid-cols-2">
        <Card>
          <h3 className="mb-4 font-semibold">Users by Role</h3>
          <ResponsiveContainer width="100%" height={240}>
            <BarChart data={roleChartData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" tick={{ fontSize: 12 }} />
              <YAxis allowDecimals={false} />
              <Tooltip />
              <Bar dataKey="count" fill="#1e40af" radius={[4, 4, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </Card>

        <Card>
          <h3 className="mb-4 font-semibold">Usage Statistics</h3>
          <dl className="grid grid-cols-2 gap-4">
            {Object.entries(stats.usage).map(([key, value]) => (
              <div key={key} className="rounded-md bg-slate-50 p-4">
                <dt className="text-xs uppercase text-muted-foreground">{key.replace(/_/g, ' ')}</dt>
                <dd className="text-2xl font-bold">{value}</dd>
              </div>
            ))}
          </dl>
        </Card>
      </div>

      <Card>
        <h3 className="mb-4 font-semibold">Recent Activity</h3>
        <div className="space-y-3">
          {activity_logs?.data?.length === 0 && (
            <p className="text-sm text-muted-foreground">No activity logged yet.</p>
          )}
          {activity_logs?.map((log) => (
            <div key={log.id} className="flex items-start justify-between border-b pb-3 last:border-0">
              <div>
                <p className="text-sm">{log.description}</p>
                <p className="text-xs text-muted-foreground">
                  {log.user?.username || 'System'} · {log.module_name}
                </p>
              </div>
              <Badge>{log.action_type}</Badge>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
}
