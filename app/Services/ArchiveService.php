<?php

namespace App\Services;

use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\ParentRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;

class ArchiveService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository,
        protected SubjectRepositoryInterface $subjectRepository,
        protected SemesterRepositoryInterface $semesterRepository,
        protected YearRepositoryInterface $yearRepository,
        protected GradeRepositoryInterface $gradeRepository,
        protected StudentRepositoryInterface $studentRepository,
        protected ParentRepositoryInterface $parentRepository,
        protected ResultRepositoryInterface $resultRepository
    ) {}

    public function semester(){
        return [
            'years' => $this->yearRepository->findAll(),
            'semesters' => $this->semesterRepository->archive()
        ];
    }

    public function student(){
        return [
            'students' => $this->studentRepository->archive(),
            'grades' => $this->gradeRepository->findAll(),
            'parents' => $this->parentRepository->findAll()
        ];
    }

    public function subject(){
        return [
            'grades' => $this->gradeRepository->findAll(),
            'subjects' => $this->subjectRepository->archive()
        ];
    }

    public function grade(){
        return [
            'grades' => $this->gradeRepository->archive()
        ];
    }

    public function exam(){
        return [
            'exams' => $this->examRepository->archive(),
            'grades' => $this->gradeRepository->findAll(),
            'semesters' => $this->semesterRepository->findAll()
        ];
    }

    public function result(){
        return [
            'grades' => $this->gradeRepository->findAll(),
            'semesters' => $this->semesterRepository->findAll(),
            'subjects' => $this->subjectRepository->findAll(),
            'students' => $this->studentRepository->findAll(),
            'results' => $this->resultRepository->archive()
        ];
    }
}
