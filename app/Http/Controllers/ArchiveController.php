<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\ParentData;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Grade;
use App\Models\Semester;
use App\Services\ArchiveService;

class ArchiveController extends Controller
{
    public function __construct(
        protected ArchiveService $archiveService
    ) {}

    public function index(){
        return Inertia::render('Archive/Index');
    }

    public function semesterArchive(){
        $dependencies = $this->archiveService->semester();

        return Inertia::render('Archive/Semester', $dependencies);
    }

    public function studentArchive(){
        $dependencies = $this->archiveService->student();

        return Inertia::render('Archive/Student', $dependencies);
    }

    public function subjectArchive(){
        $dependencies = $this->archiveService->subject();

        return Inertia::render('Archive/Subject', $dependencies);
    }

    public function gradeArchive(){
        $dependencies = $this->archiveService->grade();

        return Inertia::render('Archive/Grade', $dependencies);
    }

    public function examsArchive(){
        $dependencies = $this->archiveService->exam();

        return Inertia::render('Archive/Exam', $dependencies);
    }

    public function resultsArchive(){
        $dependencies = $this->archiveService->result();

        return Inertia::render('Archive/Results', $dependencies);

    }
}
