import { useState, useEffect } from "react";
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import DangerButton from "@/Components/DangerButton";
import Pagination from "@/Components/Pagination";
import { TSubject } from "@/types/";
import { LuArchiveRestore } from "react-icons/lu";

export default function SubjectArchive({ subjects }:  { subjects?: TSubject[] }){
    const perPage = 9;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(subjects || []);
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));

    const { submit } = useForm();
    const handleSubmit = (e, subject: TSubject) => {
        e.preventDefault();
        submit('put', route('subjects.restore', { id: subject.id }));
    };

    useEffect(() => {
        setPaginatedData(data.slice(start,end));
    }, [page, data]);

    return (
        <AuthenticatedLayout header={
                <div className="w-full flex justify-between items-center">
                    <h2 className="text-xl font-bold">Archived Subjects</h2>
                </div>

        }>
            <Head title="Archived Subjects" />
            <section className="h-fit w-fit md:w-full mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
                <section className="w-full flex gap-6 flex-wrap">
                {
                    paginatedData.length > 0 ?
                        paginatedData.map(subject =>(
                            <Link href={route('subjects.show', subject)} key={subject.id} className="w-full md:w-[29%] h-fit min-h-36 border-[1px] border-gray-300 hover:shadow-md px-4 py-2 space-y-2">
                                <h3 className="text-lg font-bold">{subject.title}</h3>
                                <p>{subject.description}</p>

                                <div className="flex gap-4 w-full justify-end">
                                    <DangerButton onClick={(e) => handleSubmit(e, subject)}>
                                        <LuArchiveRestore />
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
                <Pagination page={page} length={data.length} setPage={setPage} perPage={perPage} />
        </section>
        </AuthenticatedLayout>
    );
}
