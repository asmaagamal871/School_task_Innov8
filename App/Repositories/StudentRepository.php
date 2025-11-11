<?php

namespace App\Repositories;

use App\Models\Student;
use Config\Database;
use PDO;
use PDOException;

class StudentRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(Student $student)
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO students (student_name,class_id) VALUES (?,?)");
            $stmt->execute([$student->student_name, $student->class_id]);
            $student->student_id = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO student_subjects (student_id,subject_id) VALUES (?,?)");
            foreach ($student->subjects as $subject) {
                $stmt->execute([$student->student_id, $subject]);
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
        $stmt = $this->pdo->prepare("SELECT students.student_id, students.student_name,
         classes.class_name FROM students JOIN classes ON students.class_id = classes.class_id 
         LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll();
        return $students ?: null;
    }

    public function deletex(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE student_id=? ");
        $stmt->execute([$id]);
    }

    public function getTotalCount()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    public function getByID(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                s.student_id,
                s.student_name,
                s.class_id,
                c.class_name,
                subj.subject_id,
                subj.subject_name
            FROM students s
            LEFT JOIN classes c 
                ON s.class_id = c.class_id
            LEFT JOIN student_subjects sub 
                ON s.student_id = sub.student_id
            LEFT JOIN subjects subj 
                ON sub.subject_id = subj.subject_id
            WHERE s.student_id = ?
        ");
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            $student = [
                'student_id' => $rows[0]['student_id'],
                'student_name' => $rows[0]['student_name'],
                'class_id' => $rows[0]['class_id'],
                'class_name' => $rows[0]['class_name'],
                'subjects' => []
            ];
            foreach ($rows as $row) {
                if ($row['subject_id']) {
                    $student['subjects'][] = [
                        'subject_id' => $row['subject_id'],
                        'subject_name' => $row['subject_name']
                    ];
                }
            }
        }
        return $student;
    }

    public function edit(Student $student, array $new, array $deleted, bool $updateBasicInfo)
    {
        try {
            $this->pdo->beginTransaction();
            if ($updateBasicInfo) {
                $stmt = $this->pdo->prepare("UPDATE students SET student_name = :name, class_id = :class_id WHERE student_id = :id ");
                $stmt->bindValue(':name', $student->student_name, PDO::PARAM_STR);
                $stmt->bindValue(':class_id', $student->class_id, PDO::PARAM_INT);
                $stmt->bindValue(':id', $student->student_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            if (count($new) > 0) {
                $stmt2 = $this->pdo->prepare("INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)");
                foreach ($new as $subjectId) {
                    $stmt2->execute([$student->student_id, $subjectId]);
                }
            }
            if (count($deleted) > 0) {
                $stmt2 = $this->pdo->prepare("DELETE FROM student_subjects WHERE student_id = ? AND subject_id = ?");
                foreach ($deleted as $subjectId) {
                    $stmt2->execute([$student->student_id, $subjectId]);
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
            $stmt = $this->pdo->prepare("DELETE FROM student_subjects WHERE student_id= ? ");
            $stmt->execute([$id]);
            $stmt1 = $this->pdo->prepare("DELETE FROM students WHERE student_id= ? ");
            $stmt1->execute([$id]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollback();
            return false;
        }
    }

    public function checkClassExistence(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT student_id FROM students WHERE class_id= ? LIMIT 1");
        $stmt->execute([$id]);
        return (bool) $stmt->fetchColumn();
    }
}
