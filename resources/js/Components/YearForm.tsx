import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import { useForm } from "@inertiajs/react";
import { TYear } from "@/types/";

export default function YearForm ( { year } : { year?: TYear } ){
    const { data, setData, post, patch, errors } = useForm({
        year: (year && year.year) || '',
        end_date: (year && year.end_date) || null,
        start_date: (year && year.start_date) || null
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if(year) {
            patch(route('years.update', year.id));
            return;
        }
        post(route('years.store'));
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
        <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
                <InputLabel htmlFor="year" value="Year: " />
                <TextInput name="year" value={data.year} onChange={handleChange} />
                {errors.year && (<InputError message={errors.year} />)}
            </div>
            <div className="space-y-2">
                <InputLabel htmlFor="start_date" value="Start Date: " />
                <TextInput type="date" name="start_date" value={data.start_date} onChange={handleChange} className="w-full" />
                {errors.start_date && (<InputError message={errors.start_date} />)}
            </div>
            <div className="space-y-2">
                <InputLabel htmlFor="end_date" value="End Date: " />
                <TextInput type="date" name="end_date" value={data.end_date} onChange={handleChange} className="w-full" />
                {errors.end_date && (<InputError message={errors.end_date} />)}
            </div>
            <PrimaryButton>{year ? 'Edit' : 'Add'} Academic Year</PrimaryButton>
        </form>
    );
}
