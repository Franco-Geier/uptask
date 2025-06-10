<?php include_once __DIR__ . "/dashboard-header.php"; ?>

    <div class="container-sm">
        <?php include_once __DIR__ . "/../templates/alerts.php"; ?>

        <form action="/create-project" class="form" method="post">
            <?php include_once __DIR__ . "/project-form.php"; ?>
            <input type="submit" value="Crear Proyecto">
        </form>
    </div>

<?php include_once __DIR__ . "/dashboard-footer.php"; ?>