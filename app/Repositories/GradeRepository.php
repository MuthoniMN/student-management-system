<?php

namespace App\Repositories;

use App\Interfaces\GradeRepositoryInterface;
use App\Models\Grade;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Collection;

class GradeRepository implements GradeRepositoryInterface
{

    /**
     * @desc get all available grades
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return Grade::withCount('students')->get();
    }

    /**
     * @desc create a grade
     * @param array $attributes
     * @return Grade
     * */
    public function create(array $attributes): Grade
    {
        $grade = Grade::create($attributes);

        return $grade;
    }

    /**
     * @desc get grade by id
     * @param int $id
     * @return Collection
     * */
    public function findById(int $id): Collection
    {
        return Grade::withCount('students')->where('id', '=', $id)->get();
    }

    /**
     * @desc get grade instance
     * @param Grade $grade
     * @return Collection
     * */
    public function get(Grade $grade): Collection
    {
        return Grade::withCount('students')->where('id', '=', $grade->id)->get();
    }

    /**
     * @desc update grade instance
     * @param Grade $grade
     * @param array $attributes
     * @return Grade
     * */
    public function update(Grade $grade, array $attributes): Grade
    {
        $grade->fill($attributes);

        if($grade->isDirty()){
            $grade->save();
        }

        return $grade;
    }

    /**
     * @desc delete grade
     * @param Grade $grade
     * @return Grade
     * */
    public function delete(Grade $grade): Grade
    {
        return $grade->delete();
    }

    /**
     * @desc restore a soft-deleted grade
     * @param int $id
     * @return Grade
     * */
    public function restore(int $id): Grade
    {
        $grade = Grade::withTrashed()->where('id', $request->input('id'))->first();
        $grade->restore();

        return $grade;
    }

    /**
     * @desc get available in a particular academic year
     * @param AcademicYear $academicYear
     * @return Collection
     * */
    public function getYearGrades(AcademicYear $academicYear): Collection
    {
        return Grade::whereHas('exams.semester', function($query) use ($academicYear){
                $query->where('academic_year_id', '=', $academicYear->id);
            })->get();
    }

    public function archive(): Collection
    {
        return Grade::onlyTrashed()->with('students')->get();
    }
}
