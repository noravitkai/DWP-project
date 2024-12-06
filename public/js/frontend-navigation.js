const menuToggle = document.getElementById('menu-toggle');
const dropdownMenu = document.getElementById('dropdown-menu');

menuToggle.addEventListener('click', function (event) {
  dropdownMenu.classList.toggle('hidden');
  event.stopPropagation();
});

document.addEventListener('click', function (event) {
  const isClickInside = dropdownMenu.contains(event.target) || menuToggle.contains(event.target);
  if (!isClickInside) {
    dropdownMenu.classList.add('hidden');
  }
});