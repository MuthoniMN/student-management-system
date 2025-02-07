import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";
import { TGrade, TParent } from "@/types/";
import StudentForm from "@/Components/StudentForm";

export default function Create({ parents, grades }: { parents: TParent[], grades: TGrade[] }){

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold">Create Student Profile</h2>
            }
        >
            <Head title="Create Student" />
            <section>
                <StudentForm parents={parents} grades={grades} />
            </section>
        </AuthenticatedLayout>
    );
}
