import { useState, useEffect } from "react";
import { Head, Link, usePage, useForm } from '@inertiajs/react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import Pagination from "@/Components/Pagination";
import { TSubject, TFlash } from "@/types/";
import { FaTrash, FaPenToSquare } from "react-icons/fa6";

export default function Index({ subjects }:  { subjects: TSubject[] }){
    const flash = usePage().props.flash as TFlash;
    const perPage = 9;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(subjects);
    const [paginatedData, setPaginatedData] = useState(subjects.slice(start, end));

    const { submit } = useForm();
    const handleSubmit = (e, subject: TSubject) => {
        e.preventDefault();
        submit('delete', route('subjects.destroy', subject.id));
    };

    useEffect(() => {
        setPaginatedData(subjects.slice(start,end));
    }, [page, data]);

    return (
        <AuthenticatedLayout header={
                <div className="w-full flex justify-between items-center">
                    <h2 className="text-xl font-bold">All Available Subjects</h2>
                    <PrimaryButton>
                        <Link href={route('subjects.create')}>
                            Create Subject
                        </Link>
                    </PrimaryButton>
                </div>

        }>
            <Head title="All Subjects" />
            <section className="h-fit mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
                <section className="w-full flex gap-6 flex-wrap">
                {
                    paginatedData.length > 0 ?
                        data.map(subject =>(
                            <Link href={route('subjects.show', subject)} key={subject.id} className="w-full md:w-[29%] h-fit min-h-36 border-[1px] border-gray-300 hover:shadow-md px-4 py-2 space-y-2">
                                <h3 className="text-lg font-bold">{subject.title}</h3>
                                <p>{subject.description}</p>

                                <div className="flex gap-4 w-full justify-end">
                                    <Link href={route('subjects.edit', subject)}>
                                        <SecondaryButton>
                                            <FaPenToSquare />
                                        </SecondaryButton>
                                    </Link>
                                    <DangerButton onClick={(e) => handleSubmit(e, subject)}>
                                        <FaTrash />
                                    </DangerButton>
                                </div>
                            </Link>
                    )) : (
                        <div className="w-full h-[65vh] flex justify-center items-center">
                            <h2 className="font-bold text-lg">No Available Subjects!</h2>
                        </div>
                    )
                }
                </section>
                <Pagination page={page} length={subjects.length} setPage={setPage} perPage={perPage} />
                { flash && flash.update && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.update}</p>
                </div>
            )}
            { flash && flash.delete && (
                <div className="bg-red-300 text-red-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.delete}</p>
                </div>
            )}
        </section>
        </AuthenticatedLayout>
    );
}
