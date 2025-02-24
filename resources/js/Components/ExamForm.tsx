import { FormEvent, ChangeEvent } from "react";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import { useForm, router } from "@inertiajs/react";
import { TSemester, TSubject, TGrade, TExam, TYear} from "@/types/";
import PrimaryButton from "@/Components/PrimaryButton";

export default function ExamForm({ exam, semesters, grades, subject }: { exam?: TExam, semesters: TSemester[], grades: TGrade[], subject: TSubject }){
    const { data, setData, post, progress, errors } = useForm({
        'title': (exam && exam.title) || '',
        'type': (exam && exam.type) || '',
        'file': (exam && exam.file) || null,
        'grade_id': (exam && exam.grade.id) || 0,
        'semester_id': (exam && exam.semester.id) || 0,
        'exam_date': (exam && exam.exam_date) || null
    });
    console.log(semesters);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();

        if(!exam){
            post(route('subjects.exams.store', subject.id));
            return;
        }

        router.post(
            route('subjects.exams.update', [subject.id, exam.id]),
            {
                _method: "PATCH",
                ...data
            }
        );
    }

    const handleChange = (e: ChangeEvent<HTMLSelectElement>) => {
        const key = e.target.name;
        const value = e.target.value;

        setData({
            ...data,
            [key]: (key === 'grade_id' || key === 'semester_id') ? Number(value) : value
        });
        return;
    }

    return (
        <form onSubmit={handleSubmit} className="w-fit space-y-4">
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="title" value="Title: " />
                <TextInput value={data.title} name="title" id="title" onChange={handleChange} className="w-full" />
                {errors.title && <InputError message={errors.title} />}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="type" value="Type: " />
                <select onChange={handleChange} name="type" className="w-full rounded-md border-gray-300" value={`${data.type}`}>
                    <option value="null">-- Please Select --</option>
                    <option value="exam">Exam</option>
                    <option value="CAT">CAT</option>
                </select>
                {errors.type && <InputError message={errors.type} />}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="file" value="Document: " />
                <TextInput name="file" id="file" type="file" className="w-full" onChange={(e: ChangeEvent<HTMLInputElement>) => setData({ ...data, file: (e.target.files as FileList)[0] })} />
                {progress && <progress value={progress.percentage} max="100" className="bg-gray-200">{progress.percentage}%</progress>}
                {errors.file && <InputError message={errors.file} />}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="exam_date" value="Exam Date: " />
                <TextInput type="date" value={data.exam_date} name="exam_date" id="exam_date" onChange={handleChange} className="w-full" />
                {errors.exam_date && <InputError message={errors.exam_date} />}
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
                {errors.file && <InputError message={errors.file} />}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="semester_id" value="Semester: " />
                <select onChange={handleChange} name="semester_id" className="w-full rounded-md border-gray-300" value={`${data.semester_id}`}>
                    <option value="null">-- Please Select --</option>
                    {
                        semesters.map(semester => (
                            <option key={semester.id} value={semester.id}>{semester.title} - {(semester.year as TYear).year}</option>
                        ))
                    }
                </select>
                {errors.semester_id && <InputError message={errors.semester_id} />}
            </div>
            <PrimaryButton>{exam ? 'Edit' : 'Add'} Assessment</PrimaryButton>
        </form>
    );
}
