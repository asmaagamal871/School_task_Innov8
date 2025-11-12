<?php
require_once __DIR__ . '/../Layout.php';
?>

<?php
$result = $_SESSION['result'] ?? null;
unset($_SESSION['result']);
?>

<?php if ($result != null && !$result->success): ?>
    <p style="color: red;"><?php echo $result->message; ?></p>
<?php endif; ?>

<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="d-flex flex-column justify-content-center align-items-center bg-light bg-gradient p-5 rounded shadow">
        <h2>Login</h2>
        <form method="POST" action="/school_project/login">
            <label class="form-label">Email:</label><br>
            <input class="form-control" type="email" name="email" required><br>
            <label class="form-label">Password:</label><br>
            <input class="form-control" type="password" name="password" required><br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me"
                    value="checked">
                <label class="form-check-label" for="flexCheckDefault">
                    Remember Me
                </label>
            </div>
            <br>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary" type="submit">Login</button>
            </div></br>
        </form>
        <p>Don’t have an account? <a href="/school_project/register">Register new account</a></p>
    </div>
</div>
<?php
include __DIR__ . '/../Layout/Footer.php';
