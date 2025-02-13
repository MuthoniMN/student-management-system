<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentData;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\UpgradeGradeRequest;
use App\Http\Requests\DeleteStudentsRequest;
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
                ->where('students.deleted_at', null)
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
        return Inertia::render('Student/Show', [
            'student' => $student,
            'grade' => $student->grade,
            'parent' => $student->parent,
            'semesters' => DB::table('results')
                ->join('exams', 'results.exam_id', 'exams.id')
                ->join('grades', 'exams.grade_id', 'grades.id')
                ->join('semesters', 'exams.semester_id', 'semesters.id')
                ->select('semesters.id', 'semesters.title', 'grades.name')
                ->where('results.student_id', $student->id)
                ->distinct()
                ->get(),
            'years' => DB::table('results')
                ->join('exams', 'results.exam_id', 'exams.id')
                ->join('grades', 'exams.grade_id', 'grades.id')
                ->join('semesters', 'exams.semester_id', 'semesters.id')
                ->join('academic_years', 'semesters.academic_year_id', 'academic_years.id')
                ->select('academic_years.*', 'grades.name as grade')
                ->where('results.student_id', $student->id)
                ->distinct()
                ->get(),
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return Inertia::render('Student/Edit', [
            'student' => $student,
            'parents' => ParentData::all(),
            'grades' => Grade::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        $validated = $request->validated();

        $parent = ParentData::find($student->parent->id);

        $parent->fill([
            'name' => $validated['parent_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address']
        ]);

        if($parent->isDirty()){
            $parent->save();
        }

        $student->name = $validated['name'];
        $student->studentId = $validated['studentId'];
        $student->grade_id = $validated['grade_id'];

        if($student->isDirty()){
            $student->save();
        }

        return redirect(route('students.show', $student));
    }

    /*
     * Update many student's grade
     * */
    public function upgrade(Request $request){
        $validated = $request->input('data');

        foreach ($validated['studentIds'] as $key) {
            $student = Student::find($key);

            $student['grade_id'] = $validated['grade_id'];

            $student->save();
        }

        return back()->with('update', 'Successfully updated!' );
    }

    /*
     * Mass delete students
     * */
    public function deleteMany(Request $request){
        $validated = $request->input('studentIds');

        Student::destroy($validated);

        return back()->with('delete', 'Successfully deleted!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect(route('students.index'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $data = $request->input('data');
        $students = $data['studentIds'];
        foreach ($students as $id) {
            $student = Student::withTrashed()->where('id', $id)->first();
            $student->restore();
        }

        return redirect(route('students.index'))->with('update', 'Student restored!');
    }

    private function getGrade($num) {
        if($num > 80){
            return 'A';
        } else if($num > 65){
            return 'B';
        } else if($num > 50){
            return 'C';
        } else if($num > 40){
            return 'D';
        }else{
            return 'E';
        }
    }

    private function getSemesterResults(Student $student, Semester $semester){
        $date = date_create($semester->start_date);
        $today = date_create(now());
        $diff = date_diff($today, $date);
        $diff = explode(' ', $diff->format('%R %y'));
        $grade = ($diff[0] == '-' ? $student->grade_id - ((int)$today->format('y') - (int)$date->format('y')) : $student->grade_id + (int)$diff[1]);
        $results = DB::table('students as s')
            ->join('results as r', 's.id', '=', 'r.student_id')
            ->join('exams as e', 'r.exam_id', '=', 'e.id')
            ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
            ->select(
                's.id as id',
                's.studentId as student_id',
                's.name as student_name',
                'sub.title as subject_name',
                'e.semester_id',
                's.grade_id',
                DB::raw('SUM(r.result) as total_marks'),
                DB::raw('ROUND(AVG(r.result)) as average_marks'),
                DB::raw('RANK() OVER (PARTITION BY sub.id ORDER BY SUM(r.result) DESC) as rank')
            )
            ->where('e.semester_id', $semester->id)
            ->where('e.grade_id', $grade)
            ->groupBy('s.id', 's.name', 'sub.title', 'sub.id', 'e.semester_id', 's.grade_id')
            ->orderBy('total_marks')
            ->get();

        $totalResults = $results->where('id', $student->id)->sum('average_marks');
        $position = 1;
        $res = [];
        $subjects = [];

        foreach ($results as $key) {
            if($key->id != $student->id && !array_key_exists($key->id, $res)){
                $studentResults = round($results->where('id', $key->id)->sum('average_marks'));
                $res[$key->id] = $studentResults;
                if($studentResults > $totalResults){
                    $position += 1;
                }
            }
        }



        foreach ($results->where('id', $student->id) as $key) {
            $subjects[] = [
                'subject_name' => $key->subject_name,
                'average_marks' => $key->average_marks,
                'grade' => $this->getGrade($key->average_marks)
            ];
        }

        return [
            'id' => $student->id,
            'studentId' => $student->studentId,
            'name' => $student->name,
            'total_marks' => $totalResults,
            'subjects' => $subjects,
            'position' => $position
        ];

    }

    public function resultsAggregate(Student $student, Semester $semester){
        $results = $this->getSemesterResults($student, $semester);
        return Inertia::render('Student/Results', [
            'results' => $results,
            'semester' => $semester
        ]);
    }

    public function yearlyResults(Student $student, AcademicYear $academicYear){
        $semesters = $academicYear->semesters()->get();
        $results = [];
        $compiled = [];
        $ranks=[];
        foreach ($semesters as $semester) {
            $results[$semester->title] = $this->getSemesterResults($student, $semester);
        }

        foreach ($results as $key => $sem) {
            foreach ($sem['subjects'] as $result) {
                $saved = array_key_exists($result['subject_name'], $compiled) ? $compiled[$result['subject_name']] : [];
                $compiled[$result['subject_name']] = [
                    ...$saved,
                    $key => $result['average_marks'],
                    "{$key}_grade" => $result['grade'],
                    'subject' => $result['subject_name']
                ];
            }
            $ranks[$key] = $sem['position'];
        }

        foreach ($compiled as $key => $sub) {
            $avg = round(($sub['Semester 1'] + $sub['Semester 2']) / 2);
            $avg_grade = $this->getGrade($avg);
            $sub = [ ...$sub, 'average' => $avg, 'grade' => $avg_grade];
            $compiled[$key] = $sub;
        }
        return Inertia::render('Student/YearlyResults', [
            'results' => $compiled,
            'ranks' => $ranks,
            'student' => $student,
            'year' => $academicYear
        ]);
    }


}
