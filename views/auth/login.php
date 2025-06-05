<div class="container login">
    <h1 class="uptask">UpTask</h1>
    <p class="tagline">Crea y administra tus proyectos</p>

    <div class="container-sm">
        <p class="page-description">Iniciar Sesión</p>
        <form class="form" action="/" method="post">
            <div class="field">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Tu Password">
            </div>

            <input type="submit" class="button" value="Iniciar Sesión">
        </form>
        <div class="actions">
            <a href="/create">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/forgot">¿Olvidaste tu password?</a>
        </div>
    </div> <!--.contenedor-sm -->
</div>