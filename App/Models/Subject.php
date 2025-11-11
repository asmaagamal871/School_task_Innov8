<?php
namespace App\Models;

class Subject  {
    public ?int $subject_id;
    public string $subject_name;

    public function __construct(string $subject_name, ?int $subject_id = 0) {
        $this->subject_name = $subject_name;
        $this->subject_id = $subject_id;
    }
}
