import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Archives
                </h2>
            }
        >
            <Head title="Archives" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg flex flex-wrap p-4 justify-between gap-4">
                        <Link href={route('archive.students')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Students Archive</h3>
                            </div>
                        </Link>
                        <Link href={route('archive.semesters')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Semesters Archive</h3>
                            </div>
                        </Link>
                        <Link href={route('archive.grades')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Grades Archive</h3>
                            </div>
                        </Link>
                        <Link href={route('archive.subjects')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Subjects Archive</h3>
                            </div>
                        </Link>
                        <Link href={route('archive.exams')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Exams Archive</h3>
                            </div>
                        </Link>
                        <Link href={route('archive.results')} className="w-fit">
                            <div className="w-full min-w-[300px] hover:shadow-md rounded-md transition-all ease-in-out duration-300 p-4 border-[1px] border-gray-300">
                                <h3>Results Archive</h3>
                            </div>
                        </Link>
                     </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
