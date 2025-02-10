import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TSubject, TResult } from "@/types/";
import ResultsTable from "@/Components/ResultsTable";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

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
                <div className="flex gap-4 items-center">
                    <Link href={route('archive')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Archived Results</h2>
                </div>
            }
        >
            <Head title="Archived Results" />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
                <ResultsTable results={results} perPage={7} students={students} subjects={subjects} grades={grades} semesters={semesters} archive={true} />
            </section>
        </AuthenticatedLayout>
    );
}
