import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TYear } from "@/types/";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaPenToSquare, FaTrash, FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";

export default function Show({ student, parent, grade, semesters, years } : {
    student: TStudent,
    parent: any,
    grade: TGrade,
    semesters: TSemester[],
    years: TYear[]
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
            </section>

            <section className="space-y-6 p-4">
                <div className="space-y-4">
                    <h3 className="font-bold text-lg">Semester Reports</h3>
                    <section className="flex flex-wrap gap-2 justify-between">
                        {
                            semesters.map(sem => (
                                <div className="w-full md:w-1/4 hover:shadow-md p-4 flex flex-col items-end border-[1px] border-gray-200 space-y-2">
                                    <h3 className="text-lg font-bold w-full">{sem.title} - {sem.name}</h3>
                                    <Link href={route('students.results', [student.id, sem.id])}>
                                        <PrimaryButton>View Results</PrimaryButton>
                                    </Link>
                                </div>
                            ))
                        }
                    </section>
                </div>
                <div className="space-y-4">
                    <h3 className="font-bold text-lg">Yearly Reports</h3>
                    <section className="flex flex-wrap gap-2 justify-between">
                        {
                            years.map(sem => (
                                <div className="w-full md:w-1/4 hover:shadow-md p-4 flex flex-col items-end border-[1px] border-gray-200 space-y-2" key={sem.id}>
                                    <h3 className="text-lg font-bold w-full">{sem.year} - {sem.grade}</h3>
                                    <Link href={route('students.yearly-results', [student.id, sem.id])}>
                                        <PrimaryButton>View Results</PrimaryButton>
                                    </Link>
                                </div>
                            ))
                        }
                        </section>
                    </div>
            </section>
        </AuthenticatedLayout>
    );
}
