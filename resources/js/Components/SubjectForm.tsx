import { useForm, router } from "@inertiajs/react";
import PrimaryButton from '@/Components/PrimaryButton';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import TextInput from '@/Components/TextInput';
import { TGrade } from "@/Pages/Grade/List";

export type TSubject = {
    id?: number,
    title: string,
    description: string,
    outline?: string|File,
    grade_id: number,
    created_at?: string,
    updated_at?: string
}

export default function SubjectForm({ grades, subject }: { grades: TGrade[], subject?: TSubject }){
    let grade;

    if(subject){
        grade = grades.filter(grade => grade.id === subject.grade_id)[0];
    }

    const { data, setData, post, patch, progress, processing, errors }= useForm({
        title: (subject && (subject as TSubject).title) || '',
        description: (subject && (subject as TSubject).description) || '',
        grade_id: (subject && (subject as TSubject).grade_id) || null,
        outline: null,
    });

    function handleChange(e) {
        const key = e.target.name;
        const value = e.target.value;

        setData(vals => ({
            ...vals,
            [key]: value,
        }));
        return;
    }

    function handleSubmit(e) {
        e.preventDefault();
        if(!subject) {
            post('/subjects');
        } else {
            console.log(data);
            router.post(route(`subjects.update`, subject.id), {
                _method: 'PUT',
                ...data
            });
        }
    }

    return (
        <form onSubmit={handleSubmit} className="w-fit mx-auto space-y-2 py-4">
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="title" value="Title: " />
                <TextInput name="title" id="title" value={data.title} onChange={handleChange} className="w-full" />
               {errors.title &&  (<InputError message={`${errors.title}`} />)}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="description" value="Description: " />
                <TextInput name="description" id="description" value={data.description} onChange={handleChange} className="w-full" />
               {errors.description &&  (<InputError message={`${errors.description}`} />)}
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
                <InputLabel htmlFor="outline" value="Subject Outline: " />
                <TextInput type="file" name="outline" id="outline" onChange={(e) => setData('outline', e.target.files[0])} className="w-full" />
                {progress && (
                  <progress value={progress.percentage} max="100">
                    {progress.percentage}%
                  </progress>
                )}
                {errors.outline &&  (<InputError message={`${errors.outline}`} />)}
            </div>
            <PrimaryButton type="submit" disabled={processing}>{subject ? 'Edit' : 'Add'} Subject</PrimaryButton>
        </form>
    );
}

