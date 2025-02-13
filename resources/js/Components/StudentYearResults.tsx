import { TYearResult, TStudent, TSubResult, TRank, TYearSummary } from "@/types/";

export default function StudentYearResults({ results, student, ranks, yearResults }: { results: TYearResult , student: TStudent, ranks: TRank, yearResults: TYearSummary }){
    const sem1_total = Object.keys(results).reduce((acc, curr) => {
        const val = results[curr];

        acc += Number(val['Semester 1']);

        return acc;
    }, 0);
    const sem2_total = Object.keys(results).reduce((acc, curr) => {
        const val = results[curr];

        acc += Number(val['Semester 2']);

        return acc;
    }, 0);
    const avg_total = Object.keys(results).reduce((acc, curr) => {
        const val = results[curr];

        acc += Number(val.average);

        return acc;
    }, 0);
    return (
        <section className="p-4 space-y-6 max-w-[750px] mx-auto">
            <div className="flex w-full justify-between items-center">
                <h2 className="text-xl font-bold">Student Name: {student.name}</h2>
                <p>Student ID: {student.studentId}</p>
            </div>
            <div className="space-y-4">
                <h3 className="text-lg font-bold">Subject Performance</h3>
                <table className="flex flex-col border-[2px] divide-y-[2px] divide-gray-500 border-gray-700 w-full">
                <thead className="divide-y-[2px] divide-gray-500">
                    <tr className="divide-x-[2px] divide-gray-500 flex w-full">
                        <th className="font-bold px-4 py-2 w-1/3"></th>
                        <th className="font-bold px-4 py-2 w-[155px]" colSpan={2}>Semester 1</th>
                        <th className="font-bold px-4 py-2 w-[155px]" colSpan={2}>Semester 2</th>
                        <th className="font-bold px-4 py-2 w-[155px]" colSpan={2}>Average</th>
                    </tr>
                    <tr className="divide-x-[2px] divide-gray-500 flex w-full">
                        <th className="font-bold px-4 py-2 w-1/3">Subject</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Marks</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Grade</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Marks</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Grade</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Marks</th>
                        <th className="font-bold px-4 py-2 w-[79.5px]">Grade</th>
                        </tr>
                    </thead>
                <tbody className="divide-y-[2px] divide-gray-500 w-full">
                {
                    Object.keys(results).map(key => {
                        const subj = results[key] as TSubResult;
                        return (
                        <tr className="divide-x-[2px] divide-gray-500 flexw-full" key={key}>
                            <td className="font-bold px-4 py-2 w-1/3">{subj.subject}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj['Semester 1']}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj['Semester 1_grade']}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj['Semester 2']}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj['Semester 2_grade']}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj.average}</td>
                            <td className="py-2 px-4 text-center w-[79.5px]">{subj.grade}</td>
                        </tr>
                    )})
                }
                </tbody>
                <tfoot className="divide-y-[2px] divide-gray-500">
                    <tr className="bg-gray-700 text-white divide-x-[2px] divide-gray-500 flex w-full">
                        <td className="font-bold px-4 py-2 w-1/3">Totals</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{sem1_total}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{sem2_total}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.total_marks}</td>
                    </tr>
                    <tr className="bg-gray-700 text-white divide-x-[2px] divide-gray-500 flex w-full">
                        <td className="font-bold px-4 py-2 w-1/3">Rank</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{ranks['Semester 1']}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{ranks['Semester 2']}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.rank}</td>
                    </tr>
                </tfoot>
                </table>
            </div>
        </section>
    );
}
