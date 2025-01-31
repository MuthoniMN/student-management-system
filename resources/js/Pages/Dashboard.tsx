import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <h3 className="px-6 pt-2 text-lg font-bold">Students</h3>
                        <div className="p-6 text-gray-900 flex flex-wrap w-full gap-6">
                            <Link href={route('students.create')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>Create a New Student</h3>
                                </div>
                            </Link>
                            <Link href={route('students.index')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>View Students</h3>
                                </div>
                            </Link>

                        </div>
                        <h3 className="px-6 pt-2 text-lg font-bold">Semesters</h3>
                        <div className="p-6 text-gray-900 flex flex-wrap w-full gap-6">
                            <Link href={route('students.create')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>Start a New Academic Year</h3>
                                </div>
                            </Link>
                            <Link href={route('students.create')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>Start a New Semester</h3>
                                </div>
                            </Link>
                            <Link href={route('grades.index')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>View Available Grades</h3>
                                </div>
                            </Link>
                        </div>
                        <h3 className="px-6 pt-2 text-lg font-bold">Subjects</h3>
                        <div className="p-6 text-gray-900 flex flex-wrap w-full gap-6 justify-between">
                            <Link href={route('students.create')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>Create a New Subject</h3>
                                </div>
                            </Link>
                        </div>
                        <h3 className="px-6 pt-2 text-lg font-bold">Assessments</h3>
                        <div className="p-6 text-gray-900 flex flex-wrap w-full gap-6 justify-between">
                            <Link href={route('students.create')} className="w-fit">
                                <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                    <h3>Create a New Assessment</h3>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
