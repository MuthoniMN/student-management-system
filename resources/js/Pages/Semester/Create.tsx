import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";
import SemesterForm from "@/Components/SemesterForm";
import { TYear } from "@/Components/YearForm";

export default function Create({ years }: { years: TYear[]}){

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold">Create Semester</h2>
            }
        >
            <Head title="Create Semester" />
            <section className="w-full mx-auto">
                <SemesterForm years={years} />
            </section>
        </AuthenticatedLayout>
    );
}
