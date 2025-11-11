<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\Student;
use App\Services\ClassService;
use App\Services\StudentService;
use App\Services\SubjectService;

class StudentController
{
    private $studentService;
    private $classService;
    private $subjectService;
    private ?array $studentByID = null;
    public function __construct(StudentService $studentService, ClassService $classService, SubjectService $subjectService)
    {
        $this->studentService = $studentService;
        $this->classService = $classService;
        $this->subjectService = $subjectService;
    }

    public function showForm(?int $id = 0): void
    {
        $studentData = null;
        if ($id !== 0) {
            $studentData = $this->getByID($id);
            $studentData['subjects'] = array_column($studentData['subjects'], 'subject_id');
        }
        $data = [
            'classes' => $this->classService->getList(),
            'subjects' => $this->subjectService->getList(),
            'studentData' => $studentData,
            'id' => $id
        ];
        Helper::render("Student/Form.php", $data);
    }
    public function create()
    {
        $student = new Student(
            Helper::sanitizeInput($_POST['student_name']),
            (int)$_POST['class'],
            isset($_POST['subjects']) ? $_POST['subjects'] : [],
        );
        $result = $this->studentService->create($student);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();
            $this->showForm();
        } else {
            header("Location: /school_project/students");
            exit;
        }
    }

    public function index()
    {
        $selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = [
            'selected_page' => $selected_page,
            'result' => $this->studentService->getPaginatedList($selected_page),
        ];
        Helper::render("Student/List.php", $data);
    }
    public function edit(int $id)
    {
        $student = new Student(
            Helper::sanitizeInput($_POST['student_name']),
            (int)$_POST['class'],
            isset($_POST['subjects']) ? $_POST['subjects'] : [],
            $id
        );
        $result = $this->studentService->edit($student);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();

            $this->showForm($id);
        } else {
            header("Location: /school_project/students");
            exit;
        }
    }
    public function view($id)
    {
        $studentData = $this->getByID($id);
        if ($studentData) {

            $studentData['subjects'] = array_column($studentData['subjects'], 'subject_name');
            $data = [
                'studentData' => $studentData,
            ];

            Helper::render("Student/View.php", $data);
        }
    }

    public function delete(int $id)
    {
        $this->studentService->delete($id);
        header("Location: /school_project/students");
        exit;
    }
    private function storeInputFields(): void
    {
        $_SESSION['input_fields'] = [
            'student_name' => $_POST['student_name'] ?? '',
            'class' => $_POST['class'] ?? '',
            'subjects' => isset($_POST['subjects']) ? (array)$_POST['subjects'] : []
        ];
    }

    private function getByID(int $id)
    {
        if ($this->studentByID === null || $this->studentByID['student_id'] !== $id) {
            $result = $this->studentService->getByID($id);

            if ($result->success) {
                $this->studentByID = $result->result;
                return $result->result;
            }

            return null;
        } else return $this->studentByID;
    }
}
