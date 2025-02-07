import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";
import SemesterForm from "@/Components/SemesterForm";
import { TYear, TSemester } from "@/types/";

export default function EditSemester({ years, semester }: { years: TYear[], semester: TSemester }){

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold">Edit Semester Details</h2>
            }
        >
            <Head title="Edit Semester" />
            <section>
                <SemesterForm years={years} semester={semester} />
            </section>
        </AuthenticatedLayout>
    );
}
