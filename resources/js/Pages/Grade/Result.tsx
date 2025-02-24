import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import { TSemesterResult, TSemester, TGrade, TYear } from "@/types/";
import RankTable from "@/Components/RankTable";
import { FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";
import PrimaryButton from "@/Components/PrimaryButton";

export default function DisplayResults({ results, semester, grade, year }: { results: TSemesterResult[], semester?: TSemester, year?: TYear, grade: TGrade }){

    const print = () => {
        if(semester){
            window.open(route('semesters.results.print', [semester.id, grade.id]));
        }else if(year) {
            window.open(route('years.results.print', [year.id, grade.id]));
        }
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <div className="flex gap-4 w-fit items-center">
                        <Link href={semester && route('semesters.show', semester.id) || year && route('years.show', year.id)}>
                            <SecondaryButton>
                                <FaAngleLeft />
                            </SecondaryButton>
                        </Link>
                        <h2 className="text-xl font-bold">{semester && semester.title || year && year.year} {grade.name} Results</h2>
                    </div>
                    <PrimaryButton onClick={print} >Print Report</PrimaryButton>
                </div>
            }
        >
            <Head title="Grade Results" />
            <section>
                <RankTable results={Object.values(results)} />
            </section>
        </AuthenticatedLayout>
    );
}
