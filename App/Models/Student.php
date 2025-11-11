<?php
namespace App\Models;

class Student {
    public ?int $student_id;
    public string $student_name;
    public int $class_id;
    public array $subjects;

    public function __construct(string $student_name, int $class_id, array $subjects, ?int $student_id = 0) {
        $this->student_name = $student_name;
        $this->class_id = $class_id;
        $this->subjects = $subjects;
        $this->student_id = $student_id;
    }
}
