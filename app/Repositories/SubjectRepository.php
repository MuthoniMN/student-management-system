<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Interfaces\SubjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubjectRepository implements SubjectRepositoryInterface
{
    /**
     * @desc create subject
     * @param array $attributes
     * @return Subject
     * */
    public function create(array $attributes): Subject
    {
        return Subject::create($attributes);
    }

    /**
     * @desc get all subjects
     * @return Collection*/
    public function findAll(): Collection
    {
        return Subject::all();
    }

    /**
     * @desc find a subject instance
     * @param Subject $subject
     * @return Subject
     * */
    public function find(Subject $subject): Subject
    {
        return Subject::where('id', '=', $subject->id)->first();
    }

    /**
     * @desc gets a subject instance by id
     * @param int $id
     * @return Subject
     * */
    public function findById(int $id): Subject
    {
        return Subject::where('id', '=', $id)->first();
    }

    /**
     * @desc update a subject
     * @param Subject $subject
     * @param array $attributes
     * @return Subject
     * */
    public function update(Subject $subject, array $attributes): Subject
    {
        $subject->fill($attributes);

        if($subject->isDirty()){
            $subject->save();
        }

        return $subject;
    }

    /**
     * @desc delete a subject instance
     * @param Subject $subject
     * @return bool
     * */
    public function delete(Subject $subject): bool
    {
        return $subject->delete();
    }

    /**
     * @desc restores a deleted subject
     * @param int $id
     * @return Subject
     * */
    public function restore(int $id): Subject
    {
        $subject = Subject::onlyTrashed()->where('id', '=', $id)->first();
        $subject->restore();

        return $subject;
    }

}
