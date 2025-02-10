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


Route::resource('students', StudentController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('years', YearController::class)
    ->parameters([ 'years' => 'academicYear' ])
    ->middleware(['auth', 'verified']);

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

require __DIR__.'/auth.php';
