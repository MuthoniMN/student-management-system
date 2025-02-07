import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import ExamForm from "@/Components/ExamForm";
import { TGrade, TSemester, TSubject } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function CreateExam({ semesters, grades, subject } : { grades: TGrade[], semesters: TSemester[], subject: TSubject }){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4">
                    <SecondaryButton>
                        <Link href={route('subjects.show', subject.id)}>
                            <FaAngleLeft />
                        </Link>
                    </SecondaryButton>
                    <h1 className="text-xl font-bold">Create a New Assessment</h1>
                </div>
            }
        >
            <Head title="Create An Assessment" />
            <section className="py-8 w-fit mx-auto">
                <ExamForm grades={grades} semesters={semesters} subject={subject} />
            </section>
        </AuthenticatedLayout>
    );
}
