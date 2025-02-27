<?php

namespace App\Interfaces;

use App\Models\Semester;

interface SemesterRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function find(Semester $semester);
    public function findById(int $id);
    public function update(Semester $semester, array $attributes);
    public function delete(Semester $semester);
    public function restore(int $id);
}
