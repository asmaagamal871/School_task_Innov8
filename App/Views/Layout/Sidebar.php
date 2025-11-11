<?php
$current_page = basename($_SERVER['REQUEST_URI']);
?>

<div class="d-flex flex-row">
  <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4">School</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="/school_project/students"
          class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/school_project/students') === 0) ? 'active' : 'text-white'; ?>">
          Students
        </a>
      </li>

      <li>
        <a href="/school_project/classes"
          class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/school_project/classes') === 0) ? 'active' : 'text-white'; ?>">
          Classes
        </a>
      </li>

      <li>
        <a href="/school_project/teachers"
          class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/school_project/teachers') === 0) ? 'active' : 'text-white'; ?>">
          Teachers
        </a>
      </li>

      <li>
        <a href="/school_project/subjects"
          class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/school_project/subjects') === 0) ? 'active' : 'text-white'; ?>">
          Subjects
        </a>
      </li>
    </ul>
    <hr>
    <a class="dropdown-item" href="/school_project/logout">Sign out</a>
  </div>