import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";
import { TGrade } from "@/Pages/Grade/List";
import StudentForm from "@/Components/StudentForm";

type TParent = {
    id: number,
    name: string,
    email: string,
    phone_number: string,
    address: string,
}

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
