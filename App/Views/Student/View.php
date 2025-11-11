<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="bg-light bg-gradient p-5 rounded shadow">
        <h2> Student details</h2>
        <p><strong>Student Name:</strong> <?=$studentData['student_name']?></p>
        <p><strong>CLass Name:</strong> <?=$studentData['class_name']?></p>
        <p><strong>Subjects:</strong></p>
        <ul>
            <?php foreach($studentData['subjects'] as $subject):?>
                <li><?=$subject ?></li>
            <?php endforeach?>
        </ul>

    </div>
</div>