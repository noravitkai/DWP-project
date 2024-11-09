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
  fullName = "",
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
    <input type="text" name="ActorNames[]" value="${fullName}" placeholder="Name" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
    <input type="text" name="ActorRoles[]" value="${role}" placeholder="Role" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
    <button type="button" class="actor-btn text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
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
      addActorField(containerId, "", "", false, limitMessageId)
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
          (actor) => `<li>${actor.FullName} as ${actor.Role}</li>`
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
        actor.FullName,
        actor.Role,
        index === 0,
        "actorLimitMessageEdit"
      );
    });
  } else {
    addActorField("actorContainerEdit", "", "", true, "actorLimitMessageEdit");
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
  addActorField("actorContainer", "", "", true);
  showModal("addMovieModal");
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
      addActorField("actorContainer", "", "", true)
    );
  }
});
