<div class="container forgot">
    <?php include_once __DIR__ . "/../templates/site-name.php"; ?>
    <div class="container-sm">
        <p class="page-description">Recupera tu Acceso UpTask</p>

        <?php include_once __DIR__ . "/../templates/alerts.php"; ?>
        
        <form class="form" action="/forgot" method="post">
            <div class="field">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email">
            </div>

            <input type="submit" class="button" value="Enviar Instrucciones">
        </form>
        <div class="actions">
            <a href="/">¿Ya tienes una cuenta? Iniciar sesión</a>
            <a href="/create">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div> <!--.contenedor-sm -->
</div>