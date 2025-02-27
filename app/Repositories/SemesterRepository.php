<?php

namespace App\Repositories;

use App\Models\Semester;
use App\Models\Grade;
use App\Interfaces\SemesterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SemesterRepository implements SemesterRepositoryInterface
{
    /**
     * @desc create a semester
     * @param array $attributes
     * @return Semester
     * */
    public function create(array $attributes): Semester
    {
        $semester = Semester::create($attributes);

        return $semester;
    }

    /**
     * @desc get all semesters
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return Semester::with('year')->orderByDesc('start_date')->get();
    }

    /**
     * @desc get a semester instance
     * @param Semester $semester
     * @return Semester
     * */
    public function find(Semester $semester): Semester
    {
        return Semester::where('id', '=', $semester->id)->first();
    }

    /**
     * @desc get semester by id
     * @param int $id
     * @return Semester
     * */
    public function findById(int $id): Semester
    {
        return Semester::where('id', '=', $id)->first();
    }

    /**
     * @desc update semester
     * @param Semester $semester
     * @param array $attributes
     * @return Semester
     * */
    public function update(Semester $semester, array $attributes): Semester
    {
        $semester->fill($attributes);

        if($semester->isDirty()){
            $semester->save();
        }

        return $semester;
    }

    /**
     * @desc delete semester
     * @param Semester $semester
     * @return Semester
     * */
    public function delete(Semester $semester): boolean
    {
        return $semester->delete();
    }

    /**
     * @desc restore deleted semester
     * @param int $id
     * @return Semester
     * */
    public function restore(int $id): Semester
    {
        $sem = Semester::onlyTrashed()->where('id', '=', $id)->first();
        $sem->restore();

        return $sem;
    }

}
