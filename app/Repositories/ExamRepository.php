<?php

namespace App\Repositories;

use App\Interfaces\ExamRepositoryInterface;
use App\Models\Exam;
use App\Models\Subject;

class ExamRepository implements ExamRepositoryInterface
{
    public function create(array $attributes){
        $exam = Exam::create($attributes);

        return $exam;
    }

    public function findAll(){
        return Exam::all();
    }

    public function findBySubject(Subject $subject){
        $exams = Exam::with([
                'semester',
                'semester.year',
                'grade',
                'subject'
        ])->where('subject_id', $subject->id)->orderBy('exams.created_at', 'asc')
               ->get();

        return $exams;
    }

    public function findById(int $id){
        return Exam::with('subject', 'grade', 'semester', 'semester.year')->where('id', '=', $id)->first();
    }

    public function find(Exam $exam){
        return Exam::with('subject', 'grade', 'semester', 'semester.year')->where('id', '=', $exam->id)->first();
    }

    public function update(Exam $exam, array $attributes){
        $exam->fill($attributes);

        if($exam->isDirty()){
            $exam->save();
        }

        return $exam;
    }

    public function delete(Exam $exam){
        return $exam->delete();
    }

    public function restore(int $id){
        $exam = Exam::where('id', '=', $id)->first();
        $exam->restore();

        return $exam;
    }
}
