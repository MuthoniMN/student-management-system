<?php

namespace App\Services;

use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Subject;
use App\Models\Exam;

class ExamService {
    public function __construct(
        protected ExamRepositoryInterface $examRepository,
        protected SubjectRepositoryInterface $subjectRepository,
        protected SemesterRepositoryInterface $semesterRepository,
        protected GradeRepositoryInterface $gradeRepository,
        protected StudentRepositoryInterface $studentRepository,
        protected ResultRepositoryInterface $resultRepository
    ) {}

    public function create(Subject $subject){
        return [
           'subject' => $subject,
           'semesters' => $this->semesterRepository->findAll(),
           'grades' => $this->gradeRepository->findAll()
        ];
    }

    public function store(Subject $subject, array $attributes, $file=null){
        $exam = $this->examRepository->create(['subject_id' => $subject->id,  ...$attributes]);

        if($file){
            $path = $request->file('file')->storeAs("exams", "{$subject->title} {$exam->title} - {$exam->semester->title} {$exam->exam_date}.{$request->file('file')->getClientOriginalExtension()}", 'public');

            $exam->file = $path;
        }

        if($exam->isDirty()){
            $exam->save();
        }

        return $exam;
    }

    public function show(Subject $subject, Exam $exam){
        return [
            'exam' => $exam,
            'subject' => $subject,
            'grades' => $this->gradeRepository->findAll(),
            'semesters' => $this->semesterRepository->findAll(),
            'students' => $this->studentRepository->findAll(),
            'results' => $this->resultRepository->findExamResults($exam),
        ];
    }

    public function edit(Subject $subject, Exam $exam){
        return [
           'subject' => $subject,
            'exam' => $exam,
           'semesters' => $this->semesterRepository->findAll(),
           'grades' => $this->gradeRepository->findAll()
        ];
    }

    public function update(Exam $exam, array $attributes, $file=null){
        $exam = $this->examRepository->update($exam, $attributes);

        if($file){
            $path = $request->file('file')->storeAs("exams", "{$subject->title} {$exam->title} - {$exam->semester->title} {$exam->exam_date}.{$request->file('file')->getClientOriginalExtension()}", 'public');

            $exam->file = $path;
            $exam->save();
        }

        return $exam;
    }

    public function delete(Exam $exam){
        return $exam->delete();
    }

    public function restore(int $id){
        $exam = $this->examRepository->restore($id);

        return $exam;
    }
}
