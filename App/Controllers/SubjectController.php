<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\Subject;
use App\Services\SubjectService;

class SubjectController
{
    private $subjectService;
    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = [
            'selected_page' => $selected_page,
            'result' => $this->subjectService->getPaginatedList($selected_page),
        ];
        Helper::render("Subject/List.php", $data);
    }
    public function showForm(?int $id = 0): void
    {
        $subjectData = null;
        if ($id !== 0) {
            $subjectData = $this->subjectService->getByID($id);
        }

        $data = [
            'subjectData' => $subjectData,
            'id' => $id
        ];
        Helper::render("Subject/Form.php", $data);
        
    }

    public function create()
    {
        $subject = new Subject(
            Helper::sanitizeInput($_POST['subject_name'])
        );
        $result = $this->subjectService->create($subject);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();

            $this->showForm();
        } else {
            header("Location: /school_project/subjects");
            exit;
        }
    }

    public function edit(int $id)
    {
        $subjects = new Subject(
            Helper::sanitizeInput($_POST['subject_name']),
            $id
        );

        $result = $this->subjectService->edit($subjects);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->storeInputFields();

            $this->showForm();
        } else {
            header("Location: /school_project/subjects");
            exit;
        }
    }
    public function delete(int $id)
    {
        $results = $this->subjectService->delete($id);
        if (!$results->success) {
            $_SESSION['result'] = [
                'success' => $results->success,
                'message' => $results->message
            ];
        }
        header("Location: /school_project/subjects");
        exit;
    }

    private function storeInputFields(): void
    {
        $_SESSION['input_fields'] = [
            'subject_name' => $_POST['subject_name']
        ];
    }
}
