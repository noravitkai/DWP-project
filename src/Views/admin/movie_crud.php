<section id="movies" class="mb-10">
    <h2 class="text-3xl font-bold text-zinc-900">Movies</h2>
    <p class="mt-5 text-base text-zinc-700">Manage the movie listings and other movie-related tasks here.</p>
    <div class="mt-5">
        <button type="button" onclick="showAddMovieModal()" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Add Movie
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full my-10 text-zinc-900">
            <thead>
                <tr>
                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">ID</th>
                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Title</th>
                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Subtitle</th>
                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Release Year</th>
                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                <tr>
                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['MovieID']; ?></td>
                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['Title']; ?></td>
                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['Subtitle']; ?></td>
                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['ReleaseYear']; ?></td>
                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                        <button type="button" onclick="showPreviewMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                            </svg>
                            Preview
                        </button>
                        <button type="button" onclick="showEditMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                            </svg>
                            Edit
                        </button>
                        <button type="button" onclick="showDeleteMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-red-600 hover:text-red-800 transition ease-in-out duration-300">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="addMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900">Add New Movie</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('addMovieModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <form id="addMovieForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="addNewMovie" value="1">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Fast & Furious X" maxlength="255" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" name="Subtitle" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., A new age begins" maxlength="255">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Between 1900–2100</span>
                        <input type="number" name="ReleaseYear" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" min="1900" max="2100" placeholder="e.g., 2024" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 100 characters</span>
                        <input type="text" name="Genre" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Action" maxlength="100">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" name="Director" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., John Doe" maxlength="255">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Min. 1 minute</span>
                        <input type="number" name="Duration" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600"  min="1" placeholder="e.g., 106">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 500 characters</span>
                        <textarea name="MovieDescription" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="Type the movie's description here..." maxlength="500"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                        <div id="actorContainer" class="flex flex-col gap-2">
                        </div>
                        <div id="actorLimitMessage" class="text-sm text-red-600 mt-2 hidden">
                            You can only add up to 10 actors.
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="movieImage">Upload Image:</label>
                        <input type="file" name="movieImage" id="movieImage" accept="image/jpeg, image/png, image/gif">
                    </div>
                    <div class="sm:col-span-2 text-right">
                        <button type="submit" name="addMovieBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="previewMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900" id="previewMovieTitle">Movie Details</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('previewMovieModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">ID:</label>
                    <p id="previewMovieID" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                    <p id="previewMovieSubtitle" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                    <p id="previewMovieDuration" class="text-sm text-zinc-900 inline"></p>
                    <span id="durationSuffix" class="text-sm text-zinc-900 inline"> mins</span>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                    <p id="previewMovieGenre" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                    <p id="previewMovieReleaseYear" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                    <p id="previewMovieDirector" class="text-sm text-zinc-900"></p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                    <p id="previewMovieDescription" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                    <ul id="previewMovieCast" class="list-none pl-0 text-sm text-zinc-900"></ul>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Image:</label>
                    <img id="previewMovieImage" src="" alt="Movie Image" class="w-32 h-auto"/>
                </div>
            </div>
        </div>
    </div>
    <div id="editMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900">Edit Movie</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('editMovieModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <form id="editMovieForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="editMovieID" name="MovieID">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" id="editMovieTitle" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Fast & Furious X" maxlength="255" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" id="editMovieSubtitle" name="Subtitle" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., A new age begins" maxlength="255">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Between 1900–2100</span>
                        <input type="number" id="editMovieReleaseYear" name="ReleaseYear" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" min="1900" max="2100" placeholder="e.g., 2024" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 100 characters</span>
                        <input type="text" id="editMovieGenre" name="Genre" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Action" maxlength="100">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" id="editMovieDirector" name="Director" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., John Doe" maxlength="255">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Min. 1 minute</span>
                        <input type="number" id="editMovieDuration" name="Duration" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" min="1" placeholder="e.g., 106">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 500 characters</span>
                        <textarea id="editMovieDescription" name="MovieDescription" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="Type the movie's description here..." maxlength="500"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                        <div id="actorContainerEdit" class="flex flex-col gap-2">
                        </div>
                        <div id="actorLimitMessageEdit" class="text-sm text-red-600 mt-2 hidden">
                            You can only add up to 10 actors.
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="movieImage">Upload Image:</label>
                        <input type="file" name="movieImage" id="movieImage" accept="image/jpeg, image/png, image/gif">
                    </div>
                    <div class="sm:col-span-2 text-right">
                        <button type="submit" name="updateMovieBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="deleteMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-md w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
        <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900">Delete Movie</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('deleteMovieModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <p id="deleteMovieTitle" class="mb-4 text-sm font-semibold text-zinc-900"></p>
            <p class="mb-4 text-sm text-zinc-600">Are you sure you want to delete this movie? This action cannot be undone.</p>
            <form id="deleteMovieForm" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="deleteMovieID" name="deleteMovieID">
                <div class="flex justify-end gap-4">
                    <button type="button" class="rounded-lg bg-zinc-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-zinc-800 transition ease-in-out duration-300" onclick="hideModal('deleteMovieModal')">Cancel</button>
                    <button type="submit" name="deleteMovieBtn" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-red-800 transition ease-in-out duration-300">Delete</button>
                </div>
            </form>
        </div>
    </div>
</section>