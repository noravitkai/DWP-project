function decodeHtmlEntities(str) {
  var txt = document.createElement("textarea");
  txt.innerHTML = str;
  return txt.value;
}

function showModal(modalId) {
  const modal = document.getElementById(modalId);
  const backdrop = document.getElementById("modalBackdrop");

  if (modal && backdrop) {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    backdrop.classList.remove("hidden");
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";
  }
}

function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  const backdrop = document.getElementById("modalBackdrop");

  if (modal && backdrop) {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    backdrop.classList.add("hidden");
    document.documentElement.style.overflow = "";
    document.body.style.overflow = "";
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

// Movie modals

function showPreviewMovieModal(movie) {
  ["Title", "Subtitle", "Genre", "Director", "MovieDescription"].forEach(
    (field) => {
      movie[field] = decodeHtmlEntities(movie[field]);
    }
  );

  populateModalFields("preview", movie, {
    Title: "MovieTitle",
    MovieID: "MovieID",
    Subtitle: "MovieSubtitle",
    Duration: "MovieDuration",
    Genre: "MovieGenre",
    ReleaseYear: "MovieReleaseYear",
    Director: "MovieDirector",
    MovieDescription: "MovieDescription",
  });

  const previewImage = document.getElementById("previewMovieImage");
  if (previewImage && movie.ImageURL) {
    previewImage.src = movie.ImageURL;
    previewImage.classList.remove("hidden");
  } else if (previewImage) {
    previewImage.classList.add("hidden");
  }

  const castList = document.getElementById("previewMovieCast");
  castList.innerHTML = "";

  if (movie.Actors && movie.Actors.length > 0) {
    movie.Actors.forEach((actor) => {
      const listItem = document.createElement("li");
      listItem.textContent = `${actor.FullName} as ${actor.Role}`;
      castList.appendChild(listItem);
    });
  } else {
    const listItem = document.createElement("li");
    listItem.textContent = "No cast information available.";
    castList.appendChild(listItem);
  }

  showModal("previewModal");
}

function hidePreviewMovieModal() {
  hideModal("previewModal");
}

function showEditMovieModal(movie) {
  ["Title", "Subtitle", "Genre", "Director", "MovieDescription"].forEach(
    (field) => {
      movie[field] = decodeHtmlEntities(movie[field]);
    }
  );

  document.getElementById("editMovieID").value = movie.MovieID;
  document.getElementById("editMovieTitle").value = movie.Title;
  document.getElementById("editMovieSubtitle").value = movie.Subtitle;
  document.getElementById("editMovieReleaseYear").value = movie.ReleaseYear;
  document.getElementById("editMovieGenre").value = movie.Genre;
  document.getElementById("editMovieDirector").value = movie.Director;
  document.getElementById("editMovieDuration").value = movie.Duration;
  document.getElementById("editMovieDescription").value =
    movie.MovieDescription;

  showModal("editModal");
}

function hideEditMovieModal() {
  hideModal("editModal");
}

function showDeleteMovieModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);
  document.getElementById("deleteMovieID").value = movie.MovieID;
  document.getElementById("deleteMovieTitle").textContent = movie.Title;
  showModal("deleteModal");
}

function hideDeleteMovieModal() {
  hideModal("deleteModal");
}

function showAddMovieModal() {
  const addMovieForm = document.getElementById("addMovieForm");
  if (addMovieForm) addMovieForm.reset();
  showModal("addModal");
}

function hideAddMovieModal() {
  hideModal("addModal");
}

let actorCount = 1;
const MAX_ACTORS = 10;

