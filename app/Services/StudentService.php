<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ParentRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;

class StudentService
{
    public function __construct(
        protected StudentRepositoryInterface $studentRepository,
        protected ResultRepositoryInterface $resultRepository,
        protected GradeRepositoryInterface $gradeRepository,
        protected ParentRepositoryInterface $parentRepository,
        protected SemesterRepositoryInterface $semesterRepository,
        protected YearRepositoryInterface $yearRepository,
    ){}

    /**
     * @desc store student service
     * @param array $data
     * @return Student
     * */
    public function store(array $data): Student
    {
        $parent = $this->parentRepository->create([
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'name' => $data['name'],
            'address' => $data['address']
        ]);

        $grade = $this->gradeRepository->findById((int)$data['grade_id']);

        $student = $this->studentRepository->create($data);

        return $student;
    }

    /**
     * @desc index service
     * @return array */
    public function getAll(): array
    {
        $students = $this->studentRepository->findAll();
        $parents = $this->parentRepository->findAll();
        $grades = $this->gradeRepository->findAll();

        return [
            'students' => $students,
            'parents'=> $parents,
            'grades' => $grades
        ];
    }

    /**
     * @desc create page service
     * @return array
     * */
    public function create(): array
    {
        return [
            'parents' => $this->parentRepository->findAll(),
            'grades' => $this->gradeRepository->findAll()
        ];
    }

    /**
     * @desc display student service
     * @param Student $student
     * @return array
     * */
    public function getStudent(Student $student): array
    {
        return [
            'student' => $this->studentRepository->get($student),
            'grade' => $this->gradeRepository->get($student->grade),
            'parent' => $this->parentRepository->get($student->parent),
            'semesters' => $this->semesterRepository->getStudentSemesters($student),
            'years' => $this->yearRepository->getStudentYears($student)
        ];
    }

    /**
     * @desc edit page service
     * @param Student $student
     * @return array
     * */
    public function edit(Student $student)
    {
        return [
            'student' => $this->studentRepository->get($student),
            'parents' => $this->parentRepository->findAll(),
            'grades' => $this->gradeRepository->findAll(),
        ];
    }

    /**
     * @desc update service
     * @param Student $student
     * @param array $data
     * @return Student
     * */
    public function update(Student $student, array $data): Student
    {
        $parent = $this->parentRepository->update($student->parent->id, [
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'name' => $data['name'],
            'address' => $data['address']
        ]);

        $student = $this->studentRepository->update($student, $data);

        return $student;
    }

    /**
     * @desc upgrade service
     * @param Student $student
     * @param array $data
     * @return array
     * */
    public function upgrade(array $data)
    {
        $grade = $this->gradeRepository->findById($data['grade_id']);

        $students = $this->studentRepository->upgrade($data['studentIds']);

        return $students;
    }

    /**
     * @desc delete service
     * @param Student $student
     * @return Student
     * */
    public function delete(Student $student): bool
    {
        return $this->studentRepository->delete($student);
    }

    /**
     * @desc delete many service
     * @param array $students
     * @return array
     * */
    public function deleteMany(array $students)
    {
        return $this->studentRepository->deleteMany();
    }

    /**
     * @desc restore service
     * @param int $id
     * @return Student
     * */
    public function restoreStudent(int $id): Student
    {
        return $this->studentRepository->restore($id);
    }

    /**
     * @desc restore many service
     * @param array $students
     * @return array
     * */
    public function restoreMany(array $students){
        return $this->studentRepository->restoreMany($students);
    }

    /**
    * @desc get student's semester results service
    * @param Student $student
    * @param Semester $semester
    * @return array
    * */
    public function getSemesterResults(Student $student, Semester $semester): array
    {
        $results = $this->resultRepository->getStudentSemesterResults($student, $semester);
        $ranks = $this->resultRepository->getStudentSemesterRanks($student, $semester);
        $rank = $ranks->search(fn($r, $key) => $r['id'] == $student->studentId);

        return [
            'id' => $student->id,
            'name' => $student->name,
            'studentId' => $student->studentId,
            'results' => [
                'subjects' => current($results)['subjects'],
                'total'=> current($results)['total'],
                'rank' => $ranks[$rank]['rank']
            ],
        ];
    }

    /**
     * @desc get student's yearly results service
     * @param Student $student
     * @param AcademicYear $academicYear
     * @return array
     * */
    public function getYearResults(Student $student, AcademicYear $academicYear): array
    {
        $results = $this->resultRepository->getStudentYearResults($student, $academicYear);

        $ranks = $this->resultRepository->getStudentYearRanks($student, $academicYear);

        return [
            'yearResults' => $results,
            'ranks' => $ranks,
            'student' => $student,
            'year' => $academicYear
        ];
    }
}
