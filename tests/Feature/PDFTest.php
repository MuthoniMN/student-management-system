<?php

namespace Tests\Feature;

use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Student;
use App\Services\PDFService;
use App\Interfaces\ResultRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use PDF;

class PDFServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $resultRepositoryMock;
    protected $pdfService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resultRepositoryMock = Mockery::mock(ResultRepositoryInterface::class);
        $this->app->instance(ResultRepositoryInterface::class, $this->resultRepositoryMock);

        $this->pdfService = new PDFService($this->resultRepositoryMock);
    }

    public function testGradeSemester()
    {
        $grade = Grade::factory()->create();
        $semester = Semester::factory()->create();

        $this->resultRepositoryMock
            ->shouldReceive('getGradeSemesterResults')
            ->once()
            ->with($grade, $semester)
            ->andReturn(['mocked_result']);

        $result = $this->pdfService->gradeSemester($grade, $semester);

        $this->assertEquals(['mocked_result'], $result['results']);
        $this->assertEquals($semester->id, $result['semester']->id);
        $this->assertEquals($grade->id, $result['grade']->id);
    }

    public function testGradeYear()
    {
        $grade = Grade::factory()->create();
        $year = AcademicYear::factory()->create();

        $this->resultRepositoryMock
            ->shouldReceive('getGradeYearResults')
            ->once()
            ->with($grade, $year)
            ->andReturn(['mocked_result']);

        $result = $this->pdfService->gradeYear($grade, $year);

        $this->assertEquals(['mocked_result'], $result['results']);
        $this->assertEquals($year->id, $result['year']->id);
        $this->assertEquals($grade->id, $result['grade']->id);
    }

    public function testStudentSemester()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create();

        $this->resultRepositoryMock
            ->shouldReceive('getStudentSemesterResults')
            ->once()
            ->with($student, $semester)
            ->andReturn(['mocked_result']);

        $result = $this->pdfService->studentSemester($student, $semester);

        $this->assertEquals(['mocked_result'], $result['results']);
        $this->assertEquals($student->id, $result['id']);
        $this->assertEquals($student->studentId, $result['studentId']);
    }

    public function testStudentYear()
    {
        $student = Student::factory()->create();
        $year = AcademicYear::factory()->create();

        $this->resultRepositoryMock
            ->shouldReceive('getStudentYearResults')
            ->once()
            ->with($student, $year)
            ->andReturn(['mocked_result']);

        $this->resultRepositoryMock
            ->shouldReceive('getStudentYearRanks')
            ->once()
            ->with($student, $year)
            ->andReturn(['mocked_ranks']);

        $result = $this->pdfService->studentYear($student, $year);

        $this->assertEquals(['mocked_result'], $result['results']);
        $this->assertEquals(['mocked_ranks'], $result['ranks']);
        $this->assertEquals($student->id, $result['student']->id);
        $this->assertEquals($year->id, $result['year']->id);
        $this->assertContains('Mathematics', $result['subjects']);
    }

    public function testGradeSemesterResultsEndpoint()
    {
        $grade = Grade::factory()->create();
        $semester = Semester::factory()->create();

        $response = $this->get(route('semesters.results.print', [$semester, $grade]));

        $response->assertStatus(302);
    }

    public function testGradeYearResultsEndpoint()
    {
        $grade = Grade::factory()->create();
        $year = AcademicYear::factory()->create();

        $response = $this->get(route('years.results.print', [$year, $grade]));

        $response->assertStatus(302);
    }

    public function testStudentResultsEndpoint()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create();

        $response = $this->get(route('students.results.print', [$student, $semester]));

        $response->assertStatus(302);
    }

    public function testStudentYearlyResultsEndpoint()
    {
        $student = Student::factory()->create();
        $year = AcademicYear::factory()->create();

        $response = $this->get(route('students.yearly-results.print', [$student, $year]));

        $response->assertStatus(302);
    }
}
