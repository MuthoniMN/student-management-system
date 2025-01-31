import { Head } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import YearForm from "@/Components/YearForm";

export default function CreateYear(){
    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-xl font-bold">Create Academic Year</h1>
            }
        >
            <Head title="Create Academic Year" />
            <section className="py-8 w-fit mx-auto">
                <YearForm />
            </section>
        </AuthenticatedLayout>
    );
}
