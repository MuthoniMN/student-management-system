import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import { TGrade } from "@/Pages/Grade/List";
import StudentForm from "@/Components/StudentForm";
import { TStudent } from "@/Pages/Student/List";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

type TParent = {
    id: number,
    name: string,
    email: string,
    phone_number: string,
    address: string,
}

export default function Edit({ parents, grades, student }: { parents: TParent[], grades: TGrade[], student: TStudent }){

    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-6">
                    <Link href={route('students.show', student.id)}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Edit Student Profile</h2>
                </div>
            }
        >
            <Head title="Edit Student" />
            <section>
                <StudentForm parents={parents} grades={grades} student={student} />
            </section>
        </AuthenticatedLayout>
    );
}
