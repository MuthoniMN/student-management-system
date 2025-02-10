import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { TStudent, TGrade, TResult, TSemester, TYear, TSubject } from "@/types/";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaPenToSquare, FaTrash, FaAngleLeft } from "react-icons/fa6";
import ResultsTable from "@/Components/ResultsTable";
import SecondaryButton from "@/Components/SecondaryButton";

export default function Show({ student, parent, grade, results, grades, semesters, years, subjects } : {
    student: TStudent,
    parent: any,
    grade: TGrade,
    results: TResult[],
    grades: TGrade[],
    semesters: TSemester[],
    years: TYear[],
    subjects: TSubject[],
}){
    const { submit, delete: destroy } = useForm();
    const handleSubmit = (e) => {
        e.preventDefault();
        submit('delete', route('students.destroy', student.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between w-full items-center">
                    <div className="flex gap-4 items-center">
                    <Link href={route('students.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                        <h1 className="text-xl font-bold">{student.name}'s Profile</h1>
                    </div>
                    <div className="flex gap-4">
                        <Link href={route('students.edit', student)}>
                            <PrimaryButton>
                                <FaPenToSquare />
                            </PrimaryButton>
                        </Link>
                        <DangerButton onClick={(e) => handleSubmit(e)}>
                            <FaTrash />
                        </DangerButton>

                    </div>
                </div>
            }
        >
            <Head title="Student Profile" />
            <section className="p-4 w-fit md:w-full">
                <div className="flex justify-between gap-4 flex-col lg:flex-row">
                    <div>
                        <h2 className="font-bold text-lg">Student Information</h2>
                        <div className="py-4">
                            <p><span className="font-bold">Student ID: </span>{student.studentId}</p>
                            <p><span className="font-bold">Student Name: </span>{student.name}</p>
                            <p><span className="font-bold">Grade: </span>{grade.name}</p>
                        </div>
                    </div>
                    <div>
                        <h2 className="font-bold text-lg">Contact Information</h2>
                        <div className="py-4">
                            <p><span className="font-bold">Email: </span>{parent.email}</p>
                            <p><span className="font-bold">Phone Number: </span>{parent.phone_number}</p>
                            <p><span className="font-bold">Address: </span>{parent.address}</p>
                        </div>
                    </div>
                </div>
                <ResultsTable results={results} grades={grades} semesters={semesters} years={years} subjects={subjects} perPage={10} />
            </section>
        </AuthenticatedLayout>
    );
}
