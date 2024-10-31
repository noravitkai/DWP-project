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

function populateMovieModalFields(modalPrefix, movie) {
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

function showPreviewMovieModal(movie) {
  movie.Title = decodeHtmlEntities(movie.Title);
  movie.Subtitle = decodeHtmlEntities(movie.Subtitle);
  movie.Genre = decodeHtmlEntities(movie.Genre);
  movie.Director = decodeHtmlEntities(movie.Director);
  movie.MovieDescription = decodeHtmlEntities(movie.MovieDescription);

  populateMovieModalFields("preview", movie);

  const previewImage = document.getElementById("previewMovieImage");
  if (movie.ImageURL) {
    previewImage.src = movie.ImageURL;
    previewImage.classList.remove("hidden");
  } else {
    previewImage.classList.add("hidden");
  }

  showModal("previewModal");
}

function hidePreviewMovieModal() {
  hideModal("previewModal");
}

function showEditMovieModal(movie) {
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
  document.getElementById("addMovieForm").reset();
  showModal("addModal");
}

function hideAddMovieModal() {
  hideModal("addModal");
}

function populateNewsModalFields(modalPrefix, news) {
  document.getElementById(modalPrefix + "NewsTitleText").innerHTML =
    news.Title || "";
  document.getElementById(modalPrefix + "NewsCategory").innerHTML =
    news.Category || "";
  document.getElementById(modalPrefix + "NewsDatePublished").innerHTML =
    news.DatePublished || "";
  document.getElementById(modalPrefix + "NewsContent").innerHTML =
    news.Content || "";
}

function showPreviewNewsModal(news) {
  news.Title = decodeHtmlEntities(news.Title);
  news.Category = decodeHtmlEntities(news.Category);
  news.Content = decodeHtmlEntities(news.Content);

  populateNewsModalFields("preview", news);

  const previewImage = document.getElementById("previewNewsImage");
  if (news.ImageURL) {
    previewImage.src = news.ImageURL;
    previewImage.classList.remove("hidden");
  } else {
    previewImage.classList.add("hidden");
  }

  showModal("previewNewsModal");
}

function hidePreviewNewsModal() {
  hideModal("previewNewsModal");
}

function showEditNewsModal(news) {
  news.Title = decodeHtmlEntities(news.Title);
  news.Category = decodeHtmlEntities(news.Category);
  news.Content = decodeHtmlEntities(news.Content);

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
  document.getElementById("addNewsForm").reset();
  showModal("addNewsModal");
}

function hideAddNewsModal() {
  hideModal("addNewsModal");
}

document.getElementById("movieImage").addEventListener("change", function (e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (event) {
      document.getElementById("imagePreview").src = event.target.result;
      document.getElementById("imagePreview").classList.remove("hidden");
    };
    reader.readAsDataURL(file);
  }
});
