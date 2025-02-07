import { useForm, router } from "@inertiajs/react";
import PrimaryButton from '@/Components/PrimaryButton';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import TextInput from '@/Components/TextInput';
import { TSubject } from "@/types/";

export default function SubjectForm({ subject }: { subject?: TSubject }){

    const { data, setData, post, processing, errors }= useForm({
        title: (subject && (subject as TSubject).title) || '',
        description: (subject && (subject as TSubject).description) || '',
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
            <PrimaryButton type="submit" disabled={processing}>{subject ? 'Edit' : 'Add'} Subject</PrimaryButton>
        </form>
    );
}

