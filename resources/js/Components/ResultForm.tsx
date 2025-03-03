import { useEffect } from 'react';
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import { useForm } from "@inertiajs/react";
import { TStudent, TSubject, TExam, TResult, TGrade } from "@/types/";
import PrimaryButton from "@/Components/PrimaryButton";

export const getGrade = (num: number) => {
    if(num > 80){
        return 'A';
    }else if(num > 65){
        return 'B';
    }else if(num > 50){
        return 'C';
    }else if(num > 40){
        return 'D';
    }else{
        return 'E';
    }
}

export default function ExamForm({ subject, exam, students, result }: { result?: TResult, subject: TSubject, students: TStudent[], exam: TExam }){
    const { data, setData, post, patch, errors } = useForm({
        'result': (result && result.result) || 0,
        'grade': (result && result.grade) || null,
        'student_id': (result && result.student_id) || 0,
    });

    const handleSubmit = (e) => {
        e.preventDefault();

        if(!result){
            post(route('subjects.exams.results.store', [subject.id, exam.id]));
            return;
        }

        patch(
            route('subjects.exams.results.update', [subject.id, exam.id, result.id]));
    }



    useEffect(() => {
        if(data.result !== 0){
            const grade = getGrade(Number(data.result));

            setData({
                ...data,
                'grade': grade
            })
        }
    }, [data.result]);

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;

        setData({
            ...data,
            [key]: key === 'student_id' ? Number(value) : value
        });

        console.log(data);
        return;
    }

    return (
        <form onSubmit={handleSubmit} className="w-fit space-y-4">
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="result" value="Result: " />
                <TextInput type="number" value={data.result} name="result" id="result" onChange={handleChange} className="w-full" />
                {errors.result && <InputError message={errors.result} />}
            </div>
            <div className="space-y-2 w-full">
                <InputLabel htmlFor="student_id" value="Student: " />
                <select onChange={handleChange} name="student_id" className="w-full rounded-md border-gray-300" value={`${data.student_id}`}>
                    <option value="null">-- Please Select --</option>
                    {
                        students.map(student => (
                            <option key={student.id} value={student.id}>{student.name} - {(student.grade as TGrade).name}</option>
                        ))
                    }
                </select>
                {errors.student_id && <InputError message={errors.student_id} />}
            </div>
            <PrimaryButton>{result ? 'Edit' : 'Add'} Result</PrimaryButton>
        </form>
    );
}
