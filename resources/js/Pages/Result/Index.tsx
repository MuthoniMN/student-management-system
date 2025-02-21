import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import ResultsSummaryTable from "@/Components/ResultsSummaryTable";
import { TGrade, TStudent, TResultsSummary, TSemester, TYear } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function Results({ exam_results, grades, semesters, students, years }:
    {
        exam_results: TResultsSummary[],
        grades?: TGrade[],
        semesters?: TSemester[],
        students?: TStudent[],
        years?: TYear[],
    }){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('semesters.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Results</h2>
                </div>
            }
        >
            <Head title="Results" />
            <section className="w-full mx-auto p-6">
                <ResultsSummaryTable exam_results={Object.values(exam_results)} grades={grades} semesters={semesters} years={years} students={students} perPage={10} />
            </section>
        </AuthenticatedLayout>
    );
}
