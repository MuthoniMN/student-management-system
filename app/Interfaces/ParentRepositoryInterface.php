<?php

namespace App\Interfaces;
use App\Models\ParentData;

interface ParentRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function findById(int $id);
    public function get(ParentData $parent);
    public function update(int $id, array $attributes);
}
