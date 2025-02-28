<?php

namespace App\Services;

use App\Models\Subject;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;

class SubjectService
{
    public function __construct(
        protected SubjectRepositoryInterface $subjectRepository,
        protected ExamRepositoryInterface $examRepository,
        protected SemesterRepositoryInterface $semesterRepository,
        protected GradeRepositoryInterface $gradeRepository,
    ) {}

    public function index(): array
    {
        return [
            'subjects' => $this->subjectRepository->findAll(),
        ];
    }

    public function create(array $attributes): Subject
    {
        $subject = $this->subjectRepository->create($attributes);
        return $subject;
    }

    public function show(Subject $subject): array
    {
        $exams = $this->examRepository->findBySubject($subject);

        return [
            'subject' => $subject,
            'exams' => $exams,
            'grades' => $this->gradeRepository->findAll(),
            'semesters' => $this->semesterRepository->findAll()
        ];
    }

    public function update(Subject $subject, array $attributes): Subject
    {
        $subject = $this->subjectRepository->update($subject, $attributes);

        return $subject;
    }

    public function delete(Subject $subject): boolean
    {
        return $this->subjectRepository->delete($subject);
    }

    public function restore(int $id): Subject
    {
        $subject = $this->subjectRepository->restore($id);

        return $subject;
    }
}
