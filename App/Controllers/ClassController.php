<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\ClassModel;
use App\Services\ClassService;

class ClassController
{
    private $classService;
    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }

    public function index()
    {
        $selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = [
            'selected_page' => $selected_page,
            'result' => $this->classService->getPaginatedList($selected_page),
        ];
        Helper::render("Class/List.php", $data);

    }
    public function showForm(?int $id = 0): void
    {
        $classData = null;
        if ($id !== 0) {
            $classData = $this->classService->getByID($id);
        }
        $data = [
            'classData' => $classData,
            'id'=>$id
        ];
        Helper::render("Class/Form.php", $data);
    }

    public function create()
    {
        $class = new ClassModel(
            Helper::sanitizeInput($_POST['class_name'])
        );
        $result = $this->classService->create($class);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();
            $this->showForm();
        } else {
            header("Location: /school_project/classes");
            exit;
        }
    }

    public function edit(int $id)
    {
        $class = new ClassModel(
            Helper::sanitizeInput($_POST['class_name']),
            $id
        );

        $result = $this->classService->edit($class);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();
            $this->showForm();
        } else {
            header("Location: /school_project/classes");
            exit;
        }
    }
    public function delete(int $id)
    {
        $results = $this->classService->delete($id);
        if (!$results->success) {
            $_SESSION['result'] = [
                'success' => $results->success,
                'message' => $results->message
            ];
        }
        header("Location: /school_project/classes");
        exit;
    }

    private function storeInputFields(): void
    {
        $_SESSION['input_fields'] = [
            'class_name' => $_POST['class_name']
        ];
    }
}
