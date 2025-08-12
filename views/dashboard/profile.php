<?php include_once __DIR__ . "/dashboard-header.php"; ?>

<div class="container-sm">
    <?php include_once __DIR__ . "/../templates/alerts.php"; ?>
    
    <form action="#" class="form" method="POST">
        <div class="field">
            <label for="name">Nombre</label>
            <input
                type="text"
                value="<?php echo $user->name; ?>"
                name="name"
                placeholder="Tu Nombre">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input
                type="email"
                value="<?php echo $user->email; ?>"
                name="email"
                placeholder="Tu Email">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . "/dashboard-footer.php"; ?>