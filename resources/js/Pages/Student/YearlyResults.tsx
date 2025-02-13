import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import { TYearResult, TYear, TStudent, TRank } from "@/types/";
import StudentYearResults from "@/Components/StudentYearResults";
import { FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";
import PrimaryButton from "@/Components/PrimaryButton";

export default function YearlyResults({ results, student, year, ranks }: { results: TYearResult, year: TYear, student: TStudent, ranks: TRank }){
    const print = () => {
        window.open(route('students.yearly-results.print', [student.id, year.id]));
    }
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <div className="flex gap-4 w-fit items-center">
                        <Link href={route('students.show', student.id)}>
                            <SecondaryButton>
                                <FaAngleLeft />
                            </SecondaryButton>
                        </Link>
                        <h2 className="text-xl font-bold">Student Results for {year.year}</h2>
                    </div>
                    <PrimaryButton onClick={print} >Print Report</PrimaryButton>
                </div>
            }
        >
            <Head title={`Student Results for ${year.year}`} />
            <section>
                <StudentYearResults student={student} results={results} ranks={ranks} />
            </section>
        </AuthenticatedLayout>
    );
}
