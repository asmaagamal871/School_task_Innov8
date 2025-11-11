<?php
$result = $_SESSION['result'] ?? null;
$inputFields = $_SESSION['input_fields'] ?? ['student_name' => '', 'class' => '', 'subjects'=>[]];
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
<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="bg-light bg-gradient p-5 rounded shadow">
        <h2><?= $id === 0 ? 'Create new student' : 'Edit student' ?></h2>
        <form method="POST" action=<?= $id === 0 ? "/school_project/students/create" : "/school_project/students/edit/" . $studentData['student_id'] ?>>
            <label class="py-2">Student name</label><br>
            <input
                class="form-control"
                type="text"
                placeholder="Enter student name"
                aria-label="default input example"
                name="student_name"

                value=<?= $id === 0 ? $inputFields['student_name'] : $studentData['student_name'] ?>>

            <label class="py-2">Class</label><br>
            
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="class">
                <option selected>Select class</option>
                <?php foreach ($classes as $class): ?>
                                        <option 
                        value="<?= $class['class_id'] ?>" 
                        <?= (
                            ($id === 0 && $inputFields['class'] == $class['class_id']) || 
                            ($id !== 0 && $studentData['class_id'] == $class['class_id'])
                        ) ? 'selected' : '' ?>
                    >
                        <?= $class['class_name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <label class="py-2">Subjects</label><br>
            <select class="form-select" multiple aria-label="multiple select example" name="subjects[]">
                <?php foreach ($subjects as $subject): ?>
                <option 
                        value="<?= $subject['subject_id'] ?>"
                        <?php 
                            $selectedSubjects = $id === 0 
                                ? $inputFields['subjects'] 
                                : $studentData['subjects']; 
                            echo in_array($subject['subject_id'], $selectedSubjects) ? 'selected' : ''; 
                        ?>
                    >
                        <?= htmlspecialchars($subject['subject_name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
            <br>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
</div>