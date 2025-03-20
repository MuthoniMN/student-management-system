<?php

namespace App\Services;

use App\Repositories\ResultRepository;
use App\Repositories\ExamRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SemesterRepository;
use App\Repositories\YearRepository;
use App\Repositories\StudentRepository;
use App\Repositories\GradeRepository;
use App\Models\Exam;
use App\Models\Result;

class ResultService
{
    public function __construct(
        protected ExamRepository $examRepository,
        protected SubjectRepository $subjectRepository,
        protected SemesterRepository $semesterRepository,
        protected YearRepository $yearRepository,
        protected StudentRepository $studentRepository,
        protected GradeRepository  $gradeRepository,
        protected ResultRepository  $resultRepository
    ) {}

    public function index(): array
    {
        return [
            'exam_results' => $this->resultRepository->getResults(),
            'years' => $this->yearRepository->findAll(),
            'grades' => $this->gradeRepository->findAll()
        ];
    }

    public function create(Exam $exam): array
    {
        return [
            'subject' => $exam->subject,
            'exam' => $this->examRepository->find($exam),
            'students' => $this->studentRepository->findAll()
        ];
    }

    public function store(Exam $exam, $attributes): Result
    {
        $attributes['exam_id'] = $exam->id;
        return $this->resultRepository->create($attributes);
    }

    public function createMany()
    {
        return [
            'semesters' => $this->semesterRepository->findAll(),
            'subjects' => $this->subjectRepository->findAll(),
            'students' => $this->studentRepository->findAll(),
            'grades' => $this->gradeRepository->findAll(),
            'exams' => $this->examRepository->findAll()
        ];
    }

    public function storeMany(array $attributes){
        return DB::table('results')->insert($results);
    }

    public function edit(Result $result): array
    {
        return [
            'subject' => $result->exam->subject,
            'exam' => $this->examRepository->findById($result->exam_id),
            'result' => $this->resultRepository->get($result),
            'students' => $this->studentRepository->findAll()
        ];
    }

    public function update(Result $result, array $attributes): Result
    {
        $result->fill($attributes);

        if($result->isDirty()){
            $result->save();
        }

        return $result;
    }

    public function delete(Result $result): bool {
        return $this->resultRepository->delete($result);
    }

    public function restore(int $id): Result
    {
        $result = Result::onlyTrashed()->where('id', $id)->first();
        $result->restore();

        return $result;
    }
}
