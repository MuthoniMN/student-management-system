import { TStudentResult } from "@/types/";

export default function StudentsResults({ results }: { results: TStudentResult }){
    return (
        <section className="p-4 space-y-6 max-w-[750px] mx-auto">
            <div className="flex w-full justify-between items-center">
                <h2 className="text-xl font-bold">Student Name: {results.name}</h2>
                <p>Student ID: {results.studentId}</p>
            </div>
            <div className="space-y-4">
                <h3 className="text-lg font-bold">Subject Performance</h3>
                <div className="border-[2px] divide-y-[2px] divide-gray-500 border-gray-700 w-full">
                {
                    results.results.subjects.map(subj => (
                        <div className="divide-x-[2px] divide-gray-500 flex">
                            <p className="font-bold px-4 py-2 w-1/3">{subj.subject_name}</p>
                            <p className="py-2 px-4 w-1/3 text-center">{subj.average_marks}</p>
                            <p className="py-2 px-4 w-1/3 text-center">{subj.grade}</p>
                        </div>
                    ))
                }
                </div>
            </div>
            <div className="space-y-4">
                <h3 className="text-lg font-bold">Results Summary:</h3>
                <div className="w-full flex justify-between">
                    <p>Total: {results.results.total}</p>
                </div>
            </div>
        </section>
    );
}
