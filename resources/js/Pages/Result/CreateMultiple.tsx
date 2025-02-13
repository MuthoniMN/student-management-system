import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"
import { Head, router, useForm, usePage } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { TStudent, TSemester, TExam, TGrade, TSubject, TResultsOptions, TResult, TFlash } from "@/types/";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import TextInput from "@/Components/TextInput";
import { getGrade } from "@/Components/ResultForm";
import PrimaryButton from "@/Components/PrimaryButton";

export default function CreateMultipleResults({ students, semesters, grades, exams, subjects }: {
    students: TStudent[],
    semesters: TSemester[],
    grades: TGrade[],
    exams: TExam[],
    subjects: TSubject[]
}){
    const [gradeStudents, setGradeStudents] = useState<TStudent[]>([]);
    const [availableExams, setAvailableExams] = useState<TExam[]>([]);
    const [options, setOptions] = useState<TResultsOptions>({
        subject_id: 0,
        semester_id: 0,
        exam_id: 0,
        grade_id: 0
    });
    const [results, setResults] = useState<TResult[]>([]);

    const { errors } = useForm();
    const flash = usePage().props.flash as TFlash;

    useEffect(() => {
        if(options.grade_id > 0){
            setGradeStudents(students.filter(student => student.grade_id == options.grade_id));
        }if(options.grade_id > 0 && options.subject_id > 0 && options.semester_id){
            setAvailableExams(exams.filter(exam => exam.grade_id == options.grade_id && exam.subject_id == options.subject_id && exam.semester_id == options.semester_id));
        }
    }, [options]);

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;

        setOptions({
            ...options,
            [key]: Number(value)
        });
    }

    const addResult = (e, student: TStudent) => {
        const newResult = {
            student_id: student.id,
            result: Number(e.target.value),
            grade: getGrade(e.target.value),
            exam_id: options.exam_id
        };
        const filteredResults = results.filter(result => result.student_id != student.id);

        setResults([
            ...filteredResults,
            newResult
        ])
    }

    const handleSubmit = (e) => {
        e.preventDefault();

        router.post(route('results.store'), { results });
    }

    return (
        <AuthenticatedLayout header={
            <div className="flex gap-4 items-center">
                <h1 className="text-xl font-bold">Create Results</h1>
            </div>
        }>
            <Head title={"Create Results"} />
            <section className="flex justify-between divide-x-2">
                <form className="space-y-4 w-fit min-w-[250px] mx-auto my-8 p-4">
                    <div className="space-y-2 w-full">
                        <InputLabel value="Grade" htmlFor="grade_id" />
                        <select name="grade_id" onChange={handleChange} className="w-full">
                            <option value="">---</option>
                            {
                                grades.map(grade => (
                                    <option value={grade.id} key={grade.id}>{grade.name}</option>
                                ))
                            }
                        </select>
                        {errors.grade_id && (<InputError message={errors.grade_id} />)}
                    </div>
                    <div className="space-y-2 w-full">
                        <InputLabel value="Subject" htmlFor="subject_id" />
                        <select name="subject_id" onChange={handleChange} className="w-full">
                            <option value="">---</option>
                            {
                                subjects.map(subject => (
                                    <option value={subject.id} key={subject.id}>{subject.title}</option>
                                ))
                            }
                        </select>
                        {errors.subject_id && (<InputError message={errors.subject_id} />)}
                    </div>
                   <div className="space-y-2">
                        <InputLabel value="Semester" htmlFor="semester_id" />
                        <select name="semester_id" onChange={handleChange} className="w-full">
                            <option value="">---</option>
                            {
                                semesters.map(semester => (
                                    <option value={semester.id} key={semester.id}>{semester.title} {semester.year && semester.year.year}</option>
                                ))
                            }
                        </select>
                        {errors.semester_id && (<InputError message={errors.semester_id} />)}
                    </div>
                   <div className="space-y-2">
                        <InputLabel value="Exam" htmlFor="exam_id" />
                        <select name="exam_id" onChange={handleChange} className="w-full">
                            <option value="">---</option>
                            {
                                availableExams.map(exam => (
                                    <option value={exam.id} key={exam.id}>{exam.title}</option>
                                ))
                            }
                        </select>
                        {errors.exam_id && (<InputError message={errors.exam_id} />)}
                    </div>
                </form>

                <section className="w-full p-4 space-y-6">
                    <h2 className="text-lg font-bold">Students</h2>
                    {
                        gradeStudents.length > 0 ? gradeStudents.map(student => (
                            <div className="flex gap-4 items-center justify-between">
                                <p>{student.name}</p>
                                <TextInput type="number" onChange={(e) => addResult(e, student)} />
                            </div>
                        )) :
                            <p>Filter to view students</p>
                    }
                    <PrimaryButton disabled={results.length < 0} onClick={handleSubmit}>Add Results</PrimaryButton>
                </section>
            </section>
            { flash && flash.create && (
                <div className="bg-emerald-300 text-emerald-800 font-bold text-lg w-fit p-4 fixed bottom-4 right-4">
                    <p>{flash.create}</p>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
