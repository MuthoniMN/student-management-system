<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Interfaces\ResultRepositoryInterface;
use App\Repositories\ResultRepository;
use App\Interfaces\GradeRepositoryInterface;
use App\Repositories\GradeRepository;
use App\Interfaces\ParentRepositoryInterface;
use App\Repositories\ParentRepository;
use App\Interfaces\SemesterRepositoryInterface;
use App\Repositories\SemesterRepository;
use App\Interfaces\YearRepositoryInterface;
use App\Repositories\YearRepository;
use App\Interfaces\SubjectRepositoryInterface;
use App\Repositories\SubjectRepository;
use App\Interfaces\ExamRepositoryInterface;
use App\Repositories\ExamRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use App\Models\Student;
use App\Policies\StudentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interfaces to their implementations
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(ResultRepositoryInterface::class, ResultRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(ParentRepositoryInterface::class, ParentRepository::class);
        $this->app->bind(YearRepositoryInterface::class, YearRepository::class);
        $this->app->bind(SemesterRepositoryInterface::class, SemesterRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
    }
}
