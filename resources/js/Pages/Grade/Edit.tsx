import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { TGrade } from "@/Pages/Grade/List";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";

export default function EditGrade({ grade }: { grade: TGrade }) {
    const { patch, data, setData } = useForm({
        name: grade.name,
        description: grade.description
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        patch(route('grades.update', grade));
    }

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;

        setData({
            ...data,
            [key]: value
        });
    }

    return (
        <AuthenticatedLayout header={
                <div className="flex gap-6">
                    <Link href={route('grades.index', grade.id)}>
                        <SecondaryButton>
                            <FaAngleLeft />
                        </SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Edit {grade.name}</h2>
                </div>

        } >
            <Head title={`Edit ${grade.name}`} />
            <section className="py-8 w-fit mx-auto">
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <InputLabel htmlFor="name" value="Name: " />
                        <TextInput value={data.name} id="name" name="name" onChange={handleChange} />
                    </div>
                    <div>
                        <InputLabel htmlFor="description" value="Description: " />
                        <textarea value={data.description} id="description" name="description" onChange={handleChange}></textarea>
                    </div>
                    <PrimaryButton>Edit</PrimaryButton>
                </form>
            </section>
        </AuthenticatedLayout>
    );
}
