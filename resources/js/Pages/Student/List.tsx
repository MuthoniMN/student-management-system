import { useState, useEffect } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";
import TextInput from "@/Components/TextInput";
import { FaAnglesLeft, FaAnglesRight } from "react-icons/fa6";

type TFilter = {
    type: string,
    value: string
}

export type TStudent = {
    id: number,
    name: string,
    studentId: string,
    parent_id: number,
    grade_id: number,
    address: string,
    email: string,
    phone_number: string,
    grade: string,
    created_at: Date,
    updated_at: Date,
    parent_name?: string
}

export default function List({ students, parents, grades }: { students: TStudent[], parents: any, grades: any}){
    const [filters, setFilters] = useState<TFilter>({
        type: '',
        value: ''
    });
    // pagination
    const perPage = 10;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(students.slice(start, end));

    useEffect(() => {
        console.log(start,end)
        setData(students.slice(start,end));
    }, [page])

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;

        setFilters(vals => ({
            ...vals,
            [key]: value
        }))
    }

    useEffect(() => {
        if(filters.type && filters.value){
            filters.type == "grade" ? setData(students.filter(student => student.grade_id == +filters.value).slice(start, end)) :
            filters.type == 'parent' ? setData(students.filter(student => student.parent_id == +filters.value).slice(start, end)) : setData(students.slice(start, end));
        }else{
            setData(students.slice(start, end));
        }

    }, [filters]);

    const prevPage = () => {
        if((page - 1) <= 0) return;

        setPage(page-1);
    }

    const nextPage = () => {
        if((page + 1) > pages.length) return;

        setPage(page+1);
    }

    const handleSearch = (e) => {
        const value = e.target.value;
        const results = students.filter(student => student.name.includes(value));

        setData(results.slice(start, end));
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="w-full flex justify-between items-center">
                    <h2 className="text-xl font-bold">All Students</h2>
                    <PrimaryButton>
                        <Link href={route('students.create')}>
                            Create Student
                        </Link>
                    </PrimaryButton>
                </div>
            }
        >
            <Head title="Student List" />
            <section className="w-[95vw] h-fit mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
            <div className="py-4 flex w-full gap-6 justify-between flex-wrap">
                <div className="w-full max-w-[480px]">
                    <TextInput name="query" id="query" onChange={handleSearch} placeholder="Search student by name" className="w-full" />
                </div>
                <div>
                    <form className="flex gap-2 items-center min-w-320px">
                        <p>Filter by: </p>
                        <select name="type" value={filters.type} onChange={handleChange}>
                            <option value="">--</option>
                            <option value="grade">Grade</option>
                            <option value="parent">Parent</option>
                        </select>
                        <select name="value" value={filters.value} onChange={handleChange}>
                            {
                        filters.type === 'grade' ?
                            grades.map(grade => (
                                <option key={grade.id} value={grade.id}>{grade.name}</option>
                        )) : filters.type === 'parent' ?
                            parents.map(parent => (
                                <option key={parent.id} value={parent.id}>{parent.name}</option>
                        )) : (<option value="">--</option>)
                            }
                        </select>
                    </form>
                </div>
            </div>
                <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                    <thead>
                        <tr className="divide-x-2 divide-gray-300 text-left">
                            <th></th>
                            <th className="px-2">Student ID</th>
                            <th className="px-2">Name</th>
                            <th className="px-2">Grade</th>
                            <th className="px-2">Email</th>
                            <th className="px-2">Phone Number</th>
                            <th className="px-2">Address</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y-2 divide-gray-300">
                        {
                            data.map(student => (
                                <tr className="divide-x-2 divide-gray-300" key={student.id}>
                                    <td className="p-2">
                                        <input type="checkbox"/>
                                    </td>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out"><Link href={route('students.show', student.id)}>{student.studentId}</Link></td>
                                    <td className="px-2 min-w-36">{student.name}</td>
                                    <td className="px-2 min-w-24">{student.grade}</td>
                                    <td className="px-2 min-w-36">{student.email}</td>
                                    <td className="px-2 min-w-24">{student.phone_number}</td>
                                    <td className="px-2 min-w-24">{student.address}</td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
                <div className="py-4 text-center flex justify-between items-center">
                    <p className="font-light text-gray-500 italic">Showing page {page} of {Math.ceil(students.length/10)}</p>
                    <div className="flex gap-4">
                        <PrimaryButton onClick={prevPage}>
                            <FaAnglesLeft />
                        </PrimaryButton>
                        {
                            (() => {
                                return Array.from(
                                { length: Math.ceil(students.length/10) },
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
                <div className="flex gap-6">
                    <PrimaryButton>Update</PrimaryButton>
                    <DangerButton>Delete</DangerButton>
                </div>
            </section>
        </AuthenticatedLayout>
    );
}
