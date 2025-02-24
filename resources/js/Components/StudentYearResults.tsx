import { TYearResult, TStudent, TRank } from "@/types/";
import SubjectResultRow from "@/Components/SubjectResultsRow";

export default function StudentYearResults({ yearResults, ranks, student }: {  student: TStudent, yearResults: TYearResult, ranks: TRank }){
    console.log(ranks);
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
                    <SubjectResultRow title="Mathematics" results={yearResults} />
                    <SubjectResultRow title="English" results={yearResults} />
                    <SubjectResultRow title="Kiswahili" results={yearResults} />
                    <SubjectResultRow title="Science" results={yearResults} />
                    <SubjectResultRow title="Computer" results={yearResults} />
                    <SubjectResultRow title="History" results={yearResults} />
                    <SubjectResultRow title="Geography" results={yearResults} />
                    <SubjectResultRow title="CRE" results={yearResults} />
                </tbody>
                <tfoot className="divide-y-[2px] divide-gray-500">
                    <tr className="bg-gray-700 text-white divide-x-[2px] divide-gray-500 flex w-full">
                        <td className="font-bold px-4 py-2 w-1/3">Totals</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.exams['Semester 1'].total}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.exams['Semester 2'].total}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.total}</td>
                    </tr>
                    <tr className="bg-gray-700 text-white divide-x-[2px] divide-gray-500 flex w-full">
                        <td className="font-bold px-4 py-2 w-1/3">Rank</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{ranks['Semester 1'].rank}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{ranks['Semester 2'].rank}</td>
                        <td className="font-bold px-4 py-2 w-[156px] text-center" colSpan={2}>{yearResults.rank}</td>
                    </tr>
                </tfoot>
                </table>
            </div>
        </section>
    );
}
