<div class="container restore">
    <?php include_once __DIR__ . "/../templates/site-name.php"; ?>
    <div class="container-sm">
        <p class="page-description">Coloca tu Nuevo Password</p>
        <form class="form" action="/restore" method="post">

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
                    placeholder="Repite tu Nuevo Password">
            </div>

            <input type="submit" class="button" value="Guardar Password">
        </form>
        <div class="actions">
            <a href="/create">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/forgot">¿Olvidaste tu password?</a>
        </div>
    </div> <!--.contenedor-sm -->
</div>