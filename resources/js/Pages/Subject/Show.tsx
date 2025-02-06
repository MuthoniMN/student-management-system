import { useState, useEffect } from "react";
import { FaPen, FaDownload } from "react-icons/fa6";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import { TSubject } from "@/Components/SubjectForm";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { TFilter } from "@/Pages/Student/List";
import { TExam } from "@/Components/ExamForm";
import { TGrade } from "@/Pages/Grade/List";
import { TSemester } from "@/Components/SemesterForm";
import Pagination from "@/Components/Pagination";

export default function SubjectShow({ subject, exams, grades, semesters }: {
    subject: TSubject,
    exams: TExam[],
    grades: TGrade[],
    semesters: TSemester[]
}){
    const [filters, setFilters] = useState<TFilter>({
        type: '',
        value: ''
    });
    // pagination
    const perPage = 8;
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(exams);
    const [paginatedData, setPaginatedData] = useState(exams.slice(start, end));
    const flash = usePage().props.flash;

    useEffect(() => {
        setPaginatedData(data.slice(start,end));
    }, [page, data]);

    useEffect(() => {
        if(filters.type && filters.value){
            filters.type == "grade" ? setData(exams.filter(exam => exam.grade_id == +filters.value)) :
            filters.type == 'semester' ? setData(exams.filter(exam => exam.semester_id == +filters.value)) :
            setData(exams);
        }else{
            setData(exams);
        }

    }, [filters]);

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;

        setFilters(vals => ({
            ...vals,
            [key]: value
        }))
    }

    return  (
        <AuthenticatedLayout
            header={
                <>
                    <h2 className="font-bold text-xl mb-4">{subject.title}</h2>
                    <p className="italic">{subject.description}</p>
                </>
            }
        >
            <Head title={subject.title} />
            <section className="bg-white mt-4 py-4 px-2 rounded-lg space-y-4">
                <div className="flex justify-between">
                    <h3 className="text-xl font-bold">Exams</h3>
                    <PrimaryButton>
                        <Link href={route('subjects.exams.create', subject)}>Create an Assessment</Link>
                    </PrimaryButton>
                </div>
                <div>
                    <form className="flex gap-2 items-center min-w-320px">
                        <p>Filter by: </p>
                        <select name="type" value={filters.type} onChange={handleChange}>
                            <option value="">--</option>
                            <option value="grade">Grade</option>
                            <option value="semester">Semester</option>
                        </select>
                        <select name="value" value={filters.value} onChange={handleChange}>
                            <option value="">---</option>
                            {
                        filters.type === 'grade' ?
                            grades.map(grade => (
                                <option key={grade.id} value={grade.id}>{grade.name}</option>
                        )) : filters.type === 'semester' ?
                            semesters.map(semester => (
                                <option key={semester.id} value={semester.id}>{semester.title} - {semester.year}</option>
                        )) : (<option value="">--</option>)
                            }
                        </select>
                    </form>
                </div>
                <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                    <thead>
                        <tr className="divide-x-2 divide-gray-300 text-left">
                            <th className="px-2">Title</th>
                            <th className="px-2">Grade</th>
                            <th className="px-2">Semester</th>
                            <th className="px-2">Document</th>
                            <th className="w-fit"></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y-2 divide-gray-300">
                        {
                            paginatedData.length > 0 ? paginatedData.map(exam => (
                                <tr className="divide-x-2 divide-gray-300" key={exam.id}>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">
                                        <Link href={route('subjects.exams.show', [subject.id, exam.id])}>{exam.title}</Link>
                                    </td>
                                    <td className="px-2 min-w-24">{exam.grade}</td>
                                    <td className="px-2 min-w-36">{exam.semester} ({exam.year})</td>
                                    <td className="px-2 min-w-36">{exam.file ? (<Link href={route('files',exam.file as string)} className="rounded-full flex gap-2 items-center bg-gray-200 px-4 w-fit">File <FaDownload /> </Link>): "No uploaded file"}</td>
                                    <td className="px-2 w-fit">
                                        <SecondaryButton className="w-fit">
                                            <Link href={route('subjects.exams.edit', [subject.id, exam.id])}><FaPen /></Link>
                                        </SecondaryButton>
                                    </td>
                                </tr>
                            )) :
                            <tr className="py-2">
                                <td className="text-center" colSpan={4}>No Exams to Display</td>
                            </tr>
                        }
                    </tbody>
                </table>
                <Pagination length={Math.ceil(data.length/perPage)} perPage={perPage} page={page} setPage={setPage} />
            { flash && (flash.create || flash.update) && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.create || flash.update}</p>
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
