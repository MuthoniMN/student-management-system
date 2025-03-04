import { useState, useEffect } from "react";
import { TGrade, TResultSummary, TYear } from "@/types/";
import Pagination from "@/Components/Pagination";
import ResultsSummaryRow from "@/Components/ResultsSummaryRow";

export default function ResultsTable({ exam_results, grades, years, perPage=5 }:
    {
        exam_results: TResultSummary[],
        grades?: TGrade[],
        years?: TYear[],
        perPage?: number,
    }){
    const [type, setType] = useState('');
    const [grade, setGrade] = useState('');
    const [year, setYear] = useState('2025');
    const [semester, setSemester] = useState('');

    // pagination
    const [page, setPage] = useState(1);
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [data, setData] = useState<TResultSummary[]>([]);
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));

    useEffect(() => {
      setPaginatedData(data.slice(start, end))
    }, [data, page])


    // filtering data
    useEffect(() => {
        let filtered = exam_results.filter(res =>  res.years.hasOwnProperty(year));

        if(grade.length > 0) {
            filtered = filtered.filter(res => res.years[year].hasOwnProperty(grade));
        }

        if(grade.length > 0 && semester.length > 0) {
            filtered = filtered.filter(res => res.years[year][grade].hasOwnProperty(semester));
        }

        if(grade.length > 0) setData(filtered.sort(function (a, b) {
            if(semester.length > 0 && type.length > 0){
                return b.years[year][grade][semester][type].total - a.years[year][grade][semester][type].total
            }
            if(semester.length > 0){
                return b.years[year][grade][semester].total - a.years[year][grade][semester].total
            }

            return b.years[year][grade].total - a.years[year][grade].total
        }));

    }, [year, semester, grade]);

    return (
        <section className="space-y-4">
            <div className="flex w-full justify-end">
                <form className="flex gap-2 items-center w-full justify-between min-w-320px space-x-2">
                    <p>Filter by: </p>
                    <div className="space-x-2">
                        <label htmlFor="grade">Grade</label>
                        <select onChange={(e) => setGrade(e.target.value)}>
                            <option value="">--</option>
                            {grades && grades.map(grade => (
                                <option key={grade.id} value={grade.name}>{grade.name}</option>
                        ))}
                        </select>
                    </div>
                    <div className="space-x-2">

                        <label htmlFor="year">Academic Year: </label>

                        <select id="year" value={year} onChange={(e) => setYear(e.target.value)}>
                            <option value="">--</option>
                            {years && years.map(year => (
                                <option key={year.id} value={year.year}>{year.year}</option>
                        ))}
                        </select>
                    </div>
                    <div className="space-x-2">
                        <label htmlFor="semester">Semester: </label>
                        <select id="semester" value={semester} onChange={(e) => setSemester(e.target.value)}>
                            <option value="">--</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                        </select>
                    </div>
                    <div className="space-x-2">
                        <label htmlFor="type">Type</label>
                        <select onChange={(e) => {
                            if(year && semester) setType(e.target.value)
                        }}>
                            <option value="">--</option>
                            <option value="Final assessment">Final Assessment</option>
                            <option value="CAT 1">CAT 1</option>
                            <option value="CAT 2">CAT 2</option>
                        </select>
                    </div>

                </form>
            </div>
            <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                <thead>
                    <tr className="divide-x-2 divide-gray-300 text-left">
                        <th className="px-2">Rank</th>
                        <th className="px-2">Student</th>
                        <th className="px-2">Grade</th>
                        <th className="px-2">Academic Year</th>
                        <th className="px-2">Semester</th>
                        <th className="px-2">Mathematics</th>
                        <th className="px-2">English</th>
                        <th className="px-2">Kiswahili</th>
                        <th className="px-2">Science</th>
                        <th className="px-2">History</th>
                        <th className="px-2">Geography</th>
                        <th className="px-2">Computer</th>
                        <th className="px-2">CRE</th>
                        <th className="px-2">Total</th>
                    </tr>
                </thead>
                <tbody className="divide-y-2 divide-gray-300">
                    {
                        (year.length > 0 && grade.length > 0 && data.length > 0) ? paginatedData.map((result, index) => {
                            return result.years[year] && result.years[year][grade] && (
                            <ResultsSummaryRow index={index} year={year} grade={grade} semester={semester} type={type} result={result} />
                        )}) :
                        <tr className="py-2">
                            <td className="text-center" colSpan={15}>
                                {(type.length <= 0 || grade.length <= 0 || year.length <= 0 || semester.length <= 0) ? "Filter to view results" : 'No Results to Display'}
                            </td>
                        </tr>
                    }
                </tbody>
            </table>
            <Pagination page={page} setPage={setPage} perPage={perPage} length={data.length} />
    </section>
    );
}
