<?php
/**
 * @var Pagination $pagination
 * @var array $messages
 * @var int $page
 */
?>
<?php require_once __DIR__ . '/includes/header.tpl.php'; ?>

    <div class="container mt-5">

        <div class="row">
            <div class="col-12 mb-4">
                <?php if (isset($_SESSION['errors'])) : ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php
                        echo $_SESSION['errors'];
                        unset($_SESSION['errors']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>

                <?php if (isset($_SESSION['success'])) : ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>
            </div>

            <?php if (isLoggedIn()) : ?>

                <form method="post" class="mb-3">
                    <div class="form-floating">
                <textarea name="message" class="form-control" placeholder="Leave a comment here" id="send-message"
                          style="height: 100px"></textarea>
                        <label for="send-message">Comments</label>
                    </div>

                    <button name="submit-message" type="submit" class="btn btn-primary mt-3">Submit</button>

                </form>

                <div class="col-12">
                    <hr>
                </div>

            <?php endif; ?>

        </div>

        <?php if (!empty($messages)) : ?>

            <div class="row">
                <div class="col-12">
                    <?= $pagination ?>
                </div>
            </div>

        <?php endif; ?>

        <div class="row">
            <div class="col-12 mb-4">

                <?php if (!empty($messages)) : ?>

                    <?php foreach ($messages as $message) : ?>

                        <div class="card mb-3 <?php if (!$message['status']) echo 'border-danger'; ?>"
                             id="message-<?= $message['id'] ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title"><?= $message['name'] ?></h5>
                                    <p class="message-created"><?= $message['created_at'] ?></p>
                                </div>
                                <div class="card-text mb-3"><?= nl2br(specialChars($message['message'])) ?>
                                </div>

                                <?php if (isAdmin()) : ?>

                                    <div class="card-actions mt-2">

                                        <p>

                                            <?php if ($message['status']) : ?>
                                                <a href="?page=<?= $page ?>&do=toggle-status&status=0&id=<?= $message['id'] ?>"
                                                   class="btn btn-danger">Disable</a>
                                            <?php else: ?>
                                                <a href="?page=<?= $page ?>&do=toggle-status&status=1&id=<?= $message['id'] ?>"
                                                   class="btn btn-success">Approve</a>
                                            <?php endif; ?>

                                            <a class="btn btn-primary" data-bs-toggle="collapse"
                                               href="#collapse-<?= $message['id'] ?>">Edit</a>
                                        </p>

                                        <div class="collapse" id="collapse-<?= $message['id'] ?>">
                                            <form method="post">
                                                <div class="form-floating">
                                    <textarea name="message" class="form-control" placeholder="Leave a comment here"
                                              id="text-<?= $message['id'] ?>"
                                              style="height: 200px"><?= $message['message'] ?></textarea>
                                                    <label for="text-<?= $message['id'] ?>">Edit</label>
                                                </div>
                                                <input type="hidden" name="id" value="<?= $message['id'] ?>">
                                                <input type="hidden" name="page" value="<?= $_GET['page'] ?? 1 ?>">

                                                <button name="edit-message" type="submit" class="btn btn-primary mt-3">
                                                    Save
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else : ?>

                    <p>No messages available.</p>

                <?php endif; ?>

            </div>
        </div>

        <?php if (!empty($messages)) : ?>

            <div class="row">
                <div class="col-12">
                    <?= $pagination ?>
                </div>
            </div>

        <?php endif; ?>
    </div>

<?php require_once __DIR__ . '/includes/footer.tpl.php'; ?>