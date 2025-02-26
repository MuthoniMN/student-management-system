<?php

namespace App\Interfaces;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Result;

interface StudentRepositoryInterface
{
    public function findAll();
    public function get(Student $student);
    public function findById(int $id);
    public function update(Student $student, array $attributes);
    public function upgrade(array $attribute, Grade $grade);
    public function create(array $attributes);
    public function delete(Student $student);
    public function deleteMany(array $attributes);
    public function restore(int $id);
    public function restoreMany(array $attributes);
}
