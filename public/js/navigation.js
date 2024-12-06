document.addEventListener("DOMContentLoaded", function () {
  function getElement(id) {
    return document.getElementById(id);
  }

  function initializeNavigation(prefix) {
    const openButton = getElement(`${prefix}-open-menu`);
    const closeButton = getElement(`${prefix}-close-menu`);
    const menu = getElement(`${prefix}-hamburger-menu`);
    const container = getElement(`${prefix}-menu-container`);
    const backdrop = getElement(`${prefix}-backdrop`);

    if (!openButton || !closeButton || !menu || !container || !backdrop) {
      return;
    }

    openButton.addEventListener("click", function () {
      menu.style.display = "block";
      backdrop.style.display = "block";
      setTimeout(() => {
        container.classList.remove("-translate-x-full");
        backdrop.classList.remove("opacity-0");
        backdrop.classList.add("opacity-100");
      }, 50);
    });

    closeButton.addEventListener("click", function () {
      container.classList.add("-translate-x-full");
      backdrop.classList.remove("opacity-100");
      backdrop.classList.add("opacity-0");
      setTimeout(() => {
        menu.style.display = "none";
        backdrop.style.display = "none";
      }, 300);
    });

    backdrop.addEventListener("click", function () {
      closeButton.click();
    });

    menu
      .querySelectorAll(`.${prefix}-hamburger-menu-link`)
      .forEach((menuLink) => {
        menuLink.addEventListener("click", function () {
          closeButton.click();
        });
      });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && menu.style.display === "block") {
        closeButton.click();
      }
    });
  }

  initializeNavigation("admin");
  initializeNavigation("frontend");
});
