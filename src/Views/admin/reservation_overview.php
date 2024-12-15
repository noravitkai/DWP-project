<section id="reservations" class="mb-10">
    <h2 class="text-3xl font-bold text-zinc-900">Reservations</h2>
    <p class="mt-5 text-base text-zinc-700">Manage and review customer reservations here.</p>
    <div class="overflow-x-auto">
        <div class="max-h-96">
            <table class="min-w-full my-10 text-zinc-900 overflow-y-scroll">
                <thead>
                    <tr>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">ID</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Created At</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Screening</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Status</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $reservation['ReservationID']; ?></td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $reservation['CreatedAt']; ?></td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">ID: <?php echo $reservation['ScreeningID']; ?>; movie: <?php echo $reservation['MovieTitle']; ?></td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $reservation['Status']; ?></td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                            <button type="button" onclick="showPreviewReservationModal(<?php echo htmlspecialchars(json_encode($reservation), ENT_QUOTES, 'UTF-8'); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                                Preview
                            </button>
                            <a href="invoice.php?reservationId=<?php echo urlencode($reservation['ReservationID']); ?>" class="flex w-full items-center py-1 mt-2 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300" target="_blank">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Invoice
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="previewReservationModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
        <div class="relative max-w-3xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                <h3 class="text-lg font-semibold text-zinc-900">Reservation Details</h3>
                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('previewReservationModal')">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Reservation ID:</label>
                    <p id="previewReservationID" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Created At:</label>
                    <p id="previewReservationCreatedAt" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening:</label>
                    <div id="previewReservationScreeningInfo" class="text-sm text-zinc-900"></div>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Status:</label>
                    <p id="previewReservationStatus" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Number of Seats:</label>
                    <p id="previewReservationNumberOfSeats" class="text-sm text-zinc-900"></p>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Reserved Seats:</label>
                    <ul id="previewReservationSeats" class="list-none text-sm text-zinc-900"></ul>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Customer Information:</label>
                    <div id="previewReservationCustomerInfo" class="text-sm text-zinc-900">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Total Price:</label>
                    <p id="previewReservationTotalPrice" class="text-sm text-zinc-900"></p>
                </div>
            </div>
        </div>
    </div>
</section>