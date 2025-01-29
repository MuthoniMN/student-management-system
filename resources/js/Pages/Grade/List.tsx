import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from "@inertiajs/react";

export default function GradeList() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold text-gray-800">Available Grades</h2>
            }
        >
            <Head title="Grades" />
        </AuthenticatedLayout>
    );
}
