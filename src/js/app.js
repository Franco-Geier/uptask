const mobileMenutBtn = document.querySelector("#mobile-menu");
const closeMenuBtn = document.querySelector("#close-menu");
const sidebar = document.querySelector(".sidebar");

if(mobileMenutBtn) {
    mobileMenutBtn.addEventListener("click", function() {
        sidebar.classList.add("show");
        document.body.style.overflow = "hidden";
    });
}

if(closeMenuBtn) {
    closeMenuBtn.addEventListener("click", function() {
        sidebar.classList.add("hide");
        setTimeout(() => {
            sidebar.classList.remove("show");
            sidebar.classList.remove("hide");
        }, 500);
    });
}

// Cerrar con tecla ESC
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" && sidebar.classList.contains("show")) {
        closeSidebar();
    }
});

// Cerrar haciendo click en el overlay
sidebar.addEventListener("click", function(e) {
    if (e.target === sidebar) {
        closeSidebar();
    }
});

// Elimina la clase de show, en un tamaño de tablet y mayores
window.addEventListener("resize", function() {
    const screenWidth = document.body.clientWidth;
    if(screenWidth >= 768) {
        sidebar.classList.remove("show");
    }
});

function closeSidebar() {
    sidebar.classList.add("hide");
    setTimeout(() => {
        sidebar.classList.remove("show", "hide");
        document.body.style.overflow = "";
    }, 500);
}