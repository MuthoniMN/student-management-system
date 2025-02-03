import { useState } from "react";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from "@inertiajs/react";
import { FaAngleDown, FaAngleUp, FaPenToSquare, FaTrash } from "react-icons/fa6";
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';

export type TGrade = {
    id: number,
    name: string,
    description: string,
    created_at: Date|null,
    updated_at: Date|null,
    students_count: number
};

export default function GradeList({ grades }: {
    grades: TGrade[]
}) {
    const [current, setCurrent] = useState<number|null>(null);
    const { submit, delete: destroy } = useForm();

    const handleClick = (id: number) => {
        if(current == id) {
            setCurrent(null);
            return;
        }
        setCurrent(id);
    }

    const handleSubmit = (e, grade: TGrade) => {
        e.preventDefault();
        submit('delete', route('grades.destroy', grade.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold text-gray-800">Available Grades</h2>
            }
        >
            <Head title="Grades" />
            <ul className="mx-auto my-6 bg-white rounded-lg py-4 space-y-4">
                {
                    grades.map(grade => (
                        <li key={grade.id} className={`${current == grade.id && 'bg-slate-100'} w-full px-4 hover:bg-gray-100 hover:shadow-sm transition-all ease-in-out duration-200 space-y-2`} onClick={() => handleClick(grade.id)}>
                            <div className="flex w-full items-center justify-between">
                                <h3 className="text-lg font-bold">{grade.name}  <span className="italic font-light">({grade.students_count} learners)</span></h3>
                                {
                                    current == grade.id ? <FaAngleUp /> : <FaAngleDown />
                                }
                            </div>
                            <div className={`${current == grade.id ? 'block' : 'hidden'}`}>
                                <p>{grade.description}</p>
                                <div className="w-full flex gap-4 items-center justify-end py-2">
                                <Link href={route('grades.edit', grade)}>
                                    <PrimaryButton>
                                        <FaPenToSquare />
                                    </PrimaryButton>
                                </Link>
                                <DangerButton onClick={(e) => handleSubmit(e, grade)}>
                                    <FaTrash />
                                </DangerButton>
                                </div>
                            </div>
                        </li>
                    ))
                }
            </ul>
        </AuthenticatedLayout>
    );
}
