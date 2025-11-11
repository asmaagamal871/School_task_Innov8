<?php

namespace App\Repositories;

use Config\Database;
use App\Models\ClassModel;
use PDO;

class ClassRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getByName(string $name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE class_name = ?");
        $stmt->execute([$name]);
        $class = $stmt->fetch();
        return $class ?: null;
    }

    public function create(ClassModel $class)
    {
        $stmt = $this->pdo->prepare("INSERT INTO classes (class_name) VALUES (?)");
        return $stmt->execute([$class->class_name]);
    }

    public function getPaginatedList(int $offset)
    {
        $limit = $_ENV['PAGE_LIMIT'];
        $stmt = $this->pdo->prepare("SELECT * FROM classes LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $classes = $stmt->fetchAll();
        return $classes ?: null;
    }

    public function getTotalCount()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    }

    public function getByID(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE class_id = ?");
        $stmt->execute([$id]);
        $class = $stmt->fetch();
        return $class ?: null;
    }

    public function edit(ClassModel $class)
    {
        $stmt = $this->pdo->prepare("UPDATE classes SET class_name = :name WHERE class_id = :id ");
        $stmt->bindValue(':name', $class->class_name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $class->class_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classes WHERE class_id=? ");
        $stmt->execute([$id]);

    }

    public function getList(){
        $stmt = $this->pdo->prepare("SELECT * FROM classes");
        $stmt->execute();
        $classes = $stmt->fetchAll();
        return $classes ?: null;
    }

    
}
