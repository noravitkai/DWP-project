<?php
require_once '../../../config/user_session.php';
require_once '../../Controllers/CustomerController.php';
requireLogin();

$userName = $_SESSION['user_name'] ?? 'User';
$customerController = new CustomerController();
$customerData = $customerController->getCustomerProfile($_SESSION['user_id']);
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
            <h1 class="text-2xl font-bold text-orange-600 sm:text-3xl">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
            <section class="mt-6">
                <h2 class="text-lg">Manage Your Profile</h2>
                <div class="mt-6">
                    <dl class="-my-3 divide-y divide-zinc-700 text-sm">
                        <?php
                        $fields = [
                            'First Name' => 'FirstName',
                            'Last Name' => 'LastName',
                            'Email' => 'Email',
                            'Phone Number' => 'PhoneNumber',
                            'Suite Number' => 'SuiteNumber',
                            'Street' => 'Street',
                            'Country' => 'Country',
                            'Postal Code' => 'PostalCode',
                            'City' => 'City'
                        ];
                        foreach ($fields as $label => $fieldKey): ?>
                            <div class="grid grid-cols-1 gap-1 py-2 items-center lg:grid-cols-9">
                                <dt class="font-medium text-zinc-300 lg:col-span-4"><?php echo $label; ?></dt>
                                <dd class="text-zinc-400 lg:col-span-4">
                                    <?php echo !empty($customerData[$fieldKey]) ? htmlspecialchars($customerData[$fieldKey]) : ''; ?>
                                </dd>
                                <div class="lg:col-span-1 flex justify-start lg:justify-end">
                                    <button type="button" class="flex items-center py-1 text-orange-600 hover:text-orange-400 transition ease-in-out duration-300">
                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                            <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                        </svg>
                                        Update
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </section>
            <a href="../../../src/Controllers/CustomerController.php?action=logout" class="inline-block mt-6 rounded-lg bg-orange-600 px-5 py-3 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                Log Out
            </a>
        </div>
    </main>
</body>
</html>