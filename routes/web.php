<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\PDFController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect(route('students.index'));
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return redirect(route('students.index'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/file/{filePath}', function ($filePath) {
    return response()->file($filePath);
})->middleware(['auth', 'verified'])->name('files');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::put('/grades/restore/', [GradeController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('grades.restore');

Route::resource('grades', GradeController::class)
    ->middleware(['auth', 'verified']);

Route::put('/students/restore/', [StudentController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('students.restore');

Route::patch('/students/upgrade', [StudentController::class, 'upgrade'])
    ->middleware(['auth', 'verified'])
    ->name('students.upgrade');

Route::delete('/students/delete', [StudentController::class, 'deleteMany'])
    ->middleware(['auth', 'verified'])
    ->name('students.delete');

Route::get('/students/{student}/semesters/{semester}', [StudentController::class, 'resultsAggregate'])
    ->middleware(['auth', 'verified'])
    ->name('students.results');

Route::get('/students/{student}/years/{academicYear}', [StudentController::class, 'yearlyResults'])
    ->middleware(['auth', 'verified'])
    ->name('students.yearly-results');

Route::get('/students/{student}/years/{academicYear}/print', [PDFController::class, 'studentYearlyResults'])
    ->middleware(['auth', 'verified'])
    ->name('students.yearly-results.print');

Route::get('/students/{student}/semesters/{semester}/print', [PDFController::class, 'studentResults'])
    ->middleware(['auth', 'verified'])
    ->name('students.results.print');

Route::resource('students', StudentController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::get('/years/{academicYear}/grades/{grade}/', [YearController::class, 'yearResults'])
    ->middleware(['auth', 'verified'])->name('years.results');

Route::get('/years/{academicYear}/grades/{grade}/print', [PDFController::class, 'gradeYearResults'])
    ->middleware(['auth', 'verified'])->name('years.results.print');

Route::resource('years', YearController::class)
    ->parameters([ 'years' => 'academicYear' ])
    ->middleware(['auth', 'verified']);

Route::get('/semesters/{semester}/grades/{grade}/', [SemesterController::class, 'semesterResults'])
    ->middleware(['auth', 'verified'])->name('semesters.results');

Route::get('/semesters/{semester}/grades/{grade}/print', [PDFController::class, 'gradeSemesterResults'])
    ->middleware(['auth', 'verified'])->name('semesters.results.print');

Route::put('/semesters/restore/', [SemesterController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('semesters.restore');

Route::resource('semesters', SemesterController::class)
    ->middleware(['auth', 'verified']);

Route::put('/subjects/restore/', [SubjectController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('subjects.restore');

Route::resource('subjects', SubjectController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::put('/subjects/{subject}/exams/restore/', [ExamsController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('subjects.exams.restore');

Route::resource('subjects.exams', ExamsController::class)
    ->middleware(['auth', 'verified']);

Route::put('/subjects/{subject}/exams/{exam}/results/restore/', [ResultController::class, 'restore'])
    ->middleware(['auth', 'verified'])->name('subjects.exams.results.restore');

Route::resource('subjects.exams.results', ResultController::class)
    ->middleware(['auth', 'verified']);

Route::get('/archive', [ArchiveController::class, 'index'])->middleware(['auth', 'verified'])->name('archive');

Route::get('/archive/semesters', [ArchiveController::class, 'semesterArchive'])->middleware(['auth', 'verified'])->name('archive.semesters');

Route::get('/archive/students', [ArchiveController::class, 'studentArchive'])->middleware(['auth', 'verified'])->name('archive.students');

Route::get('/archive/subjects', [ArchiveController::class, 'subjectArchive'])->middleware(['auth', 'verified'])->name('archive.subjects');

Route::get('/archive/exams', [ArchiveController::class, 'examsArchive'])->middleware(['auth', 'verified'])->name('archive.exams');

Route::get('/archive/results', [ArchiveController::class, 'resultsArchive'])->middleware(['auth', 'verified'])->name('archive.results');

Route::get('/archive/grades', [ArchiveController::class, 'gradeArchive'])->middleware(['auth', 'verified'])->name('archive.grades');

Route::get('/results', [ResultController::class, 'index'])->middleware(['auth', 'verified'])->name('results.index');

Route::get('/results/create', [ResultController::class, 'createMultiple'])->middleware(['auth', 'verified'])->name('results.create');

Route::post('/results', [ResultController::class, 'storeMultiple'])->middleware(['auth', 'verified'])->name('results.store');

Route::get('/exams/results', [ResultController::class, 'getExams'])->middleware(['auth', 'verified'])->name('results.byExam');

require __DIR__.'/auth.php';
