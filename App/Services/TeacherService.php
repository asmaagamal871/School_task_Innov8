<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;

class TeacherService
{
    private $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }
    public function create(Teacher $teacher): Result
    {
        $errors = $this->validateForm($teacher);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $result = $this->teacherRepository->create($teacher);
        if ($result) {
            return new Result(true, 'Teacher created successfully');
        }
        return new Result(false, 'Failed to create teacher');
    }

    public function getPaginatedList($page = 1)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $offset = ($page - 1) * $limit;
        $result = $this->teacherRepository->getPaginatedList($offset);
        $total_records = $this->teacherRepository->getTotalCount();
        $total_pages = ceil($total_records / $limit);
        return new Result(true, "Data retrieved successfully", [], ["teachers" => $result, "total_pages" => $total_pages, "current_page" => $page]);
    }

    public function edit(Teacher $teacher)
    {
        $errors = $this->validateForm($teacher);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $oldData = $this->teacherRepository->getByID($teacher->teacher_id);
        $existingSubjects = array_column($oldData['subjects'], 'subject_id');
        $newSubjects = array_diff($teacher->subjects, $existingSubjects);
        $deletedSubjects = array_diff($existingSubjects, $teacher->subjects);
        $updateBasicInfo = $oldData['teacher_name'] !== $teacher->teacher_name;
        $saved = $this->teacherRepository->edit($teacher, $newSubjects, $deletedSubjects, $updateBasicInfo);
        if ($saved) {
            return new Result(true, 'Teacher data updated successfully');
        }
        return new Result(false, 'Failed to update teacher');
    }

    public function getByID($id)
    {
        $teacher =  $this->teacherRepository->getByID($id);
        if (!$teacher) {
            return new Result(false, 'Failed to get teacher data');
        }
        return new Result(true, 'Teacher data retrived successfully', [], $teacher);
    }

    public function delete(int $id)
    {
        $this->teacherRepository->delete($id);
    }

    private function validateForm(Teacher $teacher)
    {
        $errors = [];
        if (empty($teacher->teacher_name)) {
            array_push($errors, "Name is required");
        }
        if (count($teacher->subjects) == 0) {
            array_push($errors, "Subjects is required");
        }

        return $errors;
    }
}
