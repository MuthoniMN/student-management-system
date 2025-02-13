import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import { TStudent, TGrade, TSemester, TSubject, TResult, TYear, TFlash } from "@/types/";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function YearShow({ students, year, semesters, subjects, grades, results }:
        {
        students: TStudent[],
        subjects: TSubject[],
        semesters: TSemester[],
        year: TYear,
        grades: TGrade[],
        results: TResult[]
}){
    const flash = usePage().props.flash as TFlash;
    return  (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('years.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">{year.year} ({year.start_date} - {year.end_date})</h2>
                </div>
            }
        >
            <Head title={`${year.year} Academic Year`} />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
                <div className="flex justify-between">
                    <h3 className="text-xl font-bold">Results</h3>
                </div>
                <section className="flex flex-wrap gap-2 px-4 justify-between">
                {
                    grades.map(grade => (
                        <div className="w-full md:w-1/4 hover:shadow-md p-4 flex flex-col items-end border-[1px] border-gray-200 space-y-2">
                            <h3 className="text-lg font-bold">{grade.name}</h3>
                            <Link href={route('years.results', [year.id, grade.id])}>
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
