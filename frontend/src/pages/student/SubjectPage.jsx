import { useParams } from 'react-router-dom';

export default function SubjectPage() {
  const { subjectId } = useParams();

  return (
    <div>
      <h1 className="text-2xl font-bold">Subject Overview</h1>
      <p>Subject ID: {subjectId}</p>
    </div>
  );
}