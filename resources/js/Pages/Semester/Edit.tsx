import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import SemesterForm from "@/Components/SemesterForm";
import { TYear, TSemester } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function EditSemester({ years, semester }: { years: TYear[], semester: TSemester }){

    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('years.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Edit Semester Details</h2>
                </div>
            }
        >
            <Head title="Edit Semester" />
            <section>
                <SemesterForm years={years} semester={semester} />
            </section>
        </AuthenticatedLayout>
    );
}
