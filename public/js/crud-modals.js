function showPreviewModal(movie) {
  document.getElementById("movieTitle").textContent = movie.Title;
  document.getElementById("movieID").textContent = movie.MovieID;
  document.getElementById("movieSubtitle").textContent = movie.Subtitle;
  document.getElementById("movieDuration").textContent =
    movie.Duration + " min";
  document.getElementById("movieGenre").textContent = movie.Genre;
  document.getElementById("movieReleaseYear").textContent = movie.ReleaseYear;
  document.getElementById("movieDirector").textContent = movie.Director;
  document.getElementById("movieDescription").textContent =
    movie.MovieDescription;

  const modal = document.getElementById("previewModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");

  const backdrop = document.getElementById("modalBackdrop");
  backdrop.classList.remove("hidden");

  document.documentElement.style.overflow = "hidden";
  document.body.style.overflow = "hidden";
}

function hidePreviewModal() {
  const modal = document.getElementById("previewModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");

  const backdrop = document.getElementById("modalBackdrop");
  backdrop.classList.add("hidden");

  document.documentElement.style.overflow = "";
  document.body.style.overflow = "";
}
