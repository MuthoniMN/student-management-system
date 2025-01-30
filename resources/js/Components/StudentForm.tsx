import { useState } from "react";
import { useForm } from "@inertiajs/react";
import PrimaryButton from '@/Components/PrimaryButton';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import TextInput from '@/Components/TextInput';
import { TGrade } from "@/Pages/Grade/List";
import { TStudent } from "@/Pages/Student/List";

type TParent = {
    id: number,
    name: string,
    email: string,
    phone_number: string,
    address: string,
}

export default function StudentForm({ parents, grades, student }: { parents: TParent[], grades: TGrade[], student?: TStudent }){
    const [exists, setExists] = useState(false);
    let grade, parent;

    if(student){
        grade = grades.filter(grade => grade.id === student.grade_id)[0];
        parent = parents.filter(parent => parent.id === student.parent_id)[0];
    }

    const { data, setData, post, patch, processing, errors }= useForm({
        name: (student && (student as TStudent).name) || '',
        studentId: (student && (student as TStudent).studentId) || '',
        grade_id: (student && (student as TStudent).grade_id) || null,
        parent_name: (parent && (parent as any).name as string) || '',
        email: (parent && (parent as any).email) || '',
        phone_number: (parent && (parent as any).phone_number) || '',
        address: (parent && (parent as any).address) || '',
    });

    function ifParentExists(name){
        return parents.find(parent => parent.name == name);
    }

    function handleChange(e) {
        const key = e.target.name;
        const value = e.target.value;

        if(key == "parent_name"){
            const parent = ifParentExists(value);

            if(parent){
                setExists(true);
                setData(vals => ({
                    ...vals,
                    [key]: value,
                    'email': parent.email,
                    'phone_number': parent.phone_number,
                    'address': parent.address
                }))
                return;
            }
        }
        setData(vals => ({
            ...vals,
            [key]: value,
        }));
        console.log(data);
        return;
    }

    function handleSubmit(e) {
        e.preventDefault();
        if(!student) {
            post('/students');
        } else {
            patch(route(`students.update`, student.id));
        }
    }

    return (
        <form onSubmit={handleSubmit} className="w-fit mx-auto space-y-2 py-4">
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="name" value="Name: " />
                <TextInput name="name" id="name" value={data.name} onChange={handleChange} />
               {errors.name &&  (<InputError message={`${errors.name}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="studenId" value="Student ID: " />
                <TextInput name="studentId" id="studentId" value={data.studentId} onChange={handleChange} />
               {errors.studentId &&  (<InputError message={`${errors.studentId}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="grade_id" value="Grade: " />
                <select onChange={handleChange} name="grade_id" className="w-full rounded-md border-gray-300" value={`${data.grade_id}`}>
                    <option value="null">-- Please Select --</option>
                    {
                        grades.map(grade => (
                            <option key={grade.id} value={grade.id}>{grade.name}</option>
                        ))
                    }
                </select>
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="parent_name" value="Parent Name: " />
                <TextInput list="parents" name="parent_name" id="parent_name" value={data.parent_name} onChange={handleChange} />
                <datalist id="parents">{
                    parents.map(parent => (
                        <option key={parent.id} value={parent.name} />
                    ))
                }</datalist>
               {errors.parent_name &&  (<InputError message={`${errors.parent_name}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="email" value="Email: " />
                <TextInput name="email" id="email" value={data.email} onChange={handleChange} disabled={exists} />
               {errors.email &&  (<InputError message={`${errors.email}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="phone_number" value="Phone Number: " />
                <TextInput name="phone_number" id="phone_number" value={data.phone_number} onChange={handleChange} disabled={exists} />
               {errors.phone_number &&  (<InputError message={`${errors.phone_number}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="address" value="Address: " />
                <TextInput name="address" id="address" value={data.address} onChange={handleChange} disabled={exists} />
               {errors.address &&  (<InputError message={`${errors.address}`} />)}
            </div>
            <PrimaryButton type="submit" disabled={processing}>{student ? 'Edit' : 'Add'} Student</PrimaryButton>
        </form>
    );
}

