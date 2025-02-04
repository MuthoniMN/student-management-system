import { useState, useEffect } from "react";
import { Head, Link, usePage, useForm } from '@inertiajs/react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import { TSubject } from "@/Components/SubjectForm";
import { FaAnglesLeft, FaAnglesRight, FaTrash, FaPenToSquare, FaDownload } from "react-icons/fa6";
import { TGrade } from "@/Pages/Grade/List";

export default function Index({ subjects, grades }:  { subjects: TSubject[], grades: TGrade[] }){
    console.log(usePage().props);
    const { flash } = usePage().props;
    const perPage = 9;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(subjects.slice(start, end));
    const [filters, setFilters] = useState('');

    const { submit } = useForm();
    const handleSubmit = (e, subject: TSubject) => {
        e.preventDefault();
        submit('delete', route('subjects.destroy', subject.id));
    };

    const prevPage = () => {
        if((page - 1) <= 0) return;

        setPage(page-1);
    }

    const nextPage = () => {
        if((page + 1) > Math.ceil(subjects.length/perPage)) return;

        setPage(page+1);
    }

    useEffect(() => {
        setData(subjects.slice(start,end));
    }, [page]);

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
                    data.length > 0 ?
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
                <div className="py-4 text-center flex justify-between items-center">
                    <p className="font-light text-gray-500 italic">Showing page {page} of {Math.ceil(subjects.length/10)}</p>
                    <div className="flex gap-4">
                        <PrimaryButton onClick={prevPage}>
                            <FaAnglesLeft />
                        </PrimaryButton>
                        {
                            (() => {
                                return Array.from(
                                { length: Math.ceil(subjects.length/10) },
                                (_, i) => (
                                    <p onClick={() => setPage(i+1)} className={`hover:underline ${((i + 1) == page) && 'underline text-blue-700'}`} key={i}>{i+1}</p>
                                )
                            )})()
                        }
                        <PrimaryButton onClick={nextPage}>
                            <FaAnglesRight />
                        </PrimaryButton>
                    </div>
                </div>
            { flash && flash.update && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Subject updated successfully!</p>
                </div>
            )}
            { flash && flash.delete && (
                <div className="bg-red-300 text-red-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Subject deleted successfully!</p>
                </div>
            )}
        </section>
        </AuthenticatedLayout>
    );
}
