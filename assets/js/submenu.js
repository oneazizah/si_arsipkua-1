// SUBMENU ACCORDION
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.submenu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            const parent = this.closest('.submenu-item');
            const submenu = parent.querySelector('.submenu');

            document.querySelectorAll('.submenu-item').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('open');
                    item.querySelector('.submenu').style.display = "none";
                }
            });

            const isOpen = submenu.style.display === "block";
            submenu.style.display = isOpen ? "none" : "block";
            parent.classList.toggle("open", !isOpen);
        });
    });

    // AUTO OPEN SUBMENU
    document.querySelectorAll(".submenu a").forEach(link => {
        if (link.classList.contains("active-sub")) {
            const parent = link.closest(".submenu-item");
            const submenu = parent.querySelector(".submenu");

            submenu.style.display = "block";
            parent.classList.add("open");
        }
    });
});
