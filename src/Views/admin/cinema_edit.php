<section id="cinema" class="mb-10">
    <h2 class="text-3xl font-bold text-zinc-900">Cinema Details</h2>
    <p class="mt-5 text-base text-zinc-700">Manage the company presentation such as contact details and other essential information here.</p>
    <?php foreach ($cinemas as $cinema): ?>
        <div class="mt-5">
                <button 
                    type="button" 
                    onclick="showEditCinemaModal(<?php echo htmlspecialchars(json_encode($cinema), ENT_QUOTES, 'UTF-8'); ?>)" 
                    class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Details
                </button>
        </div>
        <div class="mt-10">
            <dl class="text-sm text-zinc-900">
                    <div class="divide-y divide-zinc-200">
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Title</dt>
                            <dd><?php echo $cinema['Tagline']; ?></dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Description</dt>
                            <dd><?php echo $cinema['Description']; ?></dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Phone</dt>
                            <dd><?php echo $cinema['PhoneNumber']; ?></dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Email</dt>
                            <dd><?php echo $cinema['Email']; ?></dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Opening Hours</dt>
                            <dd><?php echo $cinema['OpeningHours']; ?></dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Image</dt>
                            <dd>
                                <?php if (!empty($cinema['ImageURL'])): ?>
                                    <img src="<?php echo $cinema['ImageURL']; ?>" alt="Cinema Image" class="w-32 h-auto">
                                <?php else: ?>
                                    <span>No image available.</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    </div>
            </dl>
        </div>
    <?php endforeach; ?>
    <div id="editCinemaModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900">Edit Cinema Details</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('editCinemaModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <form id="editCinemaForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="editCinemaID" name="CinemaID">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Tagline:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="text" id="editCinemaTitle" name="Tagline" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Fast cars and much more!" maxlength="255" required> 
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 500 characters</span>
                        <textarea id="editCinemaDescription" name="Description" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Family is fast, Family is everything!" maxlength="500" required></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Phone:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 20 characters</span>
                        <input type="tel" id="editCinemaPhone" name="PhoneNumber" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., +36 30 561 2282" maxlength="20">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Email:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 255 characters</span>
                        <input type="email" id="editCinemaEmail" name="Email" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., fast@furiouscine.com" maxlength="255" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Opening Hours:</label>
                        <span class="text-zinc-500 text-xs block mb-1">Max. 300 characters</span>
                        <textarea id="editCinemaOpeningHours" name="OpeningHours" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" placeholder="e.g., Monday-Friday: 10:00 AM - 10:00 PM" maxlength="300" required></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="cinemaImage">Upload Image:</label>
                        <input type="file" name="cinemaImage" id="cinemaImage" accept="image/jpeg, image/png, image/gif">
                    </div>
                    <div class="sm:col-span-2 text-right">
                        <button type="submit" name="updateCinemaBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>