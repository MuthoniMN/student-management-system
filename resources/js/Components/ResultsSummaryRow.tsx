import { TResultSummary } from "@/types/";

function getResult(year: string, grade: string, semester: string, type: string, result: TResultSummary, subject: string){
    let marks = 0;
    if(year.length > 0 && grade.length > 0 && semester.length > 0 && type.length > 0){
        marks = result.years[year][grade][semester][type].subjects[subject]
    }else if(year.length > 0 && grade.length > 0 && semester.length > 0){
        marks = result.years[year][grade][semester].subject_averages[subject]
    }else if(year.length > 0 && grade.length > 0){
        marks = result.years[year][grade].subject_averages[subject]
    }

    return marks;
}

export default function ResultsSummmaryRow({ index, year, grade, semester, type, result }: {
    index: number,
    year: string,
    grade: string,
    semester: string,
    type: string,
    result: TResultSummary
}){
    return (
        <tr className="divide-x-2 divide-gray-300" key={`${index}`}>
            <td className="px-2 text-center">{index + 1}</td>
            <td className="px-2 min-w-24 hover:underline transition-all duration-300 ease-in-out">{result.name}</td>
            <td className="px-2 min-w-36">{grade}</td>
            <td className="px-2">{year}</td>
            <td className="px-2 min-w-36">{semester || '-'}</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'Mathematics') }</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'English') }</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'Kiswahili') }</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'Science')}</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'History') }</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'Geography') }</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'Computer')}</td>
            <td className="px-2">{ getResult(year, grade, semester, type, result, 'CRE') }</td>
            <td className="px-2">{
                (year.length > 0 && grade.length > 0 && semester.length > 0 && type.length > 0) ?
                result.years[year][grade][semester][type].total
                : (year.length > 0 && grade.length > 0 && semester.length > 0) ?
                    result.years[year][grade][semester].total
                : (year.length > 0 && grade.length > 0) ?
                    result.years[year][grade].total
                : 0
            }</td>
        </tr>
    );
}
