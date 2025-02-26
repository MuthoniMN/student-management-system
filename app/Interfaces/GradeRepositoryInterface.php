<?php

namespace App\Interfaces;
use App\Models\Grade;

interface GradeRepositoryInterface
{
    public function findAll();
    public function get(Grade $grade);
    public function findById(int $id);
    public function create(array $attributes);
    public function update(Grade $grade, array $attributes);
    public function delete(Grade $grade);
    public function restore(int $id);
}
