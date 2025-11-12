<?php
$result = $_SESSION['result'] ?? null;
$inputFields = $_SESSION['input_fields'] ?? ['class_name' => ''];
unset($_SESSION['result'], $_SESSION['input_fields']);
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
<a class="btn btn-primary" href="/school_project/classes" style="width :40px;">
    <i class="bi bi-arrow-left-short"></i>
</a>
<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="bg-light bg-gradient p-5 rounded shadow">
        <h2><?= $id === 0 ? 'Create new class' : 'Edit class' ?></h2>
        <form method="POST" action=<?= $id === 0 ? "/school_project/classes/create" : "/school_project/classes/edit/" . $classData['class_id'] ?>>
            <label class="py-2">Class name</label><br>
            
            <input
                class="form-control"
                type="text"
                placeholder="Enter class name"
                aria-label="default input example"
                name="class_name"

                value="<?= $id === 0 ? $inputFields['class_name'] : $classData['class_name'] ?>"">
            <br>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
</div>