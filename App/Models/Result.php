<?php

namespace App\Models;

class Result
{
    public bool $success;
    public string $message;
    public ?array $errors;
    public ?array $result;

    public function __construct(bool $success, string $message, ?array $errors = [], ?array $result = [])
    {
        $this->success = $success;
        $this->message = $message;
        $this->errors = $errors;
        $this->result = $result;
    }
}
