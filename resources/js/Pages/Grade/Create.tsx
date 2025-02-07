import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import SecondaryButton from "@/Components/SecondaryButton";
import { FaAngleLeft } from "react-icons/fa6";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";

export default function CreateGrade() {
    const { post, data, setData } = useForm({
        name: '',
        description: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('grades.store'));
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
                    <Link href={route('grades.index')}>
                        <SecondaryButton><FaAngleLeft /></SecondaryButton>
                    </Link>
                    <h2 className="text-xl font-bold">Add Grade</h2>
                </div>

        } >
            <Head title={`Add a Grade`} />
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
                    <PrimaryButton>Add</PrimaryButton>
                </form>
            </section>
        </AuthenticatedLayout>
    );
}
