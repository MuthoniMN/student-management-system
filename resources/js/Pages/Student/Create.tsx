import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import { TGrade, TParent } from "@/types/";
import StudentForm from "@/Components/StudentForm";
import { FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";

export default function Create({ parents, grades }: { parents: TParent[], grades: TGrade[] }){

    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('students.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Create Student Profile</h2>
                </div>
            }
        >
            <Head title="Create Student" />
            <section>
                <StudentForm parents={parents} grades={grades} />
            </section>
        </AuthenticatedLayout>
    );
}
