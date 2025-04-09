import { Link } from "@inertiajs/react";
import { FaAngleRight } from "react-icons/fa6";
import { TSemester } from "@/types/";

export default function GradeResults({ grade, year, semesters, studentId } : { grade: string, year: string, semesters: TSemester[], studentId: number }){
    return (
                <div className="flex flex-col gap-2 w-full">
                    <div className="flex flex-col gap-[6px] border-2 border-gray-200 p-4 w-full">
                        <div className="py-2 text-lg flex justify-between">
                            <h2>{grade} - {year}</h2>
                            <Link href={route('students.yearly-results', [studentId, semesters[0].academic_year_id])} className="flex gap-2 items-center hover:underline">
                                <span>Year Summary</span> <FaAngleRight />
                            </Link>
                        </div>
                        <div className="flex gap-4 flex-wrap w-full">
                        { semesters.map(semester => (
                            <Link href={route('students.results', [studentId, semester.id])} className="w-1/3">
                                <div className="w-full py-6 rounded-lg bg-white shadow-md text-center">
                                    <h3 className="text-lg">{semester.title}</h3>
                                </div>
                            </Link>
                            ))
                        }
                        </div>
                    </div>
                </div>

    );
}
