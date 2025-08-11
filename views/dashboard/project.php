<?php include_once __DIR__ . "/dashboard-header.php"; ?>
    <div class="container-sm">
        <div class="container-new-task">
            <button type="button" class="add-task" id="add-task">&#43; Nueva Tarea</button>
        </div>
    </div>

    <div id="filters" class="filters">
        <div class="filters-inputs">
            <h2>Filtros</h2>
            <div class="field">
                <label for="all">Todas</label>
                <input
                    type="radio"
                    name="filter"
                    id="all"
                    value=""
                    checked>
            </div>

            <div class="field">
                <label for="completed">Completadas</label>
                <input
                    type="radio"
                    name="filter"
                    id="completed"
                    value="1">
            </div>

            <div class="field">
                <label for="pending">Pendientes</label>
                <input
                    type="radio"
                    name="filter"
                    id="pending"
                    value="0">
            </div>
        </div>
    </div>

    <ul id="tasks-list" class="tasks-list"></ul>
<?php include_once __DIR__ . "/dashboard-footer.php"; ?>

<?php $script .= '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tasks.min.js"></script>
'; 
?>