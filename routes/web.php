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
use App\Http\Controllers\ParentDashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\ParentMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect(route('students.index'));
})->middleware(['web', AdminMiddleware::class]);

Route::get('/dashboard', function (Request $request) {
    return redirect(route('students.index'));
})->middleware(['web', AdminMiddleware::class])->name('dashboard');

Route::get('/students/dashboard', [StudentController::class, 'dashboard'])->middleware(['web', StudentMiddleware::class])->name('studentDashboard');
Route::get('/parents/dashboard', [ParentDashboardController::class, 'index'])->middleware(['web', ParentMiddleware::class])->name('parentDashboard');

Route::get('/file/{filePath}', function ($filePath) {
    return response()->file($filePath);
})->middleware(['web', AdminMiddleware::class])->name('files');


Route::middleware(['web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::put('/grades/restore/', [GradeController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('grades.restore');

Route::resource('grades', GradeController::class)
    ->middleware(['web', AdminMiddleware::class]);

Route::put('/students/restore/', [StudentController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('students.restore');

Route::patch('/students/upgrade', [StudentController::class, 'upgrade'])
    ->middleware(['web', AdminMiddleware::class])
    ->name('students.upgrade');

Route::delete('/students/delete', [StudentController::class, 'deleteMany'])
    ->middleware(['web', AdminMiddleware::class])
    ->name('students.delete');

Route::get('/students/{student}/semesters/{semester}', [StudentController::class, 'resultsAggregate'])
    ->middleware(['web'])
    ->name('students.results');

Route::get('/students/{student}/years/{academicYear}', [StudentController::class, 'yearlyResults'])
    ->middleware(['web'])
    ->name('students.yearly-results');

Route::get('/students/{student}/years/{academicYear}/print', [PDFController::class, 'studentYearlyResults'])
    ->middleware(['web'])
    ->name('students.yearly-results.print');

Route::get('/students/{student}/semesters/{semester}/print', [PDFController::class, 'studentResults'])
    ->middleware(['web'])
    ->name('students.results.print');

Route::resource('students', StudentController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->middleware(['web', AdminMiddleware::class]);

Route::get('/years/{academicYear}/grades/{grade}/', [YearController::class, 'yearResults'])
    ->middleware(['web', AdminMiddleware::class])->name('years.results');

Route::get('/years/{academicYear}/grades/{grade}/print', [PDFController::class, 'gradeYearResults'])
    ->middleware(['web', AdminMiddleware::class])->name('years.results.print');

Route::resource('years', YearController::class)
    ->parameters([ 'years' => 'academicYear' ])
    ->middleware(['web', AdminMiddleware::class]);

Route::get('/semesters/{semester}/grades/{grade}/', [SemesterController::class, 'semesterResults'])
    ->middleware(['web', AdminMiddleware::class])->name('semesters.results');

Route::get('/semesters/{semester}/grades/{grade}/print', [PDFController::class, 'gradeSemesterResults'])
    ->middleware(['web', AdminMiddleware::class])->name('semesters.results.print');

Route::put('/semesters/restore/', [SemesterController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('semesters.restore');

Route::resource('semesters', SemesterController::class)
    ->middleware(['web', AdminMiddleware::class]);

Route::put('/subjects/restore/', [SubjectController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('subjects.restore');

Route::resource('subjects', SubjectController::class)
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    ->middleware(['web', AdminMiddleware::class]);

Route::put('/subjects/{subject}/exams/restore/', [ExamsController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('subjects.exams.restore');

Route::resource('subjects.exams', ExamsController::class)
    ->middleware(['web', AdminMiddleware::class]);

Route::put('/subjects/{subject}/exams/{exam}/results/restore/', [ResultController::class, 'restore'])
    ->middleware(['web', AdminMiddleware::class])->name('subjects.exams.results.restore');

Route::resource('subjects.exams.results', ResultController::class)
    ->middleware(['web', AdminMiddleware::class]);

Route::get('/archive', [ArchiveController::class, 'index'])->middleware(['web', AdminMiddleware::class])->name('archive');

Route::get('/archive/semesters', [ArchiveController::class, 'semesterArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.semesters');

Route::get('/archive/students', [ArchiveController::class, 'studentArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.students');

Route::get('/archive/subjects', [ArchiveController::class, 'subjectArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.subjects');

Route::get('/archive/exams', [ArchiveController::class, 'examsArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.exams');

Route::get('/archive/results', [ArchiveController::class, 'resultsArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.results');

Route::get('/archive/grades', [ArchiveController::class, 'gradeArchive'])->middleware(['web', AdminMiddleware::class])->name('archive.grades');

Route::get('/results', [ResultController::class, 'index'])->middleware(['web', AdminMiddleware::class])->name('results.index');

Route::get('/results/create', [ResultController::class, 'createMultiple'])->middleware(['web', AdminMiddleware::class])->name('results.create');

Route::post('/results', [ResultController::class, 'storeMultiple'])->middleware(['web', AdminMiddleware::class])->name('results.store');

Route::get('/exams/results', [ResultController::class, 'getExams'])->middleware(['web', AdminMiddleware::class])->name('results.byExam');

Route::put('/contacts', [ParentDashboardController::class, 'updateContacts'])->middleware(['web', ParentMiddleware::class])->name('contactsUpdate');
// Route::gett('/contacts')->middleware(['web', ParentMiddleware::class])->name('contactsEdit');

Route::get('/children/{student}', [ParentDashboardController::class, 'show'])->middleware(['web', ParentMiddleware::class])->name('child');

require __DIR__.'/auth.php';
