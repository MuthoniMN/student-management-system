<?php

namespace App\Interfaces;

use App\Models\Subject;
use App\Models\Exam;

interface ExamRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function findBySubject(Subject $subject);
    public function findById(int $id);
    public function find(Exam $exam);
    public function update(Exam $exam, array $attributes);
    public function delete(Exam $exam);
    public function restore(int $id);
    public function archive();
}
