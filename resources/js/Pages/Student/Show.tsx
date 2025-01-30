import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { TStudent } from "@/Pages/Student/List";
import { TGrade } from "@/Pages/Grade/List";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaPenToSquare, FaTrash } from "react-icons/fa6";

export default function Show({ student, parent, grade }: { student: TStudent, parent: any, grade: TGrade }){
    const { submit, delete: destroy } = useForm();
    const handleSubmit = (e) => {
        e.preventDefault();
        submit('delete', route('students.destroy', student.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between w-full items-center">
                    <h1 className="text-xl font-bold">{student.name}'s Profile</h1>
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
            <section className="p-4">
                <div>
                    <h2 className="font-bold text-lg">Student Information</h2>
                    <div className="py-4">
                        <p><span className="font-bold">Student ID: </span>{student.studentId}</p>
                        <p><span className="font-bold">Student Name: </span>{student.name}</p>
                        <p><span className="font-bold">Grade: </span>{grade.name}</p>
                    </div>
                    <h2 className="font-bold text-lg">Contact Information</h2>
                    <div className="py-4">
                        <p><span className="font-bold">Email: </span>{parent.email}</p>
                        <p><span className="font-bold">Phone Number: </span>{parent.phone_number}</p>
                        <p><span className="font-bold">Address: </span>{parent.address}</p>
                    </div>
                </div>
            </section>
        </AuthenticatedLayout>
    );
}
