<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Interfaces\YearRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class YearRepository implements YearRepositoryInterface
{
    /**
     * @desc create a new academic year
     * @param array $attributes
     * @return AcademicYear
     * */
    public function create(array $attributes): AcademicYear
    {
        $year = AcademicYear::create($attributes);

        return $year;
    }

    /**
     * @desc get all academic years
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return AcademicYear::select('*')->orderByDesc('start_date')->get();
    }

    /**
     *  @desc find an academic year
     *  @param AcademicYear $academicYear
     *  @return AcademicYear
     * */
    public function find(AcademicYear $academicYear): AcademicYear
    {
        return AcademicYear::where('id', '=', $academicYear->id)->first();
    }

    /**
     *  @desc find an academic year by id
     *  @param int $id
     *  @return AcademicYear
     * */
    public function findById(int $id): AcademicYear
    {
        return AcademicYear::where('id', '=', $id)->first();
    }

    /**
     * @desc update an academic year
     * @param AcademicYear $academicYear
     * @param array $attributes
     * @return AcademicYear
     * */
    public function update(AcademicYear $academicYear, array $attributes): AcademicYear
    {
        $academicYear->fill($attributes);

        if($academicYear->isDirty()){
            $academicYear->save();
        }

        return $academicYear;
    }

    /**
     * @desc delete an academic year
     * @param AcademicYear $academicYear
     * @return AcademicYear
     * */
    public function delete(AcademicYear $academicYear): AcademicYear
    {
        return $academicYear->delete();
    }

    /**
     * @desc restore a deleted academic year
     * @param int $id
     * @return AcademicYear
     * */
    public function restore(int $id): AcademicYear
    {
        $year = AcademicYear::where('id', '=', $id)->first();
        $year->restore();

        return $year;
    }

}
