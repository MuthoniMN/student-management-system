import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import { useForm } from "@inertiajs/react";
import { TYear } from "@/Components/YearForm";

export type TSemester = {
    id?: number,
    year?: string,
    academic_year_id: number,
    title: string,
    end_date: string,
    start_date: string,
    created_at?: string,
    updated_at?: string
}

export default function SemesterForm ( { years, semester } : { semester?: TSemester, years: TYear[] } ){
    const { data, setData, post, patch, errors } = useForm({
        title: (semester && semester.title) || '',
        end_date: (semester && semester.end_date) || null,
        start_date: (semester && semester.start_date) || null,
        academic_year_id: (semester && semester.academic_year_id) || null
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if(semester) {
            patch(route('semesters.update', semester.id));
            return;
        }
        post(route('semesters.store'));
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
        <form onSubmit={handleSubmit} className="space-y-4 w-56 mx-auto py-6">
            <div className="space-y-2">
                <InputLabel htmlFor="" value="Academic Year: " />
                <select value={data.academic_year_id as number} onChange={handleChange} name="academic_year_id" className="w-full border-gray-300 border-[1px]">
                    <option value="">-- Please Select --</option>
                    {
                        years.map(year => (
                            <option key={year.id} value={year.id}>{year.year}</option>
                        ))
                    }
                </select>
                {errors.academic_year_id && (<InputError message={errors.academic_year_id} />)}
            </div>
            <div className="space-y-2">
                <InputLabel htmlFor="title" value="Title: " />
                <select value={data.title} onChange={handleChange} name="title" className="w-full border-gray-300 border-[1px]">
                    <option value="">-- Please Select --</option>
                    <option value="Semester 1">Semester 1</option>
                    <option value="Semester 2">Semester 2</option>
                </select>
                {errors.title && (<InputError message={errors.title} />)}
            </div>
            <div className="space-y-2">
                <InputLabel htmlFor="start_date" value="Start Date: " />
                <TextInput type="date" name="start_date" value={data.start_date} onChange={handleChange} className="w-full" />
                {errors.start_date && (<InputError message={errors.start_date} />)}
            </div>
            <div className="space-y-2">
                <InputLabel htmlFor="end_date" value="End Date: " />
                <TextInput type="date" name="end_date" value={data.end_date} onChange={handleChange} className="w-full"/>
                {errors.end_date && (<InputError message={errors.end_date} />)}
            </div>
            <PrimaryButton>{semester ? 'Edit' : 'Add'} Semester</PrimaryButton>
        </form>
    );
}
