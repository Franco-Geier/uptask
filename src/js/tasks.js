// IIFE
(function() {
    const newTaskBtn = document.querySelector("#add-task"); // Boton para mostrar el modal de agregar tarea
    newTaskBtn.addEventListener("click", showForm);

    function showForm() {
        const modal = createModal();
        document.body.appendChild(modal); // Lo agrega al Body
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
    
    // function showForm() {
    //     const modal = document.createElement("DIV");
    //     modal.classList.add("modal");

    //     modal.innerHTML = `
    //         <form class="form new-task container">
    //             <legend>Añade una nueva tarea</legend>
    //             <div class="field">
    //                 <label>Tarea</label>
    //                 <input
    //                     type="text"
    //                     name="task"
    //                     placeholder="Añadir Tarea al Proyecto Actual"
    //                     id="task"
    //                 >
    //             </div>

    //             <div class="options">
    //                 <input
    //                     type="submit"
    //                     class="submit-new-task"
    //                     value="Añadir Tarea"
    //                 >
    //                 <button type="button" class="close-modal">Cancelar</button>
    //             </div>
    //         </form>
    //     `;

    //     setTimeout(() => {
    //         const form = document.querySelector(".form");
    //         form.classList.add("animate");
    //     }, 0);

    //     modal.addEventListener("click", function(e) {
    //         e.preventDefault();

    //         if(e.target.classList.contains("close-modal") || e.target.classList.contains("modal")) {
    //             const form = document.querySelector(".form");
    //             form.classList.add("close");

    //             setTimeout(() => {
    //                 modal.remove();
    //             }, 390);
    //         }
    //     })

    //     document.querySelector("body").appendChild(modal);
    //     document.querySelector("#task").focus();

    //     document.addEventListener("keydown", handleEscape);

    //     function handleEscape(e) {
    //         if (e.key === "Escape") {
    //             const form = document.querySelector(".form");
    //             form.classList.add("close");
    //             setTimeout(() => {
    //                 modal.remove();
    //                 document.removeEventListener("keydown", handleEscape); // Limpieza
    //             }, 390);
    //         }
    //     }
    // }
    
})();