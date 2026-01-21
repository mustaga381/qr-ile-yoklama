import "./bootstrap";

const openBtn = document.querySelector("[data-drawer-open]");
const closeBtn = document.querySelector("[data-drawer-close]");
const drawer = document.querySelector("[data-drawer]");
const overlay = document.querySelector("[data-drawer-overlay]");

function openDrawer() {
    if (!drawer || !overlay) return;
    drawer.classList.remove("hidden");
    overlay.classList.remove("hidden");

    // animasyon için bir tick bekle
    requestAnimationFrame(() => {
        drawer.classList.remove("-translate-x-full");
    });

    document.body.style.overflow = "hidden";
}

function closeDrawer() {
    if (!drawer || !overlay) return;

    drawer.classList.add("-translate-x-full");
    overlay.classList.add("hidden");

    // animasyon bitince gizle
    setTimeout(() => {
        drawer.classList.add("hidden");
    }, 200);

    document.body.style.overflow = "";
}

if (openBtn) openBtn.addEventListener("click", openDrawer);
if (closeBtn) closeBtn.addEventListener("click", closeDrawer);
if (overlay) overlay.addEventListener("click", closeDrawer);
