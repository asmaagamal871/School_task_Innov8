<?php
require_once __DIR__ . '/../Layout.php';
?>
<?php if ($result != null && !$result->success): ?>
    <?php if (count($result->errors) > 0): ?>
        <ul style="color: red;">
            <?php foreach ($result->errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color: red;"><?php echo $result->message; ?></p>
    <?php endif; ?>
<?php endif; ?>
<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="d-flex flex-column justify-content-center align-items-center bg-light bg-gradient p-5 rounded shadow">
        <h2>Register</h2>
        <form method="POST" action="/school_project/register">
            <label class="form-label">Username:</label><br>
            <input class="form-control" type="text" name="username" value=<?= $inputFields['username'] ?>><br>
            <label class="form-label">Email:</label><br>
            <input class="form-control" type="email" name="email" value=<?= $inputFields['email'] ?>><br>
            <label class="form-label">Password:</label><br>
            <input class="form-control" type="password" name="password"><br>
            <div class="d-flex justify-content-center">

                <button class="btn btn-primary" type="submit">Sign Up</button>
            </div><br>
        </form>
        <p>Already have an account? <a href="/school_project/login">Login</a></p>
    </div>
</div>
<?php
include __DIR__ . '/../Layout/Footer.php';
