<?php

namespace App\Repositories;

use App\Interfaces\ParentRepositoryInterface;
use App\Models\ParentData;
use Illuminate\Database\Eloquent\Collection;

class ParentRepository implements ParentRepositoryInterface
{
    /**
     * @desc create a new parent
     * @param array $attributes
     * @return ParentData
     * */
    public function create(array $attributes): ParentData
    {
        return ParentData::firstOrCreate(
            [
                'email' => $attributes['email'],
                'phone_number' => $attributes['phone_number']
            ],
            [
                'name' => $attributes['parent_name'],
                'address' => $attributes['address']
            ]
        );
    }

    /**
     * @desc get all parents
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return ParentData::all();
    }

    /**
     * @desc get parent by it
     * @param int $id
     * @return ParentData
     * */
    public function findById(int $id): ParentData
    {
        return ParentData::where('id', '=', $id)->get();
    }

    /**
     * @desc get parent
     * @param ParentData $parent
     * @return ParentData
     * */
    public function get(ParentData $parent): ParentData
    {
        return ParentData::where('id', '=', $parent->id)->get();
    }

    /**
     * @desc update parent instance
     * @param int $id
     * @param array $attributes
     * @return ParentData
     * */
    public function update(int $id, array $attributes): ParentData
    {
        $parent = ParentData::find($id);

        $parent->fill([
            'name' => $attributes['parent_name'],
            'email' => $attributes['email'],
            'phone_number' => $attributes['phone_number'],
            'address' => $attributes['address']
        ]);

        if($parent->isDirty()){
            $parent->save();
        }

        return $parent;
    }
}
