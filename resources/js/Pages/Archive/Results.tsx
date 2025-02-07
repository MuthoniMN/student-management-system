import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TSubject, TResult } from "@/types/";
import ResultsTable from "@/Components/ResultsTable";

export default function ArchivedResults({ students, semesters, subjects, grades, results }:
        {
        students: TStudent[],
        subjects: TSubject[],
        semesters: TSemester[],
        grades: TGrade[],
        results: TResult[]
}){
    return  (
        <AuthenticatedLayout
            header={
                <>
                    <h2 className="font-bold text-xl mb-4">Archived Results</h2>
                </>
            }
        >
            <Head title="Archived Results" />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
                <ResultsTable results={results} perPage={7} students={students} subjects={subjects} grades={grades} semesters={semesters} archive={true} />
            </section>
        </AuthenticatedLayout>
    );
}
