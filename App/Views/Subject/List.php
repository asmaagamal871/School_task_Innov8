<h2>Subjects</h2>
<?php
$results = $_SESSION['result'] ?? null;
unset($_SESSION['result']);

if ($results && !$results['success']) {
    echo '<p style="color: red;">' . $results['message'] . '</p>';
}

?>
<?php if ($result->result['subjects'] != null): ?>
    <div>
        <a class="btn btn-primary"
            style="width: 150px;"
            href="/school_project/subjects/create"
            role="button">Add Subject</a>
        <!-- <div> -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($result->result['subjects'] as $subject): ?>
                    <tr>
                        <td><?= $subject['subject_id'] ?></td>
                        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                        <td>
                            <a class="btn btn-primary" href="/school_project/subjects/edit/<?= $subject['subject_id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $subject['subject_id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                            <div class="modal fade" id="deleteModal<?= $subject['subject_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?= $subject['subject_id'] ?>">Delete class</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this class <strong><?= $subject['subject_name'] ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a type="button" class="btn btn-danger" href="/school_project/subjects/delete/<?= $subject['subject_id'] ?>">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $result->result['total_pages']; $i++): ?>
                    <li class="page-item <?= $i === $result->result['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="d-flex flex-column justify-content-center align-items-center pt-5">

            <p>Don't have subjects? Add now</p>
            <div>
                <a class="btn btn-primary"
                    style="width: 150px;"
                    href="/school_project/subjects/create"
                    role="button">Add Subject</a>
            </div>
            <!-- </div> -->
        <?php endif; ?>

        </div>