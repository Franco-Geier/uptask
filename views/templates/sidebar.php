<aside class="sidebar">
    <div class="sidebar-container">
        <h2>UpTask</h2>    
        <div class="close-menu">
            <img src="build/img/close.svg" alt="Close menu image" id="close-menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo $tittle === 'Proyectos' ? 'active' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo $tittle === 'Crear Proyecto' ? 'active' : ''; ?>" href="/create-project">Crear Proyecto</a>
        <a class="<?php echo $tittle === 'Perfil' ? 'active' : ''; ?>" href="/profile">Perfil</a>
    </nav>

    <div class="mobile-singout">
        <a href="/logout" class="singout">Cerrar Sesión</a>
    </div>
</aside>