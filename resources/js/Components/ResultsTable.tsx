import { useEffect, useState } from "react";
import { TGrade } from "@/Pages/Grade/List";
import { TStudent } from "@/Pages/Student/List";
import { TResult, getGrade } from "@/Components/ResultForm";
import { TSemester } from "@/Components/SemesterForm";
import { TYear } from "@/Components/YearForm";
import { TFilter } from "@/Pages/Student/List";
import Pagination from "@/Components/Pagination";
import { TSubject } from "@/Components/SubjectForm";

export default function ResultsTable({ results, grades, semesters, students, years, subjects, perPage=5 }:
    {
        results: TResult[],
        grades?: TGrade[],
        semesters?: TSemester[],
        students?: TStudent[],
        subjects?: TSubject[],
        years?: TYear[],
        perPage?: number
    }){
        console.log(subjects);
    const [filters, setFilters] = useState<TFilter>({
        type: '',
        value: ''
    });
    // pagination
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState(results.slice(start, end));

    useEffect(() => {
        setData(results.slice(start,end));
    }, [page]);

    useEffect(() => {
        if(filters.type && filters.value){
            console.log(filters);
            filters.type == "student" ? setData(results.filter(result => result.student_id == +filters.value).slice(start, end)) :
            filters.type == "grade" ? setData(results.filter(result => result.grade_id == +filters.value).slice(start, end)) :
            filters.type == 'semester' ? setData(results.filter(result => result.semester_id == +filters.value).slice(start, end)) :
            filters.type == 'year' ? setData(results.filter(result => result.year == filters.value).slice(start, end)) :
            filters.type == 'subject' ? setData(results.filter(result => result.subject_id == +filters.value).slice(start, end)) : setData(results.slice(start, end));
        }else{
            setData(results.slice(start, end));
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


    return (
        <section className="space-y-4">
            <div className="flex w-full justify-end">
                <form className="flex gap-2 items-center min-w-320px">
                    <p>Filter by: </p>
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
                        grades && grades.map(grade => (
                            <option key={grade.id} value={grade.id}>{grade.name}</option>
                    )) : filters.type === 'semester' ?
                        semesters && semesters.map(semester => (
                            <option key={semester.id} value={semester.id}>{semester.title} - {semester.year}</option>
                    )) : filters.type === 'student' ?
                        students && students.map(student => (
                            <option key={student.id} value={student.id}>{student.name}</option>
                    )) : filters.type === 'subject' ?
                        subjects && subjects.map(subject => (
                            <option key={subject.id} value={subject.id}>{subject.title}</option>
                    )) : filters.type === 'year' ?
                        years && years.map(year => (
                            <option key={year.id} value={year.year}>{year.year}</option>
                    )) : (<option value="">--</option>)
                        }
                    </select>
                </form>
            </div>
            <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                <thead>
                    <tr className="divide-x-2 divide-gray-300 text-left">
                        {students && <th className="px-2">Student</th>}
                        {grades && <th className="px-2">Class</th>}
                        {semesters && <th className="px-2">Semester</th>}
                        {subjects && <th className="px-2">Subject</th>}
                        <th className="px-2">Score</th>
                        <th className="px-2">Grade</th>
                        <th className="w-fit"></th>
                    </tr>
                </thead>
                <tbody className="divide-y-2 divide-gray-300">
                    {
                        data.length > 0 ? data.map(result => (
                            <tr className="divide-x-2 divide-gray-300" key={result.id}>
                            {students &&<td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">{result.student}</td>}
                            {grades && <td className="px-2 min-w-36">{result.class_grade}</td>}
                            {semesters && <td className="px-2 min-w-36">{result.semester} ({result.year})</td>}
                            {subjects && <td className="px-2 min-w-36">{result.subject}</td>}
                                <td className="px-2 min-w-36">{result.result}</td>
                                <td className="px-2 min-w-36">{result.grade}</td>
                            </tr>
                        )) :
                        <tr className="py-2">
                            <td className="text-center" colSpan={4}>No Results to Display</td>
                        </tr>
                    }
                </tbody>
            </table>
            <div className="italic flex w-full justify-between px-2">
                <p>Average: {data.length > 0 ? Math.floor(data.map(res => res.result).reduce((acc, curr) => acc + curr) / data.length) : 0}</p>
                <p>Average Grade: {getGrade(data.map(res => res.result).reduce((acc, curr) => acc + curr, 0) / data.length)}</p>
                <p>Last Mark: {data.length > 0 ? Math.min(...data.map(res => res.result)) : 0}</p>
                <p>Top Mark: {data.length > 0 ? Math.max(...data.map(res => res.result)) : 0}</p>
                {students && (<p>Total Students: {results.length}</p>)}
            </div>
            <Pagination page={page} setPage={setPage} perPage={perPage} length={results.length} />
    </section>
    );
}
