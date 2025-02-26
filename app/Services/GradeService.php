<?php

namespace App\Services;

use App\Interfaces\GradeRepositoryInterface;
use App\Models\Grade;

class GradeService
{
    public function __construct(
        protected GradeRepositoryInterface $gradeRepository
    ) {}

    /**
     * @desc index service
     * @return array
     * */
    public function index(): array
    {
        return [
            'grades' => $this->gradeRepository->findAll(),
        ];
    }

    /**
     * @desc create page service
     * @param array $attributes
     * @return Grade
     * */
    public function create(array $attributes): Grade
    {
        $grade = $this->gradeRepository->create($attributes);

        return $grade;
    }

    /**
     * @desc edit service
     * @param Grade $grade
     * @param array $attributes
     * @return Grade
     * */
    public function edit(Grade $grade, array $attributes): Grade
    {
        $grade = $this->gradeRepository->update($grade, $attributes);

        return $grade;
    }

    /**
     * @desc delete grade
     * @param Grade $grade
     * @return Grade
     * */
    public function delete(Grade $grade): boolean
    {
        $grade = $this->gradeRepository->delete($grade);

        return $grade;
    }

    /**
     * @desc restore grade
     * @param int $id
     * @return Grade
     * */
    public function restore(int $id): Grade
    {
        $grade = $this->gradeRepository->restore($id);

        return $grade;
    }
}
