import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import SubjectForm from "@/Components/SubjectForm";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function CreateSubject(){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('subjects.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h1 className="text-xl font-bold">Create a New Subject</h1>
                </div>
            }
        >
            <Head title="Create Academic Year" />
            <section className="py-8 w-fit mx-auto">
                <SubjectForm />
            </section>
        </AuthenticatedLayout>
    );
}
