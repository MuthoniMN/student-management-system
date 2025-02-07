import { Head } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import SubjectForm from "@/Components/SubjectForm";
import { TSubject }  from "@/types/";

export default function EditSubject({ subject } : { subject: TSubject }){
    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-xl font-bold">Edit {subject.title}</h1>
            }
        >
            <Head title={`Edit ${subject.title}`} />
            <section className="py-8 w-fit mx-auto">
                <SubjectForm subject={subject} />
            </section>
        </AuthenticatedLayout>
    );
}
