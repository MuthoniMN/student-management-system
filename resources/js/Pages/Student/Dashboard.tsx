import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { TStudent, TGrade, TSemester, TYear } from "../../types/";
import { Head, Link, useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaPenToSquare, FaTrash } from "react-icons/fa6";
import { getGrade } from "@/utils/getGrade";
import GradeResults from "@/Components/GradeResults";

const StudentDashboard = ({ student, grade, semesters, years } : {
    student: TStudent,
    grade: TGrade,
    semesters: TSemester[],
    years: TYear[]
}) => {
    const { submit, delete: destroy } = useForm();
    console.log(semesters, years, student);
    const handleSubmit = (e) => {
        e.preventDefault();
        submit('delete', route('students.destroy', student.id));
    };

    return (
        <AuthenticatedLayout header={
                <div className="flex justify-between w-full items-center">
                    <div className="flex gap-4 items-center">
                        <h1 className="text-xl font-bold">Welcome, {student.name}</h1>
                    </div>
                </div>
        }>
            <Head title="Student Dashboard" />
            <section className="lg:px-16 py-6 flex flex-col gap-4">
                    <div>
                        <h2 className="font-bold text-lg">Student Information</h2>
                        <div className="py-4">
                            <p><span className="font-bold">Student ID: </span>{student.studentId}</p>
                            <p><span className="font-bold">Student Name: </span>{student.name}</p>
                            <p><span className="font-bold">Grade: </span>{(student.grade as TGrade).name}</p>
                        </div>
                    </div>
                <h1 className="text-lg font-bold">
                    View your Results
                </h1>
                {
                    years.map(year => (
                        <GradeResults grade={`Grade ${getGrade(student.grade_id, year.year)}`} year={year.year} semesters={semesters.filter(sem => sem.academic_year_id === year.id)} studentId={student.id} />
                    ))
                }
            </section>
        </AuthenticatedLayout>
    );
}

export default StudentDashboard;
