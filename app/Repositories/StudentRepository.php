<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class StudentRepository implements StudentRepositoryInterface
{
    /**
     * @desc get all students with their grade and parent
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return Student::with(['grade', 'parent'])->get();
    }

    /**
     * @desc get student
     * @param Student $student
     * @return Student
     * */
    public function get(Student $student): Student
    {
        return Student::with(['parent', 'grade'])->where('id', '=', $student->id)->first();
    }

    /**
     * @desc get student by id
     * @param int $id
     * @return Student
     * */
    public function findById(int $id): Student
    {
        return Student::with(['parent', 'grade'])->where('id', '=', $id)->first();
    }

    /**
     * @desc update student
     * @param Student $student
     * @return Student
     * */
    public function update(Student $student, array $attributes): Student
    {
        $student->name = $attributes['name'];
        $student->studentId = $attributes['studentId'];
        $student->grade_id = $attributes['grade_id'];

        if($student->isDirty()){
            $student->save();
        }

        return $student;
    }

    /**
     * @desc upgrade multiple students to the next grade
     * @param array $students
     * @param Grade $grade
     * @return array
     * */
    public function upgrade(array $students): array
    {
        foreach ($students as $key) {
            $student = Student::find($key);

            if($student['grade_id'] < 8){
                $student['grade_id'] = $student['grade_id'] + 1;
                $student->save();
            }else {
                $student->delete();
            }

        }

        return $students;
    }

    /**
     * @desc create a new student
     * @param array $attributes
     * @return Student
     * */
    public function create(array $attributes): Student
    {
        $student = new Student;
        $student['studentId'] = $attributes['studentId'];
        $student['name'] = $attributes['name'];
        $student['parent_id'] = $attributes['parent_id'];
        $student['grade_id'] = $attributes['grade_id'];

        $student->save();

        return $student;
    }

    /**
     * @desc delete a student
     * @param Student $student
     * @return Student
     * */
    public function delete(Student $student): bool
    {
        return $student->delete();
    }

    /**
     * @desc delete many students
     * @param array $students
     * @return array
     * */
    public function deleteMany(array $students)
    {
        return Student::destroy($attributes);
    }

    /**
     * @desc restore a soft deleted student
     * @param int $id
     * @return Student
     * */
    public function restore(int $id): Student
    {
        $student = Student::withTrashed()->where('id', $id)->first();
        return $student->restore();
    }

    /**
     * @desc restore multiple soft deleted instances
     * @param array $students
     * @return array
     * */
    public function restoreMany(array $students): array
    {
        foreach ($students as $id) {
            $student = Student::withTrashed()->where('id', $id)->first();
            $student->restore();
        }

        return $students;
    }

    public function archive(): Collection
    {
        return  Student::onlyTrashed()->with(['parent', 'grade'])->get();
    }
}
