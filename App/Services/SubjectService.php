<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Subject;
use App\Repositories\SubjectRepository;

class SubjectService
{
    private $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function create(Subject $subject): Result
    {
        $errors = $this->validateForm($subject);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $saved = $this->subjectRepository->create($subject);
        if ($saved) {
            return new Result(true, 'Subject created successfully');
        }
        return new Result(false, 'Failed to create Subject');
    }


    private function validateForm(Subject $subject)
    {

        $errors = [];
        if (empty($subject->subject_name)) {
            array_push($errors, "Name is required");
        }
        if ($this->subjectRepository->getByName($subject->subject_name)) {
            array_push($errors, "Subject name already exist");
        }

        return $errors;
    }
    public function getPaginatedList($page = 1)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $offset = ($page - 1) * $limit;
        $result = $this->subjectRepository->getPaginatedList($offset);
        $total_records = $this->subjectRepository->getTotalCount();

        $total_pages = ceil($total_records / $limit);
        return new Result(true, "Data retrieved successfully", [], ["subjects" => $result, "total_pages" => $total_pages, "current_page" => $page]);
    }

    public function getByID($id)
    {
        return $this->subjectRepository->getByID($id);
    }

    public function edit(Subject $subject)
    {
        $subjectExists  = $this->getByID($subject->subject_id);
        if (!$subjectExists) {
            return new Result(false, "Subject not found");
        }
        $errors = $this->validateForm($subject);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }
        $saved = $this->subjectRepository->edit($subject);

        if ($saved) {
            return new Result(true, 'Subject updated Successfully');
        }
        return new Result(false, 'Failed to update Subject');
    }
        public function delete(int $id)
    {
        $isUsed = $this->subjectRepository->isSubjectUsed($id);
        if ($isUsed) {
            return new Result(false, 'Cannot delete this subject, it is assigned to students or teachers');
        }
        $this->subjectRepository->delete($id);
        return new Result(true, 'Subject  deleted successfully');
    }


        public function getList(){
        return $this->subjectRepository->getList();
    }
}