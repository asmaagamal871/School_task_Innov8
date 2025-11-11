<?php

namespace App\Helpers;

class Helper
{
    public static function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function render(string $page, array $varsData = []): void
    {
        extract($varsData); 
        require __DIR__ . '/../Views/Dashboard/Dashboard.php';
        exit;
    }
}
