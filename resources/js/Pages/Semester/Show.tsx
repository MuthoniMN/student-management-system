import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, Link } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TSubject, TResult, TFlash } from "@/types/";
import ResultsTable from "@/Components/ResultsTable";
import SecondaryButton from "@/Components/SecondaryButton";
import PrimaryButton from "@/Components/PrimaryButton";
import { FaAngleLeft } from "react-icons/fa6";

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
                <div className="flex gap-2 items-center">
                    <Link href={route('semesters.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">{semester.title} ({semester.start_date} - {semester.end_date})</h2>
                </div>
            }
        >
            <Head title={semester.title} />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
                <div className="flex justify-between">
                    <h3 className="text-xl font-bold">Results</h3>
                </div>
                <section className="flex flex-wrap gap-2 px-4 justify-between">
                {
                    grades.map(grade => (
                        <div className="w-full md:w-1/4 hover:shadow-md p-4 flex flex-col items-end border-[1px] border-gray-200 space-y-2">
                            <h3 className="text-lg font-bold">{grade.name}</h3>
                            <Link href={route('semesters.results', [semester.id, grade.id])}>
                                <PrimaryButton>View Results</PrimaryButton>
                            </Link>
                        </div>
                    ))
                }
            </section>
            </section>
        </AuthenticatedLayout>
    );
}
