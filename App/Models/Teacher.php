<?php
namespace App\Models;

class Teacher {
    public ?int $teacher_id;
    public string $teacher_name;
    public array $subjects;

    public function __construct(string $teacher_name, array $subjects, ?int $teacher_id = 0) {
        $this->teacher_name = $teacher_name;
        $this->subjects = $subjects;
        $this->teacher_id = $teacher_id;
    }
}
