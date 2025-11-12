<?php

namespace App\Repositories;

use App\Models\Teacher;
use Config\Database;
use PDO;
use PDOException;

class TeacherRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(Teacher $teacher)
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO teachers (teacher_name) VALUES (?)");
            $stmt->execute([$teacher->teacher_name]);
            $teacher->teacher_id = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO teacher_subjects (teacher_id,subject_id) VALUES (?,?)");
            foreach ($teacher->subjects as $subject) {
                $stmt->execute([$teacher->teacher_id, $subject]);
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollback();
            return false;
        }
    }
    public function getPaginatedList(int $offset)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $stmt = $this->pdo->prepare("SELECT teachers.teacher_id, teachers.teacher_name FROM teachers LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $teachers = $stmt->fetchAll();
        return $teachers ?: null;
    }

    public function getTotalCount()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
    }

    public function getByID(int $id)
    {

        $stmt = $this->pdo->prepare("
            SELECT 
                t.teacher_id,
                t.teacher_name,
                subj.subject_id,
                subj.subject_name
            FROM teachers t
            LEFT JOIN teacher_subjects tsub 
                ON t.teacher_id = tsub.teacher_id
            LEFT JOIN subjects subj 
                ON tsub.subject_id = subj.subject_id
            WHERE t.teacher_id = ?
        ");
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll();

        if ($rows) {
            $teacher = [
                'teacher_id' => $rows[0]['teacher_id'],
                'teacher_name' => $rows[0]['teacher_name'],
                'subjects' => []
            ];
            foreach ($rows as $row) {
                if ($row['subject_id']) {
                    $teacher['subjects'][] = [
                        'subject_id' => $row['subject_id'],
                        'subject_name' => $row['subject_name']
                    ];
                }
            }
        }
        return $teacher;
    }

    public function edit(Teacher $teacher, array $new, array $deleted, bool $updateBasicInfo)
    {
        try {
            $this->pdo->beginTransaction();
            if ($updateBasicInfo) {
                $stmt = $this->pdo->prepare("UPDATE teachers SET teacher_name = :name WHERE teacher_id = :id ");
                $stmt->bindValue(':name', $teacher->teacher_name, PDO::PARAM_STR);
                $stmt->bindValue(':id', $teacher->teacher_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            if (count($new) > 0) {
                $stmt2 = $this->pdo->prepare("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)");
                foreach ($new as $subject_id) {
                    $stmt2->execute([$teacher->teacher_id, $subject_id]);
                }
            }
            if (count($deleted) > 0) {
                $stmt2 = $this->pdo->prepare("DELETE FROM teacher_subjects WHERE teacher_id = ? AND subject_id = ?");
                foreach ($deleted as $subject_id) {
                    $stmt2->execute([$teacher->teacher_id, $subject_id]);
                }
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollback();
            return false;
        }
    }

    public function delete(int $id)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("DELETE FROM teacher_subjects WHERE teacher_id= ? ");
            $stmt->execute([$id]);
            $stmt1 = $this->pdo->prepare("DELETE FROM teachers WHERE teacher_id= ? ");
            $stmt1->execute([$id]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollback();
            return false;
        }
    }
}
