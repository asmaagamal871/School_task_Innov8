<h2>Classes</h2>
<?php
$results = $_SESSION['result'] ?? null;
unset($_SESSION['result']);

if ($results && !$results['success']) {
    echo '<p style="color: red;">' . $results['message'] . '</p>';
}

?>

<?php if ($result->result['classes'] != null): ?>
    <div>
        <a class="btn btn-primary"
            style="width: 150px;"
            href="/school_project/classes/create"
            role="button">Add Class</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($result->result['classes'] as $class): ?>
                    <tr>
                        <td><?= $class['class_id'] ?></td>
                        <td><?= htmlspecialchars($class['class_name']) ?></td>
                        <td>
                            <a class="btn btn-primary" href="/school_project/classes/edit/<?= $class['class_id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $class['class_id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                            <div class="modal fade" id="deleteModal<?= $class['class_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?= $class['class_id'] ?>">Delete class</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this class <strong><?= $class['class_name'] ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a type="button" class="btn btn-danger" href="/school_project/classes/delete/<?= $class['class_id'] ?>">Delete</a>
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

            <p>Don't have classes? Add now</p>
            <div>
                <a class="btn btn-primary"
                    style="width: 150px;"
                    href="/school_project/classes/create"
                    role="button">Add Class</a>
            </div>
        <?php endif; ?>

        </div>