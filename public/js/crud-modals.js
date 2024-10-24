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
  document.getElementById(modalPrefix + "MovieTitle").textContent = movie.Title || '';
  document.getElementById(modalPrefix + "MovieID").textContent = movie.MovieID || '';
  document.getElementById(modalPrefix + "MovieSubtitle").textContent = movie.Subtitle || '';
  document.getElementById(modalPrefix + "MovieDuration").textContent = movie.Duration ? movie.Duration + " min" : '';
  document.getElementById(modalPrefix + "MovieGenre").textContent = movie.Genre || '';
  document.getElementById(modalPrefix + "MovieReleaseYear").textContent = movie.ReleaseYear || '';
  document.getElementById(modalPrefix + "MovieDirector").textContent = movie.Director || '';
  document.getElementById(modalPrefix + "MovieDescription").textContent = movie.MovieDescription || '';
}

function showPreviewModal(movie) {
  populateModalFields("preview", movie);
  showModal("previewModal");
}

function hidePreviewModal() {
  hideModal("previewModal");
}

function showEditModal(movie) {
  document.getElementById("editMovieID").value = movie.MovieID;
  document.getElementById("editMovieTitle").value = movie.Title;
  document.getElementById("editMovieSubtitle").value = movie.Subtitle;
  document.getElementById("editMovieReleaseYear").value = movie.ReleaseYear;
  document.getElementById("editMovieGenre").value = movie.Genre;
  document.getElementById("editMovieDirector").value = movie.Director;
  document.getElementById("editMovieDuration").value = movie.Duration;
  document.getElementById("editMovieDescription").value = movie.MovieDescription;

  showModal("editModal");
}

function hideEditModal() {
  hideModal("editModal");
}

function showDeleteModal(movie) {
  document.getElementById("deleteMovieID").value = movie.MovieID;
  document.getElementById("deleteMovieTitle").textContent = `${movie.Title}`;
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
