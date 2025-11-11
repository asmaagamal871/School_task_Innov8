<?php
namespace App\Models;

class ClassModel  {
    public ?int $class_id;
    public string $class_name;

    public function __construct(string $class_name, ?int $class_id = 0) {
        $this->class_name = $class_name;
        $this->class_id = $class_id;
    }
}
