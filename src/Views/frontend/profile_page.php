<?php
require_once '../../../config/user_session.php';
require_once '../../Controllers/CustomerController.php';
require_once '../../../config/functions.php';
requireLogin();

define('PASSWORD_CHANGE_LIMIT', 5);
define('PASSWORD_CHANGE_TIME_WINDOW', 900);

$userName = $_SESSION['user_name'] ?? 'User';
$customerController = new CustomerController();
$customerData = $customerController->getCustomerProfile($_SESSION['user_id']);
$statusMessage = '';

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['password_change_attempts'])) {
        $_SESSION['password_change_attempts'] = 0;
        $_SESSION['first_password_change_attempt'] = time();
    }

    $attempts = $_SESSION['password_change_attempts'];
    $firstAttemptTime = $_SESSION['first_password_change_attempt'];
    
    if ($attempts >= PASSWORD_CHANGE_LIMIT && (time() - $firstAttemptTime) < PASSWORD_CHANGE_TIME_WINDOW) {
        $remainingTime = PASSWORD_CHANGE_TIME_WINDOW - (time() - $firstAttemptTime);
        $minutes = ceil($remainingTime / 60);
        $statusMessage = "Too many password change attempts. Please try again in $minutes minutes.";
    } else {
        if ((time() - $firstAttemptTime) > PASSWORD_CHANGE_TIME_WINDOW) {
            $_SESSION['password_change_attempts'] = 0;
            $_SESSION['first_password_change_attempt'] = time();
        }
        
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($csrfToken)) {
            $statusMessage = 'Invalid CSRF token.';
        } elseif (isset($_POST['update_password'])) {
            $_SESSION['password_change_attempts']++;

            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $statusMessage = 'New passwords do not match.';
            } elseif (strlen($newPassword) < 8) {
                $statusMessage = 'Password should be at least 8 characters long.';
            } else {
                $updateSuccess = $customerController->updatePassword($_SESSION['user_id'], $currentPassword, $newPassword);
                if ($updateSuccess) {
                    $statusMessage = 'Password updated successfully.';
                    $_SESSION['password_change_attempts'] = 0;
                } else {
                    $statusMessage = 'Failed to update password. Please check your current password.';
                }
            }
        } else {
            $sanitizedData = [
                'FirstName' => sanitizeInput($_POST['FirstName'] ?? ''),
                'LastName' => sanitizeInput($_POST['LastName'] ?? ''),
                'Email' => sanitizeInput($_POST['Email'] ?? ''),
                'PhoneNumber' => sanitizeInput($_POST['PhoneNumber'] ?? ''),
                'Street' => sanitizeInput($_POST['Street'] ?? ''),
                'SuiteNumber' => sanitizeInput($_POST['SuiteNumber'] ?? ''),
                'City' => sanitizeInput($_POST['City'] ?? ''),
                'PostalCode' => sanitizeInput($_POST['PostalCode'] ?? ''),
                'Country' => sanitizeInput($_POST['Country'] ?? ''),
                'csrf_token' => $csrfToken
            ];

            $customerController->updateCustomer($sanitizedData);
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800 text-zinc-100">
    <main>
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <h1 class="text-2xl font-bold text-orange-600 sm:text-3xl">Welcome, <?php echo $userName; ?>!</h1>
            <section class="mt-6">
                <h2 class="text-xl">Profile</h2>
                <p class="mt-2 text-base text-zinc-300">Manage your profile details here.</p>
                <div class="mt-6 flex flex-row gap-3">
                    <button type="button" onclick="showEditProfileModal(<?php echo htmlspecialchars(json_encode($customerData)); ?>)" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                        Update Profile
                    </button>
                    <button type="button" onclick="showModal('updatePasswordModal')" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                        Update Password
                    </button>
                </div>
                <div class="mt-6">
                    <?php if ($statusMessage): ?>
                        <p class="text-sm <?php echo strpos($statusMessage, 'successfully') !== false ? 'text-green-500' : 'text-red-500'; ?>">
                            <?php echo htmlspecialchars($statusMessage); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="mt-6">
                    <dl class="-my-3 divide-y divide-zinc-700 text-sm">
                        <?php
                        $fields = [
                            'First Name' => 'FirstName',
                            'Last Name' => 'LastName',
                            'Email' => 'Email',
                            'Phone Number' => 'PhoneNumber',
                            'Street' => 'Street',
                            'Suite Number' => 'SuiteNumber',
                            'City' => 'City',
                            'Postal Code' => 'PostalCode',
                            'Country' => 'Country',
                        ];
                        foreach ($fields as $label => $fieldKey): ?>
                            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                <dt class="font-medium text-zinc-300"><?php echo $label; ?></dt>
                                <dd class="text-zinc-400"><?php echo $customerData[$fieldKey] ?? ''; ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </section>
            <a href="../../../src/Controllers/CustomerController.php?action=logout" 
            class="inline-block mt-6 rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                Log Out
            </a>
        </div>
        <div id="modalBackdrop" class="hidden fixed inset-0 z-40 bg-zinc-900 bg-opacity-50"></div>
        <div id="editProfileModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
            <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                    <h3 class="text-lg font-semibold text-zinc-900">Edit Profile</h3>
                    <button type="button" class="text-zinc-600 text-sm p-1.5 hover:text-zinc-900" onclick="hideModal('editProfileModal')">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-zinc-700 mb-4">
                    Fields marked with * are required to complete.
                </p>
                <form id="editProfileForm" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" id="editCustomerID" name="CustomerID">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">First Name: *</label>
                            <input type="text" id="editFirstName" name="FirstName" required autocomplete="given-name" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Last Name: *</label>
                            <input type="text" id="editLastName" name="LastName" required autocomplete="family-name" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Email: *</label>
                            <input type="email" id="editEmail" name="Email" required autocomplete="email" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Phone Number:</label>
                            <input type="text" id="editPhoneNumber" name="PhoneNumber" autocomplete="tel" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Street:</label>
                            <input type="text" id="editStreet" name="Street" autocomplete="address-line1" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Suite Number:</label>
                            <input type="text" id="editSuiteNumber" name="SuiteNumber" autocomplete="address-line2" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">City:</label>
                            <input type="text" id="editCity" name="City" autocomplete="address-level2" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Postal Code: *</label>
                            <input type="text" id="editPostalCode" name="PostalCode" required autocomplete="postal-code" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Country: *</label>
                            <input type="text" id="editCountry" name="Country" required autocomplete="country-name" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div class="sm:col-span-2 text-right">
                            <button type="submit" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="updatePasswordModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
            <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                    <h3 class="text-lg font-semibold text-zinc-900">Update Password</h3>
                    <button type="button" class="text-zinc-600 text-sm p-1.5 hover:text-zinc-900" onclick="hideModal('updatePasswordModal')">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form id="updatePasswordForm" method="POST" action="profile_page.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="update_password" value="1">
                    <p class="text-sm text-zinc-700 mb-4">
                        Password should be at least 8 characters long.
                    </p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Current Password: *</label>
                            <input type="password" name="current_password" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">New Password: *</label>
                            <input type="password" name="new_password" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Confirm New Password: *</label>
                            <input type="password" name="confirm_password" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div class="sm:col-span-2 text-right">
                            <button type="submit" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../../../public/js/crud-modals.js"></script>
</body>
</html>