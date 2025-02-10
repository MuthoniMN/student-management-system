import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import YearForm from "@/Components/YearForm";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function CreateYear(){
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-4 items-center">
                    <Link href={route('years.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Create an Academic Year</h2>
                </div>
            }
        >
            <Head title="Create Academic Year" />
            <section className="py-8 w-fit mx-auto">
                <YearForm />
            </section>
        </AuthenticatedLayout>
    );
}
