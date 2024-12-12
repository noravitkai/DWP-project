function decodeHtmlEntities(str) {
  const txt = document.createElement("textarea");
  txt.innerHTML = str;
  return txt.value;
}

function toggleModal(modalId, show = true) {
  const modal = document.getElementById(modalId);
  const backdrop = document.getElementById("modalBackdrop");

  if (modal && backdrop) {
    if (show) {
      modal.classList.remove("hidden");
      modal.classList.add("flex");
      backdrop.classList.remove("hidden");
      document.documentElement.style.overflow = "hidden";
      document.body.style.overflow = "hidden";
    } else {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      backdrop.classList.add("hidden");
      document.documentElement.style.overflow = "";
      document.body.style.overflow = "";
    }
  }
}

function populateModalFields(modalPrefix, data, fieldMapping) {
  Object.keys(fieldMapping).forEach((field) => {
    const element = document.getElementById(modalPrefix + fieldMapping[field]);
    if (element) {
      element.innerHTML = data[field] || "";
    }
  });
}

function showModal(modalId) {
  toggleModal(modalId, true);
}

function hideModal(modalId) {
  toggleModal(modalId, false);
}

function addActorField(
  containerId,
  firstName = "",
  lastName = "",
  role = "",
  showAddButton = false,
  limitMessageId = "actorLimitMessage"
) {
  const container = document.getElementById(containerId);
  const currentActorCount =
    container.getElementsByClassName("actor-entry").length;

  const limitMessage = document.getElementById(limitMessageId);

  if (currentActorCount >= 10) {
    limitMessage.classList.remove("hidden");
    return;
  } else {
    limitMessage.classList.add("hidden");
  }

  const actorEntry = document.createElement("div");
  actorEntry.classList.add("actor-entry", "flex", "gap-2");

  actorEntry.innerHTML = `
  <div class="flex flex-col w-full">
    <span class="text-zinc-500 text-xs block mb-1">Max. 50 characters</span>
    <input type="text" name="ActorFirstNames[]" value="${firstName}" placeholder="First Name" maxlength="50" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
  </div>
  <div class="flex flex-col w-full">
    <span class="text-zinc-500 text-xs block mb-1">Max. 50 characters</span>
    <input type="text" name="ActorLastNames[]" value="${lastName}" placeholder="Last Name" maxlength="50" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
  </div>
  <div class="flex flex-col w-full">
    <span class="text-zinc-500 text-xs block mb-1">Max. 100 characters</span>
    <input type="text" name="ActorRoles[]" value="${role}" placeholder="Role" maxlength="255" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
  </div>
  <button type="button" class="actor-btn pt-5 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      ${
        showAddButton
          ? `<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />`
          : `<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />`
      }
    </svg>
  </button>
`;

  container.appendChild(actorEntry);

  const actorBtn = actorEntry.querySelector(".actor-btn");
  if (showAddButton) {
    actorBtn.addEventListener("click", () =>
      addActorField(containerId, "", "", "", false, limitMessageId)
    );
  } else {
    actorBtn.addEventListener("click", () => {
      container.removeChild(actorEntry);
      limitMessage.classList.add("hidden");
    });
  }
}

function showPreviewMovieModal(movie) {
  ["Title", "Subtitle", "Genre", "Director", "MovieDescription"].forEach(
    (field) => {
      movie[field] = decodeHtmlEntities(movie[field]);
    }
  );

  populateModalFields("previewMovie", movie, {
    Title: "Title",
    MovieID: "ID",
    Subtitle: "Subtitle",
    Duration: "Duration",
    Genre: "Genre",
    ReleaseYear: "ReleaseYear",
    Director: "Director",
    MovieDescription: "Description",
  });

  const previewImage = document.getElementById("previewMovieImage");
  previewImage.src = movie.ImageURL || "";
  previewImage.classList.toggle("hidden", !movie.ImageURL);

  const castList = document.getElementById("previewMovieCast");
  castList.innerHTML =
    movie.Actors && movie.Actors.length > 0
      ? movie.Actors.map(
          (actor) =>
            `<li>${actor.FirstName} ${actor.LastName} as ${actor.Role}</li>`
        ).join("")
      : "<li>No cast information available.</li>";

  showModal("previewMovieModal");
}

