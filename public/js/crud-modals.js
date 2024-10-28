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

function populateModalFields(modalPrefix, movie) {
  document.getElementById(modalPrefix + "MovieTitle").innerHTML =
    movie.Title || "";
  document.getElementById(modalPrefix + "MovieID").innerHTML =
    movie.MovieID || "";
  document.getElementById(modalPrefix + "MovieSubtitle").innerHTML =
    movie.Subtitle || "";
  document.getElementById(modalPrefix + "MovieDuration").innerHTML =
    movie.Duration ? movie.Duration + " min" : "";
  document.getElementById(modalPrefix + "MovieGenre").innerHTML =
    movie.Genre || "";
  document.getElementById(modalPrefix + "MovieReleaseYear").innerHTML =
    movie.ReleaseYear || "";
  document.getElementById(modalPrefix + "MovieDirector").innerHTML =
    movie.Director || "";
  document.getElementById(modalPrefix + "MovieDescription").innerHTML =
    movie.MovieDescription || "";
}

function showPreviewModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);
  movie.Subtitle = decodeHtmlEntities(movie.Subtitle);
  movie.Genre = decodeHtmlEntities(movie.Genre);
  movie.Director = decodeHtmlEntities(movie.Director);
  movie.MovieDescription = decodeHtmlEntities(movie.MovieDescription);

  populateModalFields("preview", movie);
  showModal("previewModal");
}

function hidePreviewModal() {
  hideModal("previewModal");
}

function showEditModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);
  movie.Subtitle = decodeHtmlEntities(movie.Subtitle);
  movie.Genre = decodeHtmlEntities(movie.Genre);
  movie.Director = decodeHtmlEntities(movie.Director);
  movie.MovieDescription = decodeHtmlEntities(movie.MovieDescription);

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

function hideEditModal() {
  hideModal("editModal");
}

function showDeleteModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);

  document.getElementById("deleteMovieID").value = movie.MovieID;
  document.getElementById("deleteMovieTitle").textContent = movie.Title;
  showModal("deleteModal");
}

function hideDeleteModal() {
  hideModal("deleteModal");
}

function showAddModal() {
  document.getElementById("addMovieForm").reset();
  showModal("addModal");
}

function hideAddModal() {
  hideModal("addModal");
}
