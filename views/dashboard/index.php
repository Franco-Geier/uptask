<?php include_once __DIR__ . "/dashboard-header.php"; ?>

    <?php if(count($projects) === 0) { ?>
        <p class="no-projects">No hay proyectos aún</p>
        <a href="/create-project">Comienza creando uno</a>
    <?php }  else { ?>
        <ul class="project-list">
            <?php foreach($projects as $project) { ?>
                <li class="project">
                    <a href="/project?url=<?php echo $project->url; ?>">
                        <?php echo $project->project; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

<?php include_once __DIR__ . "/dashboard-footer.php"; ?>