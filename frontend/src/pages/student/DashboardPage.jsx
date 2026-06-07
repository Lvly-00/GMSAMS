import { useNavigate } from 'react-router-dom';

const subjects = [
  { id: 1, name: 'Mathematics', teacher: 'Mr. Smith' },
  { id: 2, name: 'Science', teacher: 'Ms. Johnson' },
  { id: 3, name: 'English', teacher: 'Mrs. Davis' },
];

export default function StudentDashboardPage() {
  const navigate = useNavigate();

  return (
    <div>
      <h1 className="mb-6 text-2xl font-bold">Student Dashboard</h1>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {subjects.map((subject) => (
          <div
            key={subject.id}
            className="cursor-pointer rounded-lg border bg-white p-4 shadow-sm transition hover:shadow-md"
            onClick={() =>
              navigate(`/student/subject-overview/${subject.id}`)
            }
          >
            <h2 className="text-lg font-semibold">{subject.name}</h2>
            <p className="text-sm text-muted-foreground">
              {subject.teacher}
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}