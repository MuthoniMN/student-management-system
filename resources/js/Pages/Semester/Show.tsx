import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TSubject, TResult, TFlash } from "@/types/";
import ResultsTable from "@/Components/ResultsTable";

export default function SemesterShow({ students, semester, subjects, grades, results }:
        {
        students: TStudent[],
        subjects: TSubject[],
        semester: TSemester,
        grades: TGrade[],
        results: TResult[]
}){
    const flash = usePage().props.flash as TFlash;
    return  (
        <AuthenticatedLayout
            header={
                <>
                    <h2 className="font-bold text-xl mb-4">{semester.title} ({semester.start_date} - {semester.end_date})</h2>
                </>
            }
        >
            <Head title={semester.title} />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
                <div className="flex justify-between">
                    <h3 className="text-xl font-bold">Results</h3>
                </div>
                <ResultsTable results={results} perPage={7} students={students} subjects={subjects} grades={grades} />
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
