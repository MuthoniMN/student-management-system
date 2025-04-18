import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { TExam, TStudent, TSubject, TResult } from "@/types/";
import ResultForm from "@/Components/ResultForm";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function EditResult({ students, exam, subject, result } : { exam: TExam, students: TStudent[], subject: TSubject, result: TResult }){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4">
                    <SecondaryButton>
                        <Link href={route('subjects.exams.show', [subject.id, exam.id])}>
                            <FaAngleLeft />
                        </Link>
                    </SecondaryButton>
                    <h1 className="text-xl font-bold">Edit {(result.student as TStudent).name} Results for {subject.title} {exam.title}</h1>
                </div>
            }
        >
            <Head title="Edit Results" />
            <section className="py-8 w-fit mx-auto">
                <ResultForm result={result} students={students} exam={exam} subject={subject} />
            </section>
        </AuthenticatedLayout>
    );
}
