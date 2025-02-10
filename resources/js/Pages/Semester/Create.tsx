import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from "@inertiajs/react";
import SemesterForm from "@/Components/SemesterForm";
import { TYear } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function Create({ years }: { years: TYear[]}){

    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('years.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Create a Semester</h2>
                </div>
            }
        >
            <Head title="Create Semester" />
            <section className="w-full mx-auto">
                <SemesterForm years={years} />
            </section>
        </AuthenticatedLayout>
    );
}
