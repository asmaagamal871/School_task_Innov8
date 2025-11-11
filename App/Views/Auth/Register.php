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
        <p><?php echo $result->message; ?></p>
    <?php endif; ?>
<?php endif; ?>

<h2>Register</h2>
<form method="POST" action="/school_project/register">
    <label>Username:</label><br>
    <input type="text" name="username" value=<?= $inputFields['username'] ?>><br>
    <label>Email:</label><br>
    <input type="email" name="email" value=<?= $inputFields['email'] ?>><br>
    <label>Password:</label><br>
    <input type="password" name="password" ><br><br>
    <button type="submit">Sign Up</button>
</form>
<p>Already have an account? <a href="/school_project/login">Login</a></p>

<?php 
include __DIR__ . '/../Layout/Footer.php';

