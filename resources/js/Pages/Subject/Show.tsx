import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import { TSubject } from "@/Components/SubjectForm";
import PrimaryButton from "@/Components/PrimaryButton";

export default function SubjectShow({ subject }: { subject: TSubject }){
    return  (
        <AuthenticatedLayout
            header={
                <h2 className="font-bold text-xl">{subject.title} - {subject.description}</h2>
            }
        >
            <section className="bg-white mt-4 py-4 px-2 rounded-lg space-y-4">
            <div className="flex justify-between">
                <h3 className="text-xl font-bold">Exams</h3>
                <PrimaryButton>
                    <Link href={route()}>Create an Assessment</Link>
                </PrimaryButton>
            </div>
            </section>
        </AuthenticatedLayout>
    );
}
