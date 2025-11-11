<div class="d-flex justify-content-center align-items-center pt-5">
    <div class="bg-light bg-gradient p-5 rounded shadow">
        <h2><strong>Teacher details</strong> </h2>
        <p><strong> Teacher Name: </strong><?=$teacherData['teacher_name']?></p>
        <p><strong> Subjects:</strong></p>
        <ul>
            <?php foreach($teacherData['subjects'] as $subject):?>
                <li><?=$subject ?></li>
            <?php endforeach?>
        </ul>

    </div>
</div>