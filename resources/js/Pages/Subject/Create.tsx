import { Head } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import SubjectForm from "@/Components/SubjectForm";

export default function CreateSubject(){
    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-xl font-bold">Create a New Subject</h1>
            }
        >
            <Head title="Create Academic Year" />
            <section className="py-8 w-fit mx-auto">
                <SubjectForm />
            </section>
        </AuthenticatedLayout>
    );
}
