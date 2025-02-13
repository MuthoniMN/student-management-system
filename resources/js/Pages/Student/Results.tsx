import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import { TStudentResult, TSemester } from "@/types/";
import StudentsResults from "@/Components/StudentsResults";
import { FaAngleLeft } from "react-icons/fa6";
import SecondaryButton from "@/Components/SecondaryButton";
import PrimaryButton from "@/Components/PrimaryButton";

export default function DisplayResults({ results, semester }: { results: TStudentResult, semester: TSemester }){
    const print = () => {
        window.open(route('students.results.print', [results.id, semester.id]));
    }
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <div className="flex gap-4 w-fit items-center">
                        <Link href={route('students.show', results.id)}>
                            <SecondaryButton>
                                <FaAngleLeft />
                            </SecondaryButton>
                        </Link>
                        <h2 className="text-xl font-bold">Student Results</h2>
                    </div>
                    <PrimaryButton onClick={print} >Print Report</PrimaryButton>
                </div>
            }
        >
            <Head title="Student Results" />
            <section>
                <StudentsResults results={results} />
            </section>
        </AuthenticatedLayout>
    );
}
