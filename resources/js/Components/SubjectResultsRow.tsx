import { getGrade } from "./ResultForm";
import { TYearResult } from "@/types/";

export default function SubjectRow({ title, results } : { title: string, results: TYearResult }){
    return (
        <tr className="divide-x-[2px] divide-gray-500 flexw-full">
            <td className="font-bold px-4 py-2 w-1/3">{title}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{results.exams['Semester 1']?.subjects[title]}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{getGrade(results.exams['Semester 1']?.subjects[title])}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{results.exams['Semester 2']?.subjects[title]}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{getGrade(results.exams['Semester 2']?.subjects[title])}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{results.subject_averages[title]}</td>
            <td className="py-2 px-4 text-center w-[79.5px]">{getGrade(results.subject_averages[title])}</td>
        </tr>
    );
}
