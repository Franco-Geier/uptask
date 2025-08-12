<?php include_once __DIR__ . "/dashboard-header.php"; ?>

<div class="container-sm">
    <?php include_once __DIR__ . "/../templates/alerts.php"; ?>
    <a href="/profile" class="link">Volver a Perfil</a>

    <form action="/profile" class="form" method="POST">
        <div class="field">
            <label for="password_actual">Password Actual</label>
            <input
                type="password"
                name="password_actual"
                placeholder="Tu Password Actual">
        </div>

        <div class="field">
            <label for="password_nuevo">Password Nuevo</label>
            <input
                type="password"
                name="password_nuevo"
                placeholder="Tu Password Nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . "/dashboard-footer.php"; ?>