function showEditMovieModal(movie) {
  ["Title", "Subtitle", "Genre", "Director", "MovieDescription"].forEach(
    (field) => {
      movie[field] = decodeHtmlEntities(movie[field] || "");
    }
  );

  document.getElementById("editMovieID").value = movie.MovieID || "";
  document.getElementById("editMovieTitle").value = movie.Title || "";
  document.getElementById("editMovieSubtitle").value = movie.Subtitle || "";
  document.getElementById("editMovieReleaseYear").value =
    movie.ReleaseYear || "";
  document.getElementById("editMovieGenre").value = movie.Genre || "";
  document.getElementById("editMovieDirector").value = movie.Director || "";
  document.getElementById("editMovieDuration").value = movie.Duration || "";
  document.getElementById("editMovieDescription").value =
    movie.MovieDescription || "";

  const actorContainer = document.getElementById("actorContainerEdit");
  actorContainer.innerHTML = "";

  if (movie.Actors && movie.Actors.length > 0) {
    movie.Actors.forEach((actor, index) => {
      addActorField(
        "actorContainerEdit",
        actor.FirstName || "",
        actor.LastName || "",
        actor.Role || "",
        index === 0,
        "actorLimitMessageEdit"
      );
    });
  } else {
    addActorField(
      "actorContainerEdit",
      "",
      "",
      "",
      true,
      "actorLimitMessageEdit"
    );
  }

  showModal("editMovieModal");
}

function showDeleteMovieModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);
  document.getElementById("deleteMovieID").value = movie.MovieID;
  document.getElementById("deleteMovieTitle").textContent = movie.Title;
  showModal("deleteMovieModal");
}

function showAddMovieModal() {
  const addMovieForm = document.getElementById("addMovieForm");
  if (addMovieForm) addMovieForm.reset();
  const actorContainer = document.getElementById("actorContainer");
  actorContainer.innerHTML = "";
  addActorField("actorContainer", "", "", "", true);
  showModal("addMovieModal");
}

function showAddScreeningModal() {
  const addScreeningForm = document.getElementById("addScreeningForm");
  if (addScreeningForm) addScreeningForm.reset();
  showModal("addScreeningModal");
}

function showPreviewScreeningModal(screening) {
  ["MovieTitle", "RoomLabel"].forEach((field) => {
    screening[field] = decodeHtmlEntities(screening[field]);
  });

  if (screening.Price) {
    screening.Price = `${screening.Price} DKK`;
  }

  populateModalFields("previewScreening", screening, {
    ScreeningID: "ID",
    MovieTitle: "MovieTitle",
    MovieDuration: "MovieDuration",
    ScreeningDate: "Date",
    ScreeningTime: "Time",
    RoomLabel: "Room",
    Price: "Price",
  });

  showModal("previewScreeningModal");
}

function showEditScreeningModal(screening) {
  screening.MovieTitle = decodeHtmlEntities(screening.MovieTitle || "Unknown");

  document.getElementById("editScreeningMovieTitle").textContent =
    screening.MovieTitle;

  document.getElementById("editScreeningID").value =
    screening.ScreeningID || "";
  document.getElementById("editScreeningDate").value =
    screening.ScreeningDate || "";
  document.getElementById("editScreeningTime").value =
    screening.ScreeningTime || "";
  document.getElementById("editScreeningRoomID").value = screening.RoomID || "";
  document.getElementById("editScreeningPrice").value = screening.Price || "";

  showModal("editScreeningModal");
}

function showDeleteScreeningModal(screening) {
  screening.MovieTitle = decodeHtmlEntities(screening.MovieTitle);
  document.getElementById("deleteScreeningID").value = screening.ScreeningID;
  document.getElementById(
    "deleteScreeningMovieTitle"
  ).textContent = `Screening of "${screening.MovieTitle}" on ${screening.ScreeningDate} at ${screening.ScreeningTime}`;
  showModal("deleteScreeningModal");
}

function showPreviewNewsModal(news) {
  ["Title", "Category", "Content"].forEach((field) => {
    news[field] = decodeHtmlEntities(news[field]);
  });

  populateModalFields("previewNews", news, {
    Title: "TitleText",
    Category: "Category",
    DatePublished: "DatePublished",
    Content: "Content",
  });

  const previewImage = document.getElementById("previewNewsImage");
  previewImage.src = news.ImageURL || "";
  previewImage.classList.toggle("hidden", !news.ImageURL);

  showModal("previewNewsModal");
}

function showEditNewsModal(news) {
  ["Title", "Category", "Content"].forEach((field) => {
    news[field] = decodeHtmlEntities(news[field]);
  });

  document.getElementById("editNewsID").value = news.NewsID;
  document.getElementById("editNewsTitle").value = news.Title;
  document.getElementById("editNewsCategory").value = news.Category;
  document.getElementById("editNewsDatePublished").value = news.DatePublished;
  document.getElementById("editNewsContent").value = news.Content;

  showModal("editNewsModal");
}

function showDeleteNewsModal(news) {
  news.Title = decodeHtmlEntities(news.Title);
  document.getElementById("deleteNewsID").value = news.NewsID;
  document.getElementById("deleteNewsTitle").textContent = news.Title;
  showModal("deleteNewsModal");
}

function showAddNewsModal() {
  const addNewsForm = document.getElementById("addNewsForm");
  if (addNewsForm) addNewsForm.reset();
  showModal("addNewsModal");
}

function showEditCinemaModal(cinema) {
  ["Tagline", "Description", "PhoneNumber", "Email", "OpeningHours"].forEach(
    (field) => {
      cinema[field] = decodeHtmlEntities(cinema[field] || "");
    }
  );

  document.getElementById("editCinemaID").value = cinema.CinemaID || "";
  document.getElementById("editCinemaTitle").value = cinema.Tagline || "";
  document.getElementById("editCinemaDescription").value =
    cinema.Description || "";
  document.getElementById("editCinemaPhone").value = cinema.PhoneNumber || "";
  document.getElementById("editCinemaEmail").value = cinema.Email || "";
  document.getElementById("editCinemaOpeningHours").value =
    cinema.OpeningHours || "";

  showModal("editCinemaModal");
}

function showPreviewReservationModal(reservation) {
  ["ReservationID", "NumberOfSeats", "Status"].forEach((field) => {
    reservation[field] = decodeHtmlEntities(reservation[field] || "");
  });

  reservation["ScreeningID"] = decodeHtmlEntities(
    reservation["ScreeningID"] || ""
  );
  reservation["CreatedAt"] = decodeHtmlEntities(reservation["CreatedAt"] || "");

  const screeningInfoHTML = `
    <p><span class="text-xs font-semibold text-zinc-600 uppercase">ID:</span> ${
      reservation["ScreeningID"]
    }</p>
    <p><span class="text-xs font-semibold text-zinc-600 uppercase">Movie:</span> ${decodeHtmlEntities(
      reservation["MovieTitle"] || "N/A"
    )}</p>
  `;
  document.getElementById("previewReservationScreeningInfo").innerHTML =
    screeningInfoHTML;

  let customerInfoHTML = "";
  if (reservation["CustomerID"]) {
    customerInfoHTML = `
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Name:</span> ${decodeHtmlEntities(
            reservation["CustomerFirstName"] || ""
          )} ${decodeHtmlEntities(reservation["CustomerLastName"] || "")}</p>
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Email:</span> ${decodeHtmlEntities(
            reservation["CustomerEmail"] || ""
          )}</p>
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Phone:</span> ${decodeHtmlEntities(
            reservation["CustomerPhoneNumber"] || ""
          )}</p>
      `;
  } else {
    customerInfoHTML = `
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Guest Name:</span> ${decodeHtmlEntities(
            reservation["GuestFirstName"] || ""
          )} ${decodeHtmlEntities(reservation["GuestLastName"] || "")}</p>
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Guest Email:</span> ${decodeHtmlEntities(
            reservation["GuestEmail"] || ""
          )}</p>
          <p><span class="text-xs font-semibold text-zinc-600 uppercase">Guest Phone:</span> ${decodeHtmlEntities(
            reservation["GuestPhoneNumber"] || ""
          )}</p>
      `;
  }

  document.getElementById("previewReservationID").textContent =
    reservation["ReservationID"];
  document.getElementById("previewReservationNumberOfSeats").textContent =
    reservation["NumberOfSeats"];
  document.getElementById("previewReservationCreatedAt").textContent =
    reservation["CreatedAt"];
  document.getElementById("previewReservationStatus").textContent =
    reservation["Status"];
  document.getElementById("previewReservationCustomerInfo").innerHTML =
    customerInfoHTML;

  const pricePerSeat = parseFloat(reservation["Price"]) || 0;
  const numberOfSeats = parseInt(reservation["NumberOfSeats"]) || 0;
  const totalPrice = pricePerSeat * numberOfSeats;

  document.getElementById(
    "previewReservationTotalPrice"
  ).textContent = `${totalPrice.toFixed(2)} DKK`;

  const seatsList = document.getElementById("previewReservationSeats");
  seatsList.innerHTML = "";
  if (
    reservation["Seats"] &&
    Array.isArray(reservation["Seats"]) &&
    reservation["Seats"].length > 0
  ) {
    reservation["Seats"].forEach((seat) => {
      seatsList.innerHTML += `<li>Row ${decodeHtmlEntities(
        seat["RowLabel"] || ""
      )}, Seat ${decodeHtmlEntities(seat["SeatNumber"] || "")}</li>`;
    });
  } else {
    seatsList.innerHTML = "<li>No seat information available.</li>";
  }

  showModal("previewReservationModal");
}

function showEditProfileModal(customer) {
  [
    "FirstName",
    "LastName",
    "Email",
    "PhoneNumber",
    "SuiteNumber",
    "Street",
    "Country",
    "PostalCode",
    "City",
  ].forEach((field) => {
    customer[field] = decodeHtmlEntities(customer[field] || "");
  });

  document.getElementById("editCustomerID").value = customer.CustomerID || "";
  document.getElementById("editFirstName").value = customer.FirstName || "";
  document.getElementById("editLastName").value = customer.LastName || "";
  document.getElementById("editEmail").value = customer.Email || "";
  document.getElementById("editPhoneNumber").value = customer.PhoneNumber || "";
  document.getElementById("editSuiteNumber").value = customer.SuiteNumber || "";
  document.getElementById("editStreet").value = customer.Street || "";
  document.getElementById("editCountry").value = customer.Country || "";
  document.getElementById("editPostalCode").value = customer.PostalCode || "";
  document.getElementById("editCity").value = customer.City || "";

  showModal("editProfileModal");
}

function setupImagePreview(inputId, previewId) {
  const input = document.getElementById(inputId);
  const previewImage = document.getElementById(previewId);
  if (input) {
    input.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          previewImage.src = event.target.result;
          previewImage.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
      }
    });
  }
}

setupImagePreview("movieImage", "imagePreview");
setupImagePreview("newsImage", "previewNewsImage");

document.addEventListener("DOMContentLoaded", () => {
  const addActorButton = document.getElementById("addActorBtn");
  if (addActorButton) {
    addActorButton.addEventListener("click", () =>
      addActorField("actorContainer", "", "", "", true)
    );
  }
});
