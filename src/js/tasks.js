// IIFE
(function() {

    getTasks();

    let tasks = [];
    let filtred = [];
    let currentFilter = ""; // guarda filtro activo

    getTasks();

    const newTaskBtn = document.querySelector("#add-task"); // Boton para mostrar el modal de agregar tarea
    newTaskBtn.addEventListener("click", function() {
        showForm();
    });

    // Filtros de búsqueda
    const filters = document.querySelectorAll('#filters input[type="radio"]');
    filters.forEach(radio => {
        radio.addEventListener("input", filterTasks);
    })

    function filterTasks(e) {
        currentFilter = e.target.value; // guarda filtro actual
        applyFilter();
        showTasks();
    }

    function applyFilter() {
        if (currentFilter !== "") {
            filtred = tasks.filter(task => task.state === parseInt(currentFilter));
        } else {
            filtred = [];
        }
    }

    async function getTasks() {
        try {
            const id = getProject();
            const url = `/api/tasks?url=${id}`;
            const response = await fetch(url);
            const result = await response.json();
            tasks = result.tasks;
            applyFilter();
            showTasks();

        } catch(error) {
            console.error(error);
        }
    }

    function showTasks() {
        cleanTasks();
        toggleFilterDisabled(0, "#pending");
        toggleFilterDisabled(1, "#completed");

        const arrayTasks = filtred.length || currentFilter !== "" ? filtred : tasks;

        if(arrayTasks.length === 0) {
            const tasksContainer = document.querySelector("#tasks-list");
            const noTasksText = document.createElement("LI");
            noTasksText.textContent = "No hay tareas";
            noTasksText.classList.add("no-tasks");
            tasksContainer.appendChild(noTasksText);
            return;
        }
        
        const states = {
            0: "Pendiente",
            1: "Completa"
        };

        arrayTasks.forEach(task => {
            const taskContainer = document.createElement("LI");
            taskContainer.dataset.taskId = task.id;
            taskContainer.classList.add("task");

            const taskName = document.createElement("P");
            taskName.textContent = task.name;
            taskName.onclick = function() {
                showForm(true, {...task});
            }
            
            const optionsDiv = document.createElement("DIV");
            optionsDiv.classList.add("options");

            // Boton cambiar de estado
            const btnTaskState = document.createElement("BUTTON");
            btnTaskState.classList.add("task-state");
            btnTaskState.classList.add(`${states[task.state].toLowerCase()}`);
            btnTaskState.textContent = states[task.state];
            btnTaskState.dataset.taskState = task.state;
            btnTaskState.onclick = function() {
                changeTaskState({...task});
            }

            // Boton eliminar
            const btnDeleteTask = document.createElement("BUTTON");
            btnDeleteTask.classList.add("delete-task");
            btnDeleteTask.dataset.taskId = task.id;
            btnDeleteTask.textContent = "Eliminar";
            btnDeleteTask.onclick = function() {
                confirmDeleteTask({...task});
            }

            optionsDiv.appendChild(btnTaskState);
            optionsDiv.appendChild(btnDeleteTask);

            taskContainer.appendChild(taskName);
            taskContainer.appendChild(optionsDiv);

            const tasksList = document.querySelector("#tasks-list");
            tasksList.appendChild(taskContainer);
        });
    }

    function toggleFilterDisabled(state, selector) {
        const total = tasks.filter(task => task.state === state);
        const radio = document.querySelector(selector);
        radio.disabled = total.length === 0;
    }

    function showForm(edit = false, task = {}) {
        const modal = createModal(edit, task);
        document.querySelector(".dashboard").appendChild(modal); // Lo agrega al dashboard
        document.querySelector("#task").focus(); // Focus para el input
    
        // Bloquear scroll SIEMPRE
        document.body.style.overflow = "hidden";

        const handleEscape = (e) => { //Arrow function si el usuario presiona ESC
            if(e.key === "Escape") closeModal(modal, handleEscape);
        };
        document.addEventListener("keydown", handleEscape);
    }

    function createModal(edit, task) {
        const modal = document.createElement("DIV");
        modal.classList.add("modal");

        modal.innerHTML = `
            <form class="form new-task container">
                <legend>${edit ? 'Editar tarea' : 'Añade una nueva tarea'}</legend>
                <div class="field">
                    <label for="task">Tarea</label>
                    <input
                        class="input-task"
                        type="text"
                        name="task"
                        placeholder="${task.name ? 'Edita la Tarea' : 'Añadir Tarea al Proyecto Actual'}"
                        id="task"
                        value="${task.name ? task.name : ''}"
                    >
                </div>

                <div class="options">
                    <input
                        type="submit"
                        class="submit-new-task"
                        value="${edit ? 'Guardar Cambios' : 'Añadir Tarea'}"
                    >
                    <button type="button" class="close-modal">Cancelar</button>
                </div>
            </form>
        `;

        requestAnimationFrame(() => {
            modal.querySelector(".form").classList.add("animate");
        });

        modal.addEventListener("click", function(e) {
            if(
                e.target.classList.contains("close-modal") ||
                e.target.classList.contains("modal")
            ) {
                e.preventDefault();
                closeModal(modal);
            }
        });

        modal.querySelector("form").addEventListener("submit", function(e) {
            e.preventDefault();
            const taskName = document.querySelector("#task").value.trim();
            if(taskName === "") {
                showAlert("El Nombre de la Tarea es Obligatorio", "error", document.querySelector(".form legend"));
                return;
            }
            
            if(taskName.length > 60) {
                showAlert("El Nombre de la Tarea debe tener como máximo 60 caracteres", "error", document.querySelector(".form legend"));
                return;
            }

            if(edit) {
                task.name = taskName
                updateTask(task);
            } else {
                addTask(taskName);
            }
        });

        const input = modal.querySelector("#task");
        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
        
        return modal;
    }

    function closeModal(modal, escListener = null) {
        const form = modal.querySelector(".form");
        form.classList.add("close");

        setTimeout(() => {
            modal.remove();
            // Restaurar scroll
            document.body.style.overflow = "";
            if(escListener) {
                document.removeEventListener("keydown", escListener);
            }
        }, 390);
    }

    // Muesra un mensaje en la interfaz
    function showAlert(message, type, reference) {
        const previousAlert = document.querySelector(".alert"); // Previene la creacion de multiples alertas
        if(previousAlert) {
            previousAlert.remove();
        }

        const alertWrapper = document.createElement("DIV");
        alertWrapper.classList.add("alert", type);
        
        const alert = document.createElement("P");
        alert.textContent = message;
        alertWrapper.appendChild(alert);
        reference.insertAdjacentElement("afterend", alertWrapper);        
        
        setTimeout(() => alertWrapper.remove(), 5000); // Eliminar la alerta despues de 5 segundos
    }

    // Consultar el servidor para añadir una nueva tarea al proyecto actual
    async function addTask(task) {
        const data = new FormData(); // Construir peticion
        data.append("name", task);
        data.append("projectId", getProject());

        const submitBtn = document.querySelector(".submit-new-task");
        submitBtn.disabled = true;
        submitBtn.value = "Añadiendo...";
        submitBtn.classList.add("disabled"); // Agrega clase visual

        try {
            const url = "http://localhost:3000/api/task"; // Url a la cual va la peticion
            const response = await fetch(url, { // Objeto con la configuracion de la peticion
                method: "POST",
                body: data
            });
            
            const result = await response.json();
            showAlert(result.message, result.type, document.querySelector(".form legend"));

            if(result.type === "exito") {
                // Limpiar el campo de texto
                document.querySelector("#task").value = "";

                // Agregar el objeto de tarea al global de tareas
                const taskObj = {
                    id: result.id,
                    name: task,
                    state: 0,
                    projectId: result.projectId
                }
                
                tasks = [...tasks, taskObj];
                showTasks();

                // Reactivar el botón para poder seguir agregando
                submitBtn.disabled = false;
                submitBtn.value = "Añadir Tarea";
                submitBtn.classList.remove("disabled");
            } else {
                // Si hubo un error del backend, reactivar el botón
                submitBtn.disabled = false;
                submitBtn.value = "Añadir Tarea";
            }
        } catch(error) {
            console.error(error);
            submitBtn.disabled = false;
            submitBtn.value = "Añadir Tarea";
            submitBtn.classList.remove("disabled");
        }
    }

    function changeTaskState(task) {
        const newState = task.state === 1 ? 0 : 1;
        task.state = newState;
        updateTask(task);
    }

    async function updateTask(task) {
        const {id, name, state} = task;
        const data = new FormData();

        data.append("id", id);
        data.append("name", name);
        data.append("projectId", getProject());
        data.append("state", state);

        try {
            const url = "http://localhost:3000/api/task/update";
            const response = await fetch(url, {
                method: "POST",
                body: data
            });
            const result = await response.json();
            
            if(result.response.type === "exito") {
                Swal.fire(
                    result.response.message,
                    result.response.message,
                    "success"
                );
                const modal = document.querySelector(".modal");
                
                if(modal) {
                    modal.remove();
                }

                tasks = tasks.map(memoryTask => memoryTask.id === id ? {...memoryTask, state, name} : memoryTask);
                applyFilter();
                showTasks();
            }
        } catch (error) {
            console.error(error);
        }
    }

    function confirmDeleteTask(task) {
        Swal.fire({
            icon: "warning",
            title: "¿Eliminar Tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTask(task);
            }
        });
    }

    async function deleteTask(task) {
        const {id, name, state} = task;
        const data = new FormData();

        data.append("id", id);
        data.append("name", name);
        data.append("projectId", getProject());
        data.append("state", state);

        try {
            const url = "http://localhost:3000/api/task/delete";
            const response = await fetch(url, {
                method: "POST",
                body: data
            });
            const result = await response.json();

            if(result.result) {
                Swal.fire("¡Eliminado!", result.message, "success");
                tasks = tasks.filter(memoryTask => memoryTask.id !== id);
                applyFilter();
                showTasks();
            }
        } catch (error) {
            console.error(error);
        }
    }
    
    function getProject() {
        const params = new URLSearchParams(window.location.search); // Obtenemos el query string
        const entries = Object.fromEntries(params.entries()); // Nos trae los datos del objeto projectParams
        return entries.url;
    }

    function cleanTasks() {
        const listTasks = document.querySelector("#tasks-list");
        while(listTasks.firstChild) {
            listTasks.removeChild(listTasks.firstChild);
        }
    }
})();