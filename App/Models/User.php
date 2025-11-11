<?php
namespace App\Models;

class User {
    public ?int $user_id;
    public string $username;
    public string $email;
    public string $password;

    public function __construct( string $email, ?string $password ='',string $username='', ?int $user_id = 0) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->user_id = $user_id;
    }
}
