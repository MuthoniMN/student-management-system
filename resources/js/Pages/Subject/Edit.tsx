import { Head, usePage } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import SubjectForm, { TSubject } from "@/Components/SubjectForm";
import { TGrade } from "@/Pages/Grade/List";

export default function EditSubject({ grades, subject } : { grades: TGrade[], subject: TSubject }){
    console.log(subject);
    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-xl font-bold">Edit {subject.title}</h1>
            }
        >
            <Head title={`Edit ${subject.title}`} />
            <section className="py-8 w-fit mx-auto">
                <SubjectForm grades={grades} subject={subject} />
            </section>
        </AuthenticatedLayout>
    );
}
