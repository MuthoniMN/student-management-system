<?php

namespace App\Services;

use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Collection;

class YearService
{
    public function __construct(
        protected YearRepositoryInterface $yearRepository,
        protected ResultRepositoryInterface $resultRepository,
        protected GradeRepositoryInterface $gradeRepository
    ) {}

    /**
     * @desc index service
     * @return array
     * */
    public function index(): array
    {
        return [
            'years' => $this->yearRepository->findAll(),
        ];
    }

    /**
     * @desc create service
     * @param array $attributes
     * @return AcademicYear
     * */
    public function create(array $attributes): AcademicYear
    {
        $year = $this->yearRepository->create($attributes);

        return $year;
    }

    /**
     * @desc view service
     * @param AcademicYear $academicYear
     * @return array
     * */
    public function view(AcademicYear $academicYear): array
    {
        return [
            'year' => $academicYear,
            'grades' => $this->gradeRepository->getYearGrades($academicYear),
        ];
    }

    /**
     * @desc update academic year
     * @param AcademicYear $academicYear
     * @param array $attributes
     * @return AcademicYear
     * */
    public function update(AcademicYear $academicYear, array $attributes): AcademicYear
    {
        return $this->yearRepository->update($academicYear, $attributes);
    }

    /**
     * @desc delete academic year
     * @param AcademicYear $academicYear
     * @return boolean
     * */
    public function delete(AcademicYear $academicYear): bool
    {
        return $this->yearRepository->delete($academicYear);
    }

    /**
     * @desc restore deleted academic year
     * @param int $id
     * @return AcademicYear
     * */
    public function restore(int $id): AcademicYear
    {
        return $this->yearRepository->restore($id);
    }

    /**
     * @desc fetch academic year results for a specified grade
     * @param AcademicYear $academicYear
     * @param Grade $grade
     * @return array
     * */
    public function yearResults(AcademicYear $academicYear, Grade $grade): array
    {
        $results = $this->resultRepository->getGradeYearResults($grade, $academicYear);

        return [
            'results' => $results,
            'year' => $academicYear,
            'grade' => $grade
        ];
    }
}
