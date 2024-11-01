const openMenuButton = document.getElementById("open-menu");
const closeMenuButton = document.getElementById("close-menu");
const hamburgerMenu = document.getElementById("hamburger-menu");
const menuContainer = document.getElementById("menu-container");
const backdrop = document.getElementById("backdrop");

openMenuButton.addEventListener("click", function () {
  hamburgerMenu.style.display = "block";
  backdrop.style.display = "block";
  setTimeout(() => {
    menuContainer.classList.remove("-translate-x-full");
    backdrop.classList.remove("opacity-0");
    backdrop.classList.add("opacity-100");
  }, 50);
});

closeMenuButton.addEventListener("click", function () {
  menuContainer.classList.add("-translate-x-full");
  backdrop.classList.remove("opacity-100");
  backdrop.classList.add("opacity-0");
  setTimeout(() => {
    hamburgerMenu.style.display = "none";
    backdrop.style.display = "none";
  }, 300);
});

backdrop.addEventListener("click", function () {
  closeMenuButton.click();
});

document.querySelectorAll(".hamburger-menu-link").forEach((menuLink) => {
  menuLink.addEventListener("click", function () {
    closeMenuButton.click();
  });
});
