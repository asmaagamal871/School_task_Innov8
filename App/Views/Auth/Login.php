<?php
require_once __DIR__ . '/../Layout.php';
?>

<?php
$result = $_SESSION['result'] ?? null;
unset($_SESSION['result']);
?>

<?php if ($result != null && !$result->success): ?>
    <p><?php echo $result->message; ?></p>
<?php endif; ?>

<h2>Login</h2>
<form method="POST" action="/school_project/login">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <label for="remember_me">
        <input type="checkbox" name="remember_me" id="remember_me"
            value="checked" />
        Remember Me
    </label>
    <br><br>
    <button type="submit">Login</button>
</form>
<p>Don’t have an account? <a href="/school_project/register">Register new account</a></p>

<?php
include __DIR__ . '/../Layout/Footer.php';
