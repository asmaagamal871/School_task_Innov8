<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Result;
use App\Repositories\ClassRepository;
use App\Repositories\StudentRepository;

class ClassService
{
    private $classRepository;
    private $studentRepository;

    public function __construct(ClassRepository $classRepository, StudentRepository $studentRepository)
    {
        $this->classRepository = $classRepository;
        $this->studentRepository = $studentRepository;
    }


    public function create(ClassModel $class): Result
    {
        $errors = $this->validateForm($class);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $saved = $this->classRepository->create($class);
        if ($saved) {
            return new Result(true, 'Class created successfully');
        }
        return new Result(false, 'Failed to create class');
    }


    private function validateForm(ClassModel $class)
    {

        $errors = [];
        if (empty($class->class_name)) {
            array_push($errors, "Name is required");
        }
        if ($this->classRepository->getByName($class->class_name)) {
            array_push($errors, "Class name already exist");
        }

        return $errors;
    }
    public function getPaginatedList($page = 1)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $offset = ($page - 1) * $limit;
        $result = $this->classRepository->getPaginatedList($offset);
        $total_records = $this->classRepository->getTotalCount();

        $total_pages = ceil($total_records / $limit);
        return new Result(true, "Data retrieved successfully", [], ["classes" => $result, "total_pages" => $total_pages, "current_page" => $page]);
    }

    public function getByID($id)
    {
        return $this->classRepository->getByID($id);
    }

    public function edit(ClassModel $class)
    {
        $classExists  = $this->getByID($class->class_id);
        if (!$classExists) {
            return new Result(false, "Class not found");
        }
        $errors = $this->validateForm($class);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $saved = $this->classRepository->edit($class);

        if ($saved) {
            return new Result(true, 'Class updated Successfully');
        }
        return new Result(false, 'Failed to update class');
    }

    public function delete(int $id)
    {
        $isUsed = $this->studentRepository->checkClassExistence($id);
        if ($isUsed) {
            return new Result(false, 'Cannot delete this class, the class contains students');
        }
        $this->classRepository->delete($id);
        return new Result(true, 'Class deleted successfully');
    }

    public function getList()
    {
        return $this->classRepository->getList();
    }
}
