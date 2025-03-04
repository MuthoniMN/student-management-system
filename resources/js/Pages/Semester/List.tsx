import { useState, useEffect } from "react";
import { TYear, TSemester, TFlash } from "@/types/";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage, useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import {  FaTrash, FaPenToSquare } from "react-icons/fa6";
import Pagination from "@/Components/Pagination";

export default function SemesterList({ semesters, years }: { semesters: TSemester[], years: TYear[] }){
    const perPage = 10;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(semesters);
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));
    const [filters, setFilters] = useState('');
    const flash = usePage().props.flash as TFlash;

    const { submit, delete: destroy } = useForm();
    const handleSubmit = (e, semester: TSemester) => {
        e.preventDefault();
        submit('delete', route('semesters.destroy', semester.id));
    };

    useEffect(() => {
        setPaginatedData(data.slice(start,end));
    }, [page, data]);

    useEffect(() => {
        if(filters){
            setData(semesters.filter(semester => semester.academic_year_id == +filters));
        }else {
            setData(semesters);
        }
    }, [filters]);

    return (
        <AuthenticatedLayout header={
                <div className="w-full flex justify-between items-center">
                    <h2 className="text-xl font-bold">All Semesters</h2>
                    <PrimaryButton>
                        <Link href={route('semesters.create')}>
                            Create Semester
                        </Link>
                    </PrimaryButton>
                </div>

        } >
            <Head title="Semesters" />
          <section className="h-fit w-fit md:w-full mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
            <div className="py-4 flex w-full gap-6 justify-between flex-wrap">
                <div className="flex justify-end">
                    <form className="flex gap-2 items-center min-w-320px">
                        <p>Filter by Academic Year: </p>
                        <select name="value" value={filters} onChange={(e) => setFilters(e.target.value)}>
                            <option value="">----</option>
                            {
                        years.map((year: TYear) => (
                                <option key={year.id} value={year.id}>{year.year}</option>
                        ))
                            }
                        </select>
                    </form>
                </div>
            </div>
                <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                    <thead>
                        <tr className="divide-x-2 divide-gray-300 text-left">
                            <th className="px-2">Semester</th>
                            <th className="px-2">Academic Year</th>
                            <th className="px-2">Start Date</th>
                            <th className="px-2">End Date</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y-2 divide-gray-300">
                        {
                            paginatedData.length > 0 ?
                            paginatedData.map(semester => (
                                <tr className="divide-x-2 divide-gray-300" key={semester.id}>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">
                                    <Link href={route('semesters.show', semester)}>{semester.title}</Link>
                                    </td>
                                    <td className="px-2 min-w-36">{(semester.year as TYear).year}</td>
                                    <td className="px-2 min-w-24">{semester.start_date}</td>
                                    <td className="px-2 min-w-36">{semester.end_date}</td>
                                    <td className="px-2 text-center w-fit">
                                        <Link href={route('semesters.edit', semester)}>
                                            <SecondaryButton>
                                                <FaPenToSquare />
                                            </SecondaryButton>
                                        </Link>
                                    </td>
                                    <td className="px-2 text-center w-fit">
                                        <DangerButton onClick={(e) => handleSubmit(e, semester)}>
                                            <FaTrash />
                                        </DangerButton>
                                    </td>
                                </tr>
                            )) :
                            <tr className="text-center">
                                <td colSpan={6}>No Semesters Available!</td>
                            </tr>
                        }
                    </tbody>
                </table>
                <Pagination perPage={perPage} length={data.length} page={page} setPage={setPage} />
                { flash && flash.delete && (
                <div className="bg-red-300 text-red-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Semester deleted successfully!</p>
                </div>
            )}
            { flash && flash.update && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>Semester updated successfully!</p>
                </div>
            )}
        </section>

        </AuthenticatedLayout>
    );
}
