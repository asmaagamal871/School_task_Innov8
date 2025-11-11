<h2>Teachers</h2>
<?php if ($result->result['teachers'] != null): ?>
    <div>
        <a class="btn btn-primary"
            style="width: 150px;"
            href="/school_project/teachers/create"
            role="button">Add Teacher</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($result->result['teachers'] as $teacher): ?>
                    <tr>
                        <td><?= $teacher['teacher_id'] ?></td>
                        <td><?= $teacher['teacher_name'] ?></td>
                        <td>
                            <a class="btn btn-primary" href="/school_project/teachers/edit/<?= $teacher['teacher_id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a class="btn btn-success" href="/school_project/teachers/view/<?= $teacher['teacher_id'] ?>">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $teacher['teacher_id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>

                            <div class="modal fade" id="deleteModal<?= $teacher['teacher_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $teacher['teacher_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?= $teacher['teacher_id'] ?>">Delete teacher</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong><?= htmlspecialchars($teacher['teacher_name']) ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a class="btn btn-danger" href="/school_project/teachers/delete/<?= $teacher['teacher_id'] ?>">Delete</a>
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

            <p>Don't have teachers? Add now</p>
            <div>
                <a class="btn btn-primary"
                    style="width: 150px;"
                    href="/school_project/teachers/create"
                    role="button">Add Teacher</a>
            </div>
        <?php endif; ?>

        </div>