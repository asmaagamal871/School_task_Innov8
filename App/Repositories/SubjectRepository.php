<?php

namespace App\Repositories;

use Config\Database;
use App\Models\Subject;
use PDO;

class SubjectRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getByName(string $name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE subject_name = ?");
        $stmt->execute([$name]);
        $subject = $stmt->fetch();
        return $subject ?: null;
    }

    public function create(Subject $subject)
    {
        $stmt = $this->pdo->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
        return $stmt->execute([$subject->subject_name]);
    }

    public function getPaginatedList(int $offset)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $stmt = $this->pdo->prepare("SELECT * FROM subjects LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $subjects = $stmt->fetchAll();
        return $subjects ?: null;
    }

    public function getTotalCount()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
    }

    public function getByID(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE subject_id = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch();
        return $subject ?: null;
    }

    public function edit(Subject $subject)
    {
        $stmt = $this->pdo->prepare("UPDATE subjects SET subject_name = :name WHERE subject_id = :id ");
        $stmt->bindValue(':name', $subject->subject_name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $subject->subject_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM subjects WHERE subject_id=? ");
        $stmt->execute([$id]);
    }
    public function getList()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects");
        $stmt->execute();
        $subjects = $stmt->fetchAll();
        return $subjects ?: null;
    }


    public function isSubjectUsed(int $id): bool
    {
        $stmt = $this->pdo->prepare("
        SELECT subject_id FROM student_subjects WHERE subject_id = :id
        UNION
        SELECT subject_id FROM teacher_subjects WHERE subject_id = :id
    ");
        $stmt->execute([':id' => $id]);

        return (bool) $stmt->fetchColumn();
    }
}
