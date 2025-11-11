<h2>Students</h2>
<?php if ($result->result['students'] != null): ?>
    <div>
        <a class="btn btn-primary"
            style="width: 150px;"
            href="/school_project/students/create"
            role="button">Add Student</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Class</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($result->result['students'] as $student): ?>
                    <tr>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['student_name'] ?></td>
                        <td><?= $student['class_name'] ?></td>
                        <td>
                            <a class="btn btn-primary" href="/school_project/students/edit/<?= $student['student_id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a class="btn btn-success" href="/school_project/students/view/<?= $student['student_id'] ?>">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $student['student_id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>

                            <div class="modal fade" id="deleteModal<?= $student['student_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $student['student_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?= $student['student_id'] ?>">Delete student</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong><?= $student['student_name'] ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a class="btn btn-danger" href="/school_project/students/delete/<?= $student['student_id'] ?>">Delete</a>
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

            <p>Don't have students? Add now</p>
            <div>
                <a class="btn btn-primary"
                    style="width: 150px;"
                    href="/school_project/students/create"
                    role="button">Add Student</a>
            </div>
        <?php endif; ?>

        </div>