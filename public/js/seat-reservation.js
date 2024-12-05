document.addEventListener("DOMContentLoaded", function () {
  const maxSeats = 5;
  const seatCheckboxes = document.querySelectorAll(".seat-checkbox");
  const seatForm = document.getElementById("seatReservationForm");

  if (seatCheckboxes && seatForm) {
    seatCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        const selectedSeats = Array.from(seatCheckboxes).filter(
          (cb) => cb.checked
        );
        if (selectedSeats.length > maxSeats) {
          alert(`You can only select up to ${maxSeats} seats.`);
          checkbox.checked = false;
        }
      });
    });

    seatForm.addEventListener("submit", (e) => {
      const selectedSeats = Array.from(seatCheckboxes).filter(
        (cb) => cb.checked
      );
      if (selectedSeats.length === 0) {
        alert("Please select at least one seat.");
        e.preventDefault();
      }
    });
  }

  const body = document.body;
  const reservationId = body.getAttribute("data-reservation-id");
  const screeningId = body.getAttribute("data-screening-id");
  const csrfToken = body.getAttribute("data-csrf-token") || "";
  const proceedForm = document.getElementById("proceedToPaymentForm");
  const backToHomeBtn = document.getElementById("backToHomeBtn");

  if (reservationId && screeningId && proceedForm && backToHomeBtn) {
    let timer;

    function cancelReservation(callback) {
      const formData = new URLSearchParams();
      formData.append("reservation_id", reservationId);
      formData.append("csrf_token", csrfToken);

      fetch("release_reservation.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData.toString(),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log("Reservation canceled successfully.");
          } else if (data.status === "info") {
            console.log(data.message);
          } else {
            console.error("Error canceling reservation:", data.message);
          }
        })
        .catch((error) => {
          console.error("Error canceling reservation:", error);
        })
        .finally(() => {
          if (typeof callback === "function") {
            callback();
          }
        });
    }

    timer = setTimeout(cancelReservation, 900000);

    proceedForm.addEventListener("submit", function () {
      clearTimeout(timer);
    });

    backToHomeBtn.addEventListener("click", function (event) {
      event.preventDefault();

      const loadingMessage = document.createElement("div");
      loadingMessage.innerText = "Cancelling your reservation...";
      loadingMessage.className = "loading-message";
      document.body.appendChild(loadingMessage);

      cancelReservation(() => {
        document.body.removeChild(loadingMessage);
        window.location.href = "home_page.php?screening_id=" + screeningId;
      });
    });

    window.addEventListener("beforeunload", function (e) {
      const params = new URLSearchParams();
      params.append("reservation_id", reservationId);
      params.append("csrf_token", csrfToken);
      navigator.sendBeacon("release_reservation.php", params.toString());
    });
  }
});
