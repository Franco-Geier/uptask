// IIFE
(function() {

    getTasks();

    let tasks = [];

    const newTaskBtn = document.querySelector("#add-task"); // Boton para mostrar el modal de agregar tarea
    newTaskBtn.addEventListener("click", showForm);

    async function getTasks() {
        try {
            const id = getProject();
            const url = `/api/tasks?url=${id}`;
            const response = await fetch(url);
            const result = await response.json();
            tasks = result.tasks;
            showTasks();

        } catch(error) {
            console.error(error);
        }
    }

    function showTasks() {
        cleanTasks();
        if(tasks.length === 0) {
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

        tasks.forEach(task => {
            const taskContainer = document.createElement("LI");
            taskContainer.dataset.taskId = task.id;
            taskContainer.classList.add("task");

            const taskName = document.createElement("P");
            taskName.textContent = task.name;
            
            const optionsDiv = document.createElement("DIV");
            optionsDiv.classList.add("options");

            // Botones
            const btnTaskState = document.createElement("BUTTON");
            btnTaskState.classList.add("task-state");
            btnTaskState.classList.add(`${states[task.state].toLowerCase()}`);
            btnTaskState.textContent = states[task.state];
            btnTaskState.dataset.taskState = task.state;
            btnTaskState.ondblclick = function() {
                changeTaskState({...task});
            }

            const btnDeleteTask = document.createElement("BUTTON");
            btnDeleteTask.classList.add("delete-task");
            btnDeleteTask.dataset.taskId = task.id;
            btnDeleteTask.textContent = "Eliminar";

            optionsDiv.appendChild(btnTaskState);
            optionsDiv.appendChild(btnDeleteTask);

            taskContainer.appendChild(taskName);
            taskContainer.appendChild(optionsDiv);

            const tasksList = document.querySelector("#tasks-list");
            tasksList.appendChild(taskContainer);
        });
    }

    function showForm() {
        const modal = createModal();
        document.querySelector(".dashboard").appendChild(modal); // Lo agrega al dashboard
        document.querySelector("#task").focus(); // Focus para el input
    
        const handleEscape = (e) => { //Arrow function si el usuario presiona ESC
            if(e.key === "Escape") closeModal(modal, handleEscape);
        };
        document.addEventListener("keydown", handleEscape);
    }

    function createModal() {
        const modal = document.createElement("DIV");
        modal.classList.add("modal");

        modal.innerHTML = `
            <form class="form new-task container">
                <legend>Añade una nueva tarea</legend>
                <div class="field">
                    <label for="task">Tarea</label>
                    <input
                        class="input-task"
                        type="text"
                        name="task"
                        placeholder="Añadir Tarea al Proyecto Actual"
                        id="task"
                    >
                </div>

                <div class="options">
                    <input
                        type="submit"
                        class="submit-new-task"
                        value="Añadir Tarea"
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
            submitFormNewTask();
        });

        return modal;
    }

    function closeModal(modal, escListener = null) {
        const form = modal.querySelector(".form");
        form.classList.add("close");

        setTimeout(() => {
            modal.remove();
            if(escListener) {
                document.removeEventListener("keydown", escListener);
            }
        }, 390);
    }

    function submitFormNewTask() {
        const task = document.querySelector("#task").value.trim();
        if(task === "") {
            showAlert("El Nombre de la Tarea es Obligatorio", "error", document.querySelector(".form legend"));
            return;
        }
        
        if (task.length > 60) {
            showAlert("El Nombre de la Tarea debe tener como máximo 60 caracteres", "error", document.querySelector(".form legend"));
            return;
        }
        addTask(task);
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
        const {id, name, projectId, state} = task;
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
                showAlert(result.response.message, result.response.type,
                          document.querySelector(".container-new-task"));
            }

            tasks = tasks.map(memoryTask => {
                if(memoryTask.id === id) {
                    memoryTask.state = state;
                } 
                return memoryTask;
            });
            showTasks();
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