
function toggleMenu() {
    const menu = document.getElementById("navMenu");

    menu.classList.toggle("active");
}

document.querySelectorAll(".nav-menu a").forEach(link => {
    link.addEventListener("click", function() {
        document.getElementById("navMenu")
            .classList.remove("active");
    });
});
