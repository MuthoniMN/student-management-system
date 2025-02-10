import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { TExam, TSubject, TResult, TStudent, TFlash } from "@/types/";
import ResultsTable from "@/Components/ResultsTable";
import { FaAngleLeft } from "react-icons/fa6";

export default function ExamShow({ students, exam, subject, results }:
        {
        students: TStudent[],
        exam: TExam,
        subject: TSubject,
        results: TResult[]
}){
    const flash = usePage().props.flash as TFlash;
    return  (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <SecondaryButton>
                        <Link href={route('subjects.show', subject.id)}>
                            <FaAngleLeft />
                        </Link>
                    </SecondaryButton>
                    <h2 className="font-bold text-xl">{subject.title} {exam.title} - {exam.exam_date}</h2>
                </div>
            }
        >
            <Head title={exam.title} />
            <section className="bg-white mt-4 w-fit md:w-full py-4 px-2 rounded-lg space-y-4">
                <div className="flex justify-between">
                    <h3 className="text-xl font-bold">Results</h3>
                    <PrimaryButton>
                        <Link href={route('subjects.exams.results.create', [subject,exam])}>Add Student Results</Link>
                    </PrimaryButton>
                </div>
                <ResultsTable results={results} perPage={7} students={students} />
             { flash && (flash.create || flash.update) && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.create || flash.update}</p>
                </div>
            )}
            { flash && flash.delete && (
                <div className="bg-red-300 text-red-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.delete}</p>
                </div>
            )}

            </section>
        </AuthenticatedLayout>
    );
}
