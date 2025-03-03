<?php

namespace App\Interfaces;

use App\Models\Subject;

interface SubjectRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function find(Subject $subject);
    public function findById(int $id);
    public function update(Subject $subject, array $attributes);
    public function delete(Subject $subject);
    public function restore(int $id);
    public function archive();
}
