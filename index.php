<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Config/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\AuthController;
use App\Controllers\ClassController;
use App\Controllers\studentController;
use App\Controllers\SubjectController;
use App\Controllers\TeacherController;
use App\Repositories\ClassRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\ClassService;
use App\Services\StudentService;
use App\Services\SubjectService;
use App\Services\TeacherService;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$userRepository = new UserRepository();
$authService = new AuthService($userRepository);
$authController = new AuthController($authService);
$classRepository = new ClassRepository();
$subjectRepository = new SubjectRepository();
$subjectService = new SubjectService($subjectRepository);
$subjectController = new SubjectController($subjectService);
$studentRepository = new StudentRepository();
$studentService = new StudentService($studentRepository);
$classService = new ClassService($classRepository, $studentRepository);
$studentController = new StudentController($studentService, $classService, $subjectService);
$classController = new ClassController($classService);
$teacherRepository = new TeacherRepository();
$teacherService = new TeacherService($teacherRepository);
$teacherController = new TeacherController($teacherService, $subjectService);

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace('/school_project', '', $uri);

if (!isset($_SESSION['user'])) {

    if (isset($_COOKIE['remember_me'])) {
        [$selector, $validator] = explode(':', $_COOKIE['remember_me']);
        $user = $authService->validateRememberToken($selector, $validator);

        if ($user) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];
            if (in_array($uri, ['/login', '/register'])) {
                header("Location: /school_project/students");
                exit;
            }
        } else {
            setcookie('remember_me', '', time() - 3600, '/');

            if (!in_array($uri, ['/login', '/register'])) {
                header("Location: /school_project/login");
                exit;
            }
        }
    } else {
        if (!in_array($uri, ['/login', '/register'])) {
            header("Location: /school_project/login");
            exit;
        }
    }
}

// --- 2. Handle already logged-in users visiting login/register ---
if (isset($_SESSION['user']) && in_array($uri, ['/login', '/register'])) {
    header("Location: /school_project/students");
    exit;
}



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/school_project', '', $uri);
switch (true) {
    case ($uri === '/register'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $authController->handleRegister()
            : $authController->showRegister();
        break;
    case ($uri === '/login'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $authController->handleLogin()
            : $authController->showLogin();
        break;
    case ($uri === '/classes'):
        $classController->index();
        break;
    case ($uri === '/classes/create'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $classController->create()
            : $classController->showForm();
        break;
    case (preg_match('#^/classes/edit/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $classController->edit($id)
            : $classController->showForm($id);
        break;
    case (preg_match('#^/classes/delete/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $classController->delete($id);
        break;
    case ($uri === '/subjects'):
        $subjectController->index();
        break;
    case ($uri === '/subjects/create'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $subjectController->create()
            : $subjectController->showForm();
        break;
    case (preg_match('#^/subjects/edit/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $subjectController->edit($id)
            : $subjectController->showForm($id);
        break;
    case (preg_match('#^/subjects/delete/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $subjectController->delete($id);
        break;
    case ($uri === '/dashboard'):
    case ($uri === '/students'):
        $studentController->index();
        break;
    case ($uri === '/students/create'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $studentController->create()
            : $studentController->showForm();
        break;
    case (preg_match('#^/students/edit/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $studentController->edit($id)
            : $studentController->showForm($id);
        break;
    case (preg_match('#^/students/view/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $studentController->view($id);
        break;
    case (preg_match('#^/students/delete/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $studentController->delete($id);
        break;
    case ($uri === '/teachers'):
        $teacherController->index();
        break;
    case ($uri === '/teachers/create'):
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $teacherController->create()
            : $teacherController->showForm();
        break;
    case (preg_match('#^/teachers/edit/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $teacherController->edit($id)
            : $teacherController->showForm($id);
        break;
    case (preg_match('#^/teachers/view/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $teacherController->view($id);
        break;
    case (preg_match('#^/teachers/delete/(\d+)$#', $uri, $matches)):
        $id = $matches[1];
        $teacherController->delete($id);
        break;
    case ($uri === '/logout'):
        $authController->logout();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
