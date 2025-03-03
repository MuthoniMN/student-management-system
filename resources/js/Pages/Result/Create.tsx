import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { TExam, TStudent, TSubject, TYear, TSemester, TGrade } from "@/types/";
import ResultForm from "@/Components/ResultForm";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function CreateResult({ students, exam, subject } : { exam: TExam, students: TStudent[], subject: TSubject }){
    console.log(exam);
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4">
                    <SecondaryButton>
                        <Link href={route('subjects.exams.show', [subject, exam])}>
                            <FaAngleLeft />
                        </Link>
                    </SecondaryButton>
                    <div className="space-y-2">
                        <h1 className="text-xl font-bold">Add Results for {subject.title}</h1>
                        <p>{(exam.grade as TGrade).name} {exam.title} - {(exam.semester.year as TYear).year} {(exam.semester as TSemester).title} </p>
                    </div>
                </div>
            }>
            <Head title="Add Results" />
            <section className="py-8 w-fit mx-auto">
                <ResultForm students={students} exam={exam} subject={subject} />
            </section>
        </AuthenticatedLayout>
    );
}
