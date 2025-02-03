import { useState, useEffect } from "react";
import { TSemester } from "@/Components/SemesterForm";
import { TYear } from "@/Components/YearForm";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage, useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaAnglesLeft, FaAnglesRight, FaTrash, FaPenToSquare } from "react-icons/fa6";

export default function SemesterList({ semesters, years }: { semesters: TSemester[], years: TYear[] }){
    const perPage = 10;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(semesters.slice(start, end));
    const [filters, setFilters] = useState('');
    const { flash } = usePage().props;

    const { submit, delete: destroy } = useForm();
    const handleSubmit = (e, semester: TSemester) => {
        e.preventDefault();
        submit('delete', route('semesters.destroy', semester.id));
    };

    useEffect(() => {
        setData(semesters.slice(start,end));
    }, [page]);


    useEffect(() => {
        if(filters){
            setData(semesters.filter(semester => semester.academic_year_id == +filters).slice(start, end));
        }else {
            setData(semesters.slice(start, end));
        }
    }, [filters]);


    const prevPage = () => {
        if((page - 1) <= 0) return;

        setPage(page-1);
    }

    const nextPage = () => {
        if((page + 1) > Math.ceil(semesters.length/10)) return;

        setPage(page+1);
    }

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
          <section className="h-fit mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
            <div className="py-4 flex w-full gap-6 justify-between flex-wrap">
                <div>
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
                            data.length > 0 ?
                            data.map(semester => (
                                <tr className="divide-x-2 divide-gray-300" key={semester.id}>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">{semester.title}</td>
                                    <td className="px-2 min-w-36">{semester.year}</td>
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
                <div className="py-4 text-center flex justify-between items-center">
                    <p className="font-light text-gray-500 italic">Showing page {page} of {Math.ceil(semesters.length/10)}</p>
                    <div className="flex gap-4">
                        <PrimaryButton onClick={prevPage}>
                            <FaAnglesLeft />
                        </PrimaryButton>
                        {
                            (() => {
                                return Array.from(
                                { length: Math.ceil(semesters.length/10) },
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
