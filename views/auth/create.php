<div class="container create">
    <?php include_once __DIR__ . "/../templates/site-name.php"; ?>
    <div class="container-sm">
        <p class="page-description">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . "/../templates/alerts.php"; ?>

        <form class="form" action="/create" method="post">
            <div class="field">
                <label for="name">Nombre</label>
                <input 
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Tu Nombre"
                    value="<?php echo s($user->name); ?>">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Tu Email"
                    value="<?php echo s($user->email); ?>">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Tu Password">
            </div>

            <div class="field">
                <label for="password2">Repetir Password</label>
                <input
                    type="password"
                    name="password2"
                    id="password2"
                    placeholder="Repite tu Password">
            </div>

            <input type="submit" class="button" value="Crear cuenta">
        </form>
        <div class="actions">
            <a href="/">¿Ya tienes una cuenta? Iniciar sesión</a>
            <a href="/forgot">¿Olvidaste tu password?</a>
        </div>
    </div> <!--.contenedor-sm -->
</div>