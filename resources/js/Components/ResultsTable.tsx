import { useEffect, useState, FormEvent, ChangeEvent } from "react";
import { TGrade, TStudent, TResult, TSemester, TYear, TFilter, TSubject} from "@/types/";
import Pagination from "@/Components/Pagination";
import { Link, useForm, router } from "@inertiajs/react";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";
import { FaTrash, FaPenToSquare } from "react-icons/fa6";
import { LuArchiveRestore } from "react-icons/lu";
import { getGrade } from "@/Components/ResultForm";

type FilterFunction = (res: TResult) => boolean

export default function ResultsTable({ results, grades, semesters, students, years, subjects, perPage=5, archive=false }:
    {
        results: TResult[],
        grades?: TGrade[],
        semesters?: TSemester[],
        students?: TStudent[],
        subjects?: TSubject[],
        years?: TYear[],
        perPage?: number,
        archive?: boolean
    }){

    const [filters, setFilters] = useState<TFilter>({
        type: '',
        value: ''
    });

    const [type, setType] = useState('');
    const [grade, setGrade] = useState('');
    const [subject, setSubject] = useState('');

    // pagination
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(results);
    const [paginatedData, setPaginatedData] = useState(results.slice(start, end));

    useEffect(() => {
        setPaginatedData(data.slice(start,end));
    }, [page, data]);

    const filterResults = (results: TResult[], filters: TFilter, type: string, grade: string, subject: string) => {
        const { type: filterType, value: filterValue } = filters;

        // Base filter conditions
        const baseFilters = [] as FilterFunction[];
        if (type) baseFilters.push(result => result.exam?.type === type);
        if (grade) baseFilters.push(result => result.grade === grade);
        if (subject) baseFilters.push(result => result.exam?.subject.id === +subject);

        // Additional filter conditions based on filterType
        if (filterType && filterValue) {
            switch (filterType) {
                case 'student':
                    baseFilters.push(result => result.student?.id === +filterValue);
                    break;
                case 'grade':
                    baseFilters.push(result => result.exam?.grade.id === +filterValue);
                    break;
                case 'semester':
                    baseFilters.push(result => result.exam?.semester.id === +filterValue);
                    break;
                case 'year':
                    baseFilters.push(result => `${(result.exam?.semester.year as TYear).id}` == filterValue);
                    break;
                case 'subject':
                    baseFilters.push(result => result.exam?.subject.id === +filterValue);
                    break;
                default:
                    break;
            }
        }

        // Apply all filters
        const filteredResults = results.filter(result => baseFilters.every(filter => filter(result)));

        // Update the data
        setData(filteredResults);
    };

    // filtering data
    useEffect(() => {
        filterResults(results, filters, type, grade, subject);
    }, [filters, type, grade, subject]);

    const handleChange = (e: ChangeEvent<HTMLSelectElement>) => {
        const key = e.target.name;
        const value = e.target.value;

        setFilters(vals => ({
            ...vals,
            [key]: value
        }))
    }

    const { submit, delete: destroy } = useForm();
    const handleSubmit = (e: FormEvent, result: TResult) => {
        e.preventDefault();
        submit('delete', route('subjects.exams.results.destroy', [result.subject_id, result.exam?.id, result.id]));
    };

    const handleRestore = (e: FormEvent, result: TResult) => {
        e.preventDefault();
        router.put(route('subjects.exams.results.restore',[result.subject_id, result.exam?.id]), {
            id: result.id
        });
    };

    return (
        <section className="space-y-4">
            <div className="flex w-full justify-end">
                <form className="flex gap-2 items-center w-full justify-between min-w-320px space-x-2">
                    <p>Filter by: </p>
                    <div className="space-x-2">
                        <label htmlFor="type">Type</label>
                        <select onChange={(e) => setType(e.target.value)}>
                            <option value="">--</option>
                            <option value="exam">Exam</option>
                            <option value="CAT">CAT</option>
                        </select>
                    </div>
                    <div className="space-x-2">
                        <label htmlFor="subject">Subject</label>
                        <select onChange={(e) => setSubject(e.target.value)}>
                            <option value="">--</option>
                            {subjects && subjects.map(subject => (
                                <option key={subject.id} value={subject.id}>{subject.title}</option>
                        ))}
                        </select>
                    </div>
                    <div className="space-x-2">
                        <label htmlFor="grade">Grade</label>
                        <select onChange={(e) => setGrade(e.target.value)}>
                            <option value="">--</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                    <div className="space-x-2">
                        <select name="type" value={filters.type} onChange={handleChange}>
                            <option value="">--</option>
                            {grades && <option value="grade">Grade</option>}
                            {semesters && <option value="semester">Semester</option>}
                            {students &&(<option value="student">Student</option>)}
                            {subjects &&(<option value="subject">Subject</option>)}
                            {years &&(<option value="year">Year</option>)}
                        </select>
                        <select name="value" value={filters.value} onChange={handleChange}>
                            {
                        filters.type === 'grade' ?
                            <>
                        <option value="">--</option>
                        {grades && grades.map(grade => (
                                <option key={grade.id} value={grade.id}>{grade.name}</option>
                        ))}</> : filters.type === 'semester' ?
                        <>
                            <option value="">--</option>
                            {semesters && semesters.map(semester => (
                                    <option key={semester.id} value={semester.id}>{semester.title} - {(semester.year as TYear).year}</option>
                    ))}</> : filters.type === 'student' ?
                        <>
                        <option value="">--</option>
                            {students && students.map(student => (
                                <option key={student.id} value={student.id}>{student.name}</option>
                        ))}</> : filters.type === 'subject' ?
                            <>
                        <option value="">--</option>
                            {subjects && subjects.map(subject => (
                                <option key={subject.id} value={subject.id}>{subject.title}</option>
                        ))}</> : filters.type === 'year' ?
                            <>
                        <option value="">--</option>
                            {years && years.map(year => (
                                <option key={year.id} value={year.year}>{year.year}</option>
                        ))}</> : (<option value="">--</option>)
                            }
                        </select>
                    </div>
                </form>
            </div>
            <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                <thead>
                    <tr className="divide-x-2 divide-gray-300 text-left">
                        {students && <th className="px-2">Student</th>}
                        {grades && <th className="px-2">Class</th>}
                        {semesters && <th className="px-2">Semester</th>}
                        {subjects && <th className="px-2">Subject</th>}
                        <th className="px-2">Exam</th>
                        <th className="px-2">Score</th>
                        <th className="px-2">Grade</th>
                        <th className="w-fit"></th>
                    </tr>
                </thead>
                <tbody className="divide-y-2 divide-gray-300">
                    {
                        paginatedData.length > 0 ? paginatedData.map(result => (
                            <tr className="divide-x-2 divide-gray-300" key={result.id}>
                            {students &&<td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">{result.student?.name}</td>}
                            {grades && <td className="px-2 min-w-36">{result.exam?.grade.name}</td>}
                            {semesters && <td className="px-2 min-w-36">{result.exam?.semester.title} ({(result.exam?.semester?.year as TYear).year})</td>}
                            {subjects && <td className="px-2 min-w-36">{result.exam?.subject.title}</td>}
                                <td className="px-2 min-w-36">{result.exam?.title}</td>
                                <td className="px-2 min-w-36">{result.result}</td>
                                <td className="px-2 min-w-36">{result.grade}</td>
                                { !archive ?
                                (
                                    <>
                                    <td className="px-2 text-center w-fit">
                                    <Link href={route('subjects.exams.results.edit', [result.exam?.subject.id, result.exam?.id, result.id])}>
                                        <SecondaryButton>
                                            <FaPenToSquare />
                                        </SecondaryButton>
                                    </Link>
                                </td>
                                <td className="px-2 text-center w-fit">
                                    <DangerButton onClick={(e) => handleSubmit(e, result)}>
                                        <FaTrash />
                                    </DangerButton>
                                </td>
                                </>
                                ) :
                                (<td className="p-2 text-center w-fit">
                                    <DangerButton onClick={(e) => handleRestore(e, result)}>
                                        <LuArchiveRestore className="text-lg" />
                                    </DangerButton>
                                </td>)
                            }
                            </tr>
                        )) :
                        <tr className="py-2">
                            <td className="text-center" colSpan={4}>No Results to Display</td>
                        </tr>
                    }
                </tbody>
            </table>
            <div className="px-2 space-y-2">
                <h3 className="text-lg font-bold">Exam Results: </h3>
                <div className="italic flex w-full justify-between">
                {!students && (<p>Total Marks: {data.filter(res => res.exam?.type == 'exam').length > 0 ? Math.floor(data.filter(res => res.exam?.type == 'exam').map(res => res.result).reduce((acc, curr) => acc + curr)) : 0}</p>)}
                    <p>Average: {data.filter(res => res.exam?.type == 'exam').length > 0 ? Math.floor(data.filter(res => res.exam?.type == 'exam').map(res => res.result).reduce((acc, curr) => acc + curr) / data.filter(res => res.exam?.type == 'exam').length) : 0}</p>
                    <p>Average Grade: {getGrade(data.filter(res => res.exam?.type == 'exam').map(res => res.result).reduce((acc, curr) => acc + curr, 0) / data.filter(res => res.exam?.type == 'exam').length)}</p>
                    <p>Last Mark: {data.filter(res => res.exam?.type == 'exam').length > 0 ? Math.min(...data.filter(res => res.exam?.type == 'exam').map(res => res.result)) : 0}</p>
                    <p>Top Mark: {data.filter(res => res.exam?.type == 'exam').length > 0 ? Math.max(...data.filter(res => res.exam?.type == 'exam').map(res => res.result)) : 0}</p>
                    {students && (<p>Total Students: {students.length}</p>)}
                </div>
            </div>
            <div className="px-2 space-y-2">
                <h3 className="text-lg font-bold">CAT Results: </h3>
                <div className="italic flex w-full justify-between">
                    <p>Average: {data.filter(res => res.exam?.type == 'CAT').length > 0 ? Math.floor(data.filter(res => res.exam?.type == 'CAT').map(res => res.result).reduce((acc, curr) => acc + curr) / data.filter(res => res.exam?.type == 'CAT').length) : 0}</p>
                    <p>Average Grade: {getGrade(data.filter(res => res.exam?.type == 'CAT').map(res => res.result).reduce((acc, curr) => acc + curr, 0) / data.filter(res => res.exam?.type == 'CAT').length)}</p>
                    <p>Last Mark: {data.filter(res => res.exam?.type == 'CAT').length > 0 ? Math.min(...data.filter(res => res.exam?.type == 'CAT').map(res => res.result)) : 0}</p>
                    <p>Top Mark: {data.filter(res => res.exam?.type == 'CAT').length > 0 ? Math.max(...data.filter(res => res.exam?.type == 'CAT').map(res => res.result)) : 0}</p>
                    {students && (<p>Total Students: {students.length}</p>)}
                </div>
            </div>

            <Pagination page={page} setPage={setPage} perPage={perPage} length={data.length} />
    </section>
    );
}
