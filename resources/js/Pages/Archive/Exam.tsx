import { useState, useEffect } from "react";
import { FaDownload } from "react-icons/fa6";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, router } from "@inertiajs/react";
import { TFilter, TExam, TGrade, TSemester } from "@/types/";
import Pagination from "@/Components/Pagination";
import DangerButton from "@/Components/DangerButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { LuArchiveRestore } from "react-icons/lu";
import { FaAngleLeft } from "react-icons/fa6";

export default function ExamArchive({ exams, grades, semesters }: {
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

    const { submit } = useForm();
    const handleSubmit = (e, exam: TExam) => {
        e.preventDefault();
        router.put(route('subjects.exams.restore', exam.subject_id), {
            id: exam.id
        });
    };

    return  (
        <AuthenticatedLayout
            header={
                <div className="w-full flex gap-4 items-center">
                    <Link href={route('archive')}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="font-bold text-xl">Archived Exams</h2>
                </div>
            }
        >
            <Head title="Archived Exams" />
            <section className="bg-white w-fit md:w-full mt-4 py-4 px-2 rounded-lg space-y-4">
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
                            <th className="px-2">Subject</th>
                            <th className="px-2">Semester</th>
                            <th className="px-2">Document</th>
                            <th className="w-fit"></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y-2 divide-gray-300">
                        {
                            paginatedData.length > 0 ? paginatedData.map(exam => (
                                <tr className="divide-x-2 divide-gray-300" key={exam.id}>
                                    <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">{exam.title}</td>
                                    <td className="px-2 min-w-24">{exam.grade}</td>
                                    <td className="px-2 min-w-24">{exam.subject}</td>
                                    <td className="px-2 min-w-36">{exam.semester} ({exam.year})</td>
                                    <td className="px-2 min-w-36">{exam.file ? (<Link href={route('files',exam.file as string)} className="rounded-full flex gap-2 items-center bg-gray-200 px-4 w-fit">File <FaDownload /> </Link>): "No uploaded file"}</td>
                                    <td className="px-2 w-fit">
                                        <DangerButton onClick={(e) => handleSubmit(e, exam)}>
                                            <LuArchiveRestore className="text-lg" />
                                        </DangerButton>
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
            </section>
        </AuthenticatedLayout>
    );
}
