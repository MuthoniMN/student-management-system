import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function List({ students }: { students: any }){
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold">All Students</h2>
            }
        >
            <Head title="Student List" />
            <section className="w-[95vw] h-fit mx-auto p-6 my-4 bg-white rounded-lg overflow-scroll">
                <table className="w-full divide-y-2 divide-gray-300 border-gray-300 border-2 overflow-scroll">
                    <thead>
                        <tr className="divide-x-2 divide-gray-300 text-left">
                            <th className="px-2">Student ID</th>
                            <th className="px-2">Name</th>
                            <th className="px-2">Grade</th>
                            <th className="px-2">Email</th>
                            <th className="px-2">Phone Number</th>
                            <th className="px-2">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            students.data.map(student => (
                                <tr className="divide-x-2 divide-gray-300">
                                    <td className="px-2 min-w-24">{student.studentId}</td>
                                    <td className="px-2 min-w-36">{student.name}</td>
                                    <td className="px-2 min-w-24">{student.grade}</td>
                                    <td className="px-2 min-w-36">{student.email}</td>
                                    <td className="px-2 min-w-24">{student.phone_number}</td>
                                    <td className="px-2 min-w-24">{student.address}</td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
                <div className="py-4 text-center">
                    {
                        students.links.map(link => (
                            link.url ?

                                <Link
                                    className={`p-1 mx-1 ${link.active ? 'font-bold text-blue-400 underline' : ''}`}
                                    key={link.label} href={link.url} dangerouslySetInnerHTML={{ __html: link.label }} />
                                :

                                <span
                                    className="cursor-not-allowed text-gray-300"
                                    key={link.label} dangerouslySetInnerHTML={{ __html: link.label }}>
                                </span>
                        ))
                    }
                </div>
            </section>
        </AuthenticatedLayout>
    );
}
