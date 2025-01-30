<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentData;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StudentRequest;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = DB::table('students')
                ->join('grades', 'students.grade_id', 'grades.id')
                ->join('parents', 'students.parent_id', 'parents.id')
                ->select('students.*', 'grades.name as grade', 'parents.email', 'parents.phone_number', 'parents.address')
                ->get();

        return Inertia::render('Student/List', [
            "students" => $students,
            "grades" => Grade::all(),
            "parents" => ParentData::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Student/Create',[
            "parents" => ParentData::all()->makeHidden(['created_at', 'updated_at']),
            "grades" => Grade::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $validated = $request->validated();

        // find or create parent
        $parent = ParentData::firstOrCreate(
            [
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number']
            ],
            [
                'name' => $validated['parent_name'],
                'address' => $validated['address']
            ]
        );

        // find grade
        $grade = Grade::find((int)$validated['grade_id']);

        // create student
        $student = new Student;
        $student['studentId'] = $validated['studentId'];
        $student['name'] = $validated['name'];
        $student['parent_id'] = $parent->id;
        $student['grade_id'] = $grade->id;

        $student->save();

        return redirect(route('students.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
