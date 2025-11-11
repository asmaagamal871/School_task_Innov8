<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\Teacher;
use App\Services\SubjectService;
use App\Services\TeacherService;

class TeacherController
{
    private $teacherService;
    private $subjectService;
    private ?array $teacherByID = null;


    public function __construct(TeacherService $teacherService, SubjectService $subjectService)
    {
        $this->teacherService = $teacherService;
        $this->subjectService = $subjectService;
    }

    public function showForm(?int $id = 0): void
    {
        $teacherData = null;
        if ($id !== 0) {
            $teacherData = $this->getByID($id);
            $teacherData['subjects'] = array_column($teacherData['subjects'], 'subject_id');
        }

        $data=[
            'subjects' => $this->subjectService->getList(),
            'teacherData'=>$teacherData,
            'id'=>$id
        ];
        Helper::render("Teacher/Form.php", $data);
    }
    public function create()
    {
        $teacher = new Teacher(
            Helper::sanitizeInput($_POST['teacher_name']),
            isset($_POST['teacher_subjects']) ? $_POST['teacher_subjects'] : [],
        );
        $result = $this->teacherService->create($teacher);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();

            $this->showForm();
        } else {
            header("Location: /school_project/teachers");
            exit;
        }
    }

    public function index()
    {
        $selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data=[
            'selected_page'=>$selected_page,
            'result'=>$this->teacherService->getPaginatedList($selected_page)
        ];
        Helper::render("Teacher/List.php", $data);
    }
    public function edit(int $id)
    {
        $teacher = new Teacher(
            Helper::sanitizeInput($_POST['teacher_name']),
            isset($_POST['teacher_subjects']) ? $_POST['teacher_subjects'] : [],
            $id
        );
        $result = $this->teacherService->edit($teacher);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();
            $this->showForm($id);
        } else {
            header("Location: /school_project/teachers");
            exit;
        }
    }
    public function view($id)
    {
        $teacherData = $this->getByID($id);
        if ($teacherData) {
            $teacherData['subjects'] = array_column($teacherData['subjects'], 'subject_name');
            $data = [
                'teacherData' => $teacherData,
            ];

            Helper::render("Teacher/View.php", $data);
        }
    }

    public function delete(int $id)
    {
        $this->teacherService->delete($id);
        header("Location: /school_project/teachers");
        exit;
    }
    private function storeInputFields(): void
    {
        $_SESSION['input_fields'] = [
            'teacher_name' => $_POST['teacher_name'] ?? '',
            'teacher_subjects' => isset($_POST['teacher_subjects']) ? (array)$_POST['teacher_subjects'] : []
        ];
    }

    private function getByID(int $id)
    {
        if ($this->teacherByID === null || $this->teacherByID['student_id'] !== $id) {
            $result = $this->teacherService->getByID($id);

            if ($result->success) {
                $this->teacherByID = $result->result;
                return $result->result;
            }

            return null;
        } else return $this->teacherByID;
    }
}