function addActorField() {
  if (actorCount >= MAX_ACTORS) {
    document.getElementById("actorLimitMessage").classList.remove("hidden");
    return;
  }

  actorCount++;

  const container = document.getElementById("actorContainer");

  const actorEntry = document.createElement("div");
  actorEntry.classList.add("actor-entry", "flex", "gap-2");

  actorEntry.innerHTML = `
    <input type="text" name="ActorNames[]" placeholder="Full Name" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
    <input type="text" name="ActorRoles[]" placeholder="Role" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
    <button type="button" class="remove-actor-btn text-zinc-600 hover:text-zinc-900">
      <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
      </svg>
    </button>
  `;

  container.appendChild(actorEntry);

  actorEntry
    .querySelector(".remove-actor-btn")
    .addEventListener("click", () => {
      container.removeChild(actorEntry);
      actorCount--;

      document.getElementById("actorLimitMessage").classList.add("hidden");
      document.getElementById("addActorBtn").disabled = false;
    });

  if (actorCount >= MAX_ACTORS) {
    document.getElementById("addActorBtn").disabled = true;
    document.getElementById("actorLimitMessage").classList.remove("hidden");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("addActorBtn")
    .addEventListener("click", addActorField);
});
// News modals

function showPreviewNewsModal(news) {
  ["Title", "Category", "Content"].forEach((field) => {
    news[field] = decodeHtmlEntities(news[field]);
  });

  populateModalFields("preview", news, {
    Title: "NewsTitleText",
    Category: "NewsCategory",
    DatePublished: "NewsDatePublished",
    Content: "NewsContent",
  });

  const previewImage = document.getElementById("previewNewsImage");
  if (previewImage && news.ImageURL) {
    previewImage.src = news.ImageURL;
    previewImage.classList.remove("hidden");
  } else if (previewImage) {
    previewImage.classList.add("hidden");
  }

  showModal("previewNewsModal");
}

function hidePreviewNewsModal() {
  hideModal("previewNewsModal");
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

function hideEditNewsModal() {
  hideModal("editNewsModal");
}

function showDeleteNewsModal(news) {
  news.Title = decodeHtmlEntities(news.Title);
  document.getElementById("deleteNewsID").value = news.NewsID;
  document.getElementById("deleteNewsTitle").textContent = news.Title;
  showModal("deleteNewsModal");
}

function hideDeleteNewsModal() {
  hideModal("deleteNewsModal");
}

function showAddNewsModal() {
  const addNewsForm = document.getElementById("addNewsForm");
  if (addNewsForm) addNewsForm.reset();
  showModal("addNewsModal");
}

function hideAddNewsModal() {
  hideModal("addNewsModal");
}

// Image preview handlers

const movieImageInput = document.getElementById("movieImage");
if (movieImageInput) {
  movieImageInput.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        const previewImage = document.getElementById("imagePreview");
        if (previewImage) {
          previewImage.src = event.target.result;
          previewImage.classList.remove("hidden");
        }
      };
      reader.readAsDataURL(file);
    }
  });
}

const newsImageInput = document.getElementById("newsImage");
if (newsImageInput) {
  newsImageInput.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        const previewImage = document.getElementById("previewNewsImage");
        if (previewImage) {
          previewImage.src = event.target.result;
          previewImage.classList.remove("hidden");
        }
      };
      reader.readAsDataURL(file);
    }
  });
}

// Profile modals

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

  document.getElementById("editCustomerID").value = customer.CustomerID;
  document.getElementById("editFirstName").value = customer.FirstName;
  document.getElementById("editLastName").value = customer.LastName;
  document.getElementById("editEmail").value = customer.Email;
  document.getElementById("editPhoneNumber").value = customer.PhoneNumber;
  document.getElementById("editSuiteNumber").value = customer.SuiteNumber;
  document.getElementById("editStreet").value = customer.Street;
  document.getElementById("editCountry").value = customer.Country;
  document.getElementById("editPostalCode").value = customer.PostalCode;
  document.getElementById("editCity").value = customer.City;

  showModal("editProfileModal");
}

function hideEditProfileModal() {
  hideModal("editProfileModal");
}

function showUpdatePasswordModal() {
  showModal("updatePasswordModal");
}

function hideUpdatePasswordModal() {
  hideModal("updatePasswordModal");
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".add-actor-btn").forEach((button) => {
    button.addEventListener("click", addActorField);
  });
});
