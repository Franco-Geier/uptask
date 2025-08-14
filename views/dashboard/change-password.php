<?php include_once __DIR__ . "/dashboard-header.php"; ?>

<div class="container-sm">
    <?php include_once __DIR__ . "/../templates/alerts.php"; ?>
    <a href="/profile" class="link">Volver a Perfil</a>

    <form action="/change-password" class="form" method="POST">
        <div class="field">
            <label for="current_password">Password Actual</label>
            <input
                type="password"
                name="current_password"
                placeholder="Tu Password Actual">
        </div>

        <div class="field">
            <label for="new_password">Password Nuevo</label>
            <input
                type="password"
                name="new_password"
                placeholder="Tu Password Nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . "/dashboard-footer.php"; ?>