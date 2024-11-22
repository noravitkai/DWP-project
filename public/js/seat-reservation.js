const maxSeats = 5;
const seatCheckboxes = document.querySelectorAll(".seat-checkbox");
const form = document.getElementById("seatReservationForm");

seatCheckboxes.forEach((checkbox) => {
  checkbox.addEventListener("change", () => {
    const selectedSeats = Array.from(seatCheckboxes).filter((cb) => cb.checked);
    if (selectedSeats.length > maxSeats) {
      alert(`You can only select up to ${maxSeats} seats.`);
      checkbox.checked = false;
    }
  });
});

form.addEventListener("submit", (e) => {
  const selectedSeats = Array.from(seatCheckboxes).filter((cb) => cb.checked);
  if (selectedSeats.length === 0) {
    alert("Please select at least one seat.");
    e.preventDefault();
  }
});
