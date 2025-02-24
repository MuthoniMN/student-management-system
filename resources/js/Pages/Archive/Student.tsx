import { useState, useEffect, ChangeEvent, FormEvent } from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";
import DangerButton from "@/Components/DangerButton";
import TextInput from "@/Components/TextInput";
import Pagination from "@/Components/Pagination";
import { TStudent, TFilter, TParent, TGrade } from "@/types/";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";

export default function List({ students, parents, grades }: { students: TStudent[], parents: TParent[], grades: TGrade[]}){
    console.log(students);
    const [filters, setFilters] = useState<TFilter>({
        type: '',
        value: ''
    });
    const [selected, setSelected] = useState<number[]>([]);
    // pagination
    const perPage = 10;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(students);
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));

    const handleDelete = (e: FormEvent) => {
        e.preventDefault();
        router.put(route('students.restore'), {
            data: { 'studentIds': selected }
        })
    }

    useEffect(() => {
        setPaginatedData(students.slice(start,end));
    }, [page, data])

    const handleChange = (e: ChangeEvent<HTMLSelectElement>) => {
        const key = e.target.name;
        const value = e.target.value;

        setFilters(vals => ({
            ...vals,
            [key]: value
        }))
    }

    const handleSelect = (i: number) => {
        if(selected && selected.includes(i)){
            setSelected(selected.filter(val => val !== i));
        }else {
            setSelected([...selected, i])
        }
    }

    const handleSelectAll = () => {
        if(selected.length === data.length){
            setSelected([]);
            return;
        }
        data.map(val => {
            setSelected(selected => [...selected, val.id]);
        });
    }

    useEffect(() => {
        if(filters.type && filters.value){
            filters.type == "grade" ? setData(students.filter(student => (student.grade as TGrade).id == +filters.value)) :
            filters.type == 'parent' ? setData(students.filter(student => student.parent.id == +filters.value)) : setData(students);
        }else{
            setData(students);
        }

    }, [filters]);


    const handleSearch = (e: ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        const results = students.filter(student => student.name.includes(value));

        setData(results);
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="w-full flex gap-4 items-center">
                    <Link href={route('archive')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Students Archive</h2>
                </div>
            }
        >
            <Head title="Student Archive" />
            <section className="h-fit w-fit md:w-full mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
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
                            <th className="p-2">
                                <input type="checkbox" checked={selected.length === data.length && selected.length > 0} onChange={() => handleSelectAll()} />
                            </th>
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
                            paginatedData.map(student => (
                                <tr className="divide-x-2 divide-gray-300" key={student.id}>
                                    <td className="p-2">
                                        <input type="checkbox" checked={(selected && (selected as number[]).includes(student.id)) || false} onChange={() => handleSelect(student.id)} />
                                    </td>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out"><Link href={route('students.show', student.id)}>{student.studentId}</Link></td>
                                    <td className="px-2 min-w-36">{student.name}</td>
                                    <td className="px-2 min-w-24">{(student.grade as TGrade).name}</td>
                                    <td className="px-2 min-w-36">{student.parent.email}</td>
                                    <td className="px-2 min-w-24">{student.parent.phone_number}</td>
                                    <td className="px-2 min-w-24">{student.parent.address}</td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
                <Pagination length={data.length} perPage={perPage} page={page} setPage={setPage} />
                { selected.length >= 1 &&
                <div className="flex gap-6">
                    <DangerButton onClick={handleDelete}>Restore Student</DangerButton>
                </div> }
        </section>
    </AuthenticatedLayout>
);
}
