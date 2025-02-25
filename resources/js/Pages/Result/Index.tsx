import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";
import ResultsSummaryTable from "@/Components/ResultsSummaryTable";
import { TGrade, TStudent, TResultSummary, TSemester, TYear } from "@/types/";

export default function Results({ exam_results, grades, years }:
    {
        exam_results: TResultSummary[],
        grades?: TGrade[],
        semesters?: TSemester[],
        students?: TStudent[],
        years?: TYear[],
    }){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <h2 className="font-bold text-xl">Results</h2>
                </div>
            }
        >
            <Head title="Results" />
            <section className="w-full mx-auto p-6">
                <ResultsSummaryTable exam_results={Object.values(exam_results)} grades={grades} years={years} perPage={10} />
            </section>
        </AuthenticatedLayout>
    );
}
