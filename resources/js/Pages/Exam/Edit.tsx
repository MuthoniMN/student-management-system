import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import ExamForm from "@/Components/ExamForm";
import SecondaryButton from "@/Components/SecondaryButton";
import { TGrade, TSubject, TSemester, TExam } from "@/types/";
import { FaAngleLeft } from "react-icons/fa6";

export default function EditExam({ subject, semesters, grades, exam } : { grades: TGrade[], subject: TSubject, exam: TExam, semesters: TSemester[] }){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4">
                <SecondaryButton>
                    <Link href={route('subjects.show', subject.id)}>
                        <FaAngleLeft />
                    </Link>
                </SecondaryButton>
                <h1 className="text-xl font-bold">Edit Assessment: {exam.title}</h1>
                </div>
            }
        >
            <Head title="Edit Assessment" />
            <section className="py-8 w-fit mx-auto">
                <ExamForm grades={grades} semesters={semesters} subject={subject} exam={exam} />
            </section>
        </AuthenticatedLayout>
    );
}
