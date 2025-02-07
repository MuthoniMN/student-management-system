import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { FaPenToSquare, FaTrash } from "react-icons/fa6";
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';
import { TYear, TFlash } from "@/types/";

export default function YearList({ years }: {
    years: TYear[]
}) {
    const { submit, delete: destroy } = useForm();
    const flash = usePage().props.flash as TFlash;

    const handleSubmit = (e, year: TYear) => {
        e.preventDefault();
        submit('delete', route('years.destroy', year.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="w-full flex justify-between">
                    <h2 className="text-xl font-semibold text-gray-800">Available Academic Years</h2>
                    <Link href={route('years.create')}>
                        <PrimaryButton>
                            Create Academic Year
                        </PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Grades" />
            <ul className="mx-auto my-6 bg-white rounded-lg py-4 divide-y-[1px] divide-gray-100">
                {
                    years.length > 0 ?
                    years.map(year => (
                        <li key={year.id} className="w-full py-2 px-4 space-y-2">
                            <div className="flex w-full items-center justify-between">
                            <Link href={route('years.show', year.id)}>
                                <h3 className="text-lg font-bold">{year.year}  <span className="italic font-light">({new Date(year.start_date).toDateString()} - {new Date(year.end_date).toDateString()})</span></h3>
                            </Link>
                                <div className="flex gap-4 items-center">
                                    <Link href={route('years.edit', { id: year.id })}>
                                        <PrimaryButton>
                                            <FaPenToSquare />
                                        </PrimaryButton>
                                    </Link>
                                    <DangerButton onClick={(e) => handleSubmit(e, year)}>
                                        <FaTrash />
                                    </DangerButton>
                                </div>
                            </div>
                        </li>
                    )) :
                    <div className="w-full h-[65vh] flex items-center justify-center">
                        <h2 className="text-lg font-bold">No Academic Years added</h2>
                    </div>
                }
            </ul>
            { flash && flash.delete && (
                <div className="bg-red-300 text-red-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Academic year deleted successfully!</p>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
