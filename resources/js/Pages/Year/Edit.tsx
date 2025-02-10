import { Head, usePage, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import YearForm from "@/Components/YearForm";
import { TYear, TFlash } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function EditYear({ year } : { year: TYear }){
    const flash  = usePage().props.flash as TFlash;
    return (
        <AuthenticatedLayout
            header={
                <div className="flex gap-6">
                    <Link href={route('years.index')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Edit Academic Year</h2>
                </div>
            }
        >
            <Head title="Edit Academic Year" />
            <section className="py-8 w-fit mx-auto">
                <YearForm year={year} />
            </section>
            { flash && flash.update && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Grade updated successfully!</p>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
