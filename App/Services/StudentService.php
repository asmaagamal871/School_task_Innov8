<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Student;
use App\Repositories\StudentRepository;

class StudentService
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }
    public function create(Student $student): Result
    {
        $errors = $this->validateForm($student);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $result = $this->studentRepository->create($student);
        if ($result) {
            return new Result(true, 'Student created successfully');
        }
        return new Result(false, 'Failed to create student');
    }

    public function getPaginatedList($page = 1)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $offset = ($page - 1) * $limit;
        $result = $this->studentRepository->getPaginatedList($offset);
        $total_records = $this->studentRepository->getTotalCount();
        $total_pages = ceil($total_records / $limit);
        return new Result(true, "Data retrieved successfully", [], ["students" => $result, "total_pages" => $total_pages, "current_page" => $page]);
    }

    public function edit(Student $student)
    {
        $errors = $this->validateForm($student);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $oldData = $this->studentRepository->getByID($student->student_id);
        $existingSubjects = array_column($oldData['subjects'], 'subject_id');
        $newSubjects = array_diff($student->subjects, $existingSubjects);
        $deletedSubjects = array_diff($existingSubjects, $student->subjects);
        $updateBasicInfo = $oldData['student_name'] !== $student->student_name ||
            $oldData['class_id'] !== $student->class_id;
        $saved = $this->studentRepository->edit($student, $newSubjects, $deletedSubjects, $updateBasicInfo);
        if ($saved) {
            return new Result(true, 'Student data updated successfully');
        }
        return new Result(false, 'Failed to update student');
    }

    public function getByID($id)
    {
        $student =  $this->studentRepository->getByID($id);
        if (!$student) {
            return new Result(false, 'Failed to get student data');
        }
        return new Result(true, 'Student data retrived successfully', [], $student);
    }

    public function delete(int $id)
    {
        $this->studentRepository->delete($id);
    }

    private function validateForm(Student $student)
    {

        $errors = [];
        if (empty($student->student_name)) {
            array_push($errors, "Name is required");
        }
        if (empty($student->class_id)) {
            array_push($errors, "Class is required");
        }
        if (count($student->subjects) == 0) {
            array_push($errors, "Subjects is required");
        }

        return $errors;
    }
}
