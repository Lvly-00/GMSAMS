import { useNavigate } from 'react-router-dom';

export default function GradebookPage() {
  const navigate = useNavigate();

  return (
    <div>
      <h1 className="mb-6 text-2xl font-bold">Gradebook</h1>

      <div
        className="cursor-pointer rounded-lg border p-4 hover:bg-slate-50"
        onClick={() => navigate('/teacher/class-record')}
      >
        <h2 className="font-semibold">BSIT 1-A</h2>
        <p className="text-sm text-muted-foreground">
          35 students enrolled
        </p>
      </div>
    </div>
  );
}