import { TParent, TStudent, TGrade, TFlash } from "../../types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link,usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { FaPenToSquare } from "react-icons/fa6";
import { useState, useEffect } from "react";
import Pagination from "@/Components/Pagination";

const ParentDashboard = ( { parent, children }: { parent: TParent, children: TStudent[] } ) => {
    console.log(parent);
    const perPage = 10;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(children);
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));
    const flash = usePage().props.flash as TFlash;

    useEffect(() => {
        setPaginatedData(data.slice(start,end));
    }, [page, data])
    return (
        <AuthenticatedLayout
            header={
                <div className="w-full flex justify-between items-center">
                    <h2 className="text-xl font-bold">Welcome {parent.name}</h2>
                    <PrimaryButton>
                        <Link href={route('students.create')} className="flex gap-2">
                            <span>Edit Contact Information</span> <FaPenToSquare />
                        </Link>
                    </PrimaryButton>
                </div>
            }
        >
            <Head title="Parent Dashboard" />
            <h2 className="mx-8 my-4 text-lg font-bold">Your Children</h2>
            <section className="h-fit w-fit md:w-full mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
                <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                    <thead>
                        <tr className="divide-x-2 divide-gray-300 text-left">
                            <th className="px-2">Student ID</th>
                            <th className="px-2">Name</th>
                            <th className="px-2">Grade</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y-2 divide-gray-300">
                        {
                           paginatedData.length > 0 ? paginatedData.map(student => (
                                <tr className="divide-x-2 divide-gray-300" key={student.id}>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out"><Link href={route('child', student.id)}>{student.studentId}</Link></td>
                                    <td className="px-2 min-w-36">{student.name}</td>
                                    <td className="px-2 min-w-24">{(student.grade as TGrade).name}</td>
                                </tr>
                            )) :
                                <tr className="py-2 text-center">
                                    <td colSpan={7}>No available students!</td>
                            </tr>
                        }
                    </tbody>
                </table>
                <Pagination length={data.length} perPage={perPage} page={page} setPage={setPage} />
            { flash && flash.update && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.update}</p>
                </div>
            )}
        </section>
    </AuthenticatedLayout>
    );
}

export default ParentDashboard;
