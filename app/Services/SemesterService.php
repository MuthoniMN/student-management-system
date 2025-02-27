<?php

namespace App\Services;

use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Grade;

class SemesterService
{
    public function __construct(
        protected SemesterRepositoryInterface $semesterRepository,
        protected YearRepositoryInterface $yearRepository,
        protected GradeRepositoryInterface $gradeRepository,
        protected ResultRepositoryInterface $resultRepository
    ) {}

    public function index(): array
    {
        return [
            'years' => $this->yearRepository->findAll(),
            'semesters' => $this->semesterRepository->findAll(),
        ];
    }

    public function create(): array
    {
        return [
            'years' => $this->yearRepository->findAll(),
        ];
    }

    public function store(array $attributes): Semester
    {
        $semester = Semester::create($attributes);

        return $semester;
    }

    public function show(Semester $semester): array
    {
        return [
            'semester' => $semester,
            'grades' => $this->gradeRepository->getYearGrades($semester->year)
        ];
    }

    public function edit(Semester $semester): array
    {
        return [
            'semester' => $semester,
            'years' => $this->yearRepository->findAll()
        ];
    }

    public function update(Semester $semester, array $attributes): Semester
    {
        $semester->fill($attributes);

        if($semester->isDirty()){
            $semester->save();
        }

        return $semester;
    }

    public function delete(Semester $semester): boolean
    {
        return $semester->delete();
    }

    public function restore(int $id): Semester
    {
        $semester = Semester::withTrashed()->where('id', $id)->first();
        $semester->restore();

        return $semester;
    }

    public function semesterResults(Semester $semester, Grade $grade): array
    {
        $results = $this->resultRepository->getGradeSemesterResults($grade, $semester);

        return [
            'results' => $results,
            'semester' => $semester,
            'grade' => $grade
        ];
    }
}
