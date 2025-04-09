<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentData;
use App\Models\Student;
use Inertia\Inertia;
use App\Services\StudentService;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ContactUpdateRequest;
use App\Interfaces\ParentRepositoryInterface;

class ParentDashboardController extends Controller
{
    public function __construct(
        protected StudentService $studentService,
        protected ParentRepositoryInterface $parentRepository,
    ){}
    // dashboard
    public function index(Request $request){
        $parent = $request->user()->parent;
        return Inertia::render('Parent/Dashboard', [
            'parent' => $parent,
            'children' => Student::with('grade')->where('parent_id', '=', $parent->id)->get()
        ]);
    }

    public function show(Student $student){
        Gate::authorize('view', $student);
        $dependencies = $this->studentService->getStudent($student);
        return Inertia::render('Student/Dashboard', $dependencies);
    }

    public function updateContacts(ContactUpdateRequest $request, ParentData $parent){
        $validated = $request->validated();

        $parent = $this->parentRepository->update($parent->id, $validated);

        return redirect(route('parentDashboard'))->with('update', 'Successfully updated!' );
    }
}
