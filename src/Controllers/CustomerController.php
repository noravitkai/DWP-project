<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../Models/CustomerModel.php';
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../../config/functions.php';

class CustomerController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
    }

    public function fetchLoggedInCustomerData() {
        $customerId = $_SESSION['user_id'] ?? null;
        if ($customerId) {
            return $this->customerModel->getCustomerWithCityById($customerId);
        }
        return null;
    }

    public function getCustomerProfile($customerId) {
        return $this->customerModel->getCustomerWithCityById($customerId);
    }

    public function register($data) {
        try {
            if (!isset($data['csrf_token']) || !verifyCsrfToken($data['csrf_token'])) {
                $this->redirect('../../src/Views/frontend/user_signup.php');
            }

            $result = $this->customerModel->createCustomer(
                $data['firstName'],
                $data['lastName'],
                $data['email'],
                $data['password'],
                $data['phone'],
                $data['suite'],
                $data['street'],
                $data['country'],
                $data['postalCode'],
                $data['city']
            );
            if ($result) {
                regenerateCsrfToken();
                $this->redirect('../../src/Views/frontend/user_login.php');
            } else {
                throw new Exception("Registration failed.");
            }
        } catch (Exception $e) {
            error_log("Customer Registration Error: " . $e->getMessage());
            $this->redirect('../../src/Views/frontend/user_signup.php');
        }
    }

    public function login($email, $password) {
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            $this->redirect('../../src/Views/frontend/user_login.php');
        }

        $sanitizedEmail = sanitizeInput($email);
        try {
            $customer = $this->customerModel->getCustomerByEmail($sanitizedEmail);
            if ($customer && password_verify($password, $customer['Password'])) {
                $_SESSION['user_id'] = $customer['CustomerID'];
                $_SESSION['user_name'] = $customer['FirstName'];
                regenerateCsrfToken();
                $this->redirect('../../src/Views/frontend/profile_page.php');
            } else {
                $this->redirect('../../src/Views/frontend/user_login.php?error=invalid_credentials');
            }
        } catch (Exception $e) {
            error_log("Customer Login Error: " . $e->getMessage());
            $this->redirect('../../src/Views/frontend/user_login.php');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('../../src/Views/frontend/user_login.php');
    }

    public function updateCustomer($data) {
        $customerId = $_SESSION['user_id'] ?? null;
        if ($customerId) {
            if (!isset($data['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
                die('Invalid CSRF token.');
            }
            $updateResult = $this->customerModel->updateCustomerById($customerId, $data);
            if ($updateResult) {
                $_SESSION['user_name'] = $data['FirstName'];
            }
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->redirect('../../Views/frontend/profile_page.php');
        }
    }

    public function updatePassword($customerId, $currentPassword, $newPassword) {
        $customer = $this->customerModel->getCustomerWithCityById($customerId);
        if ($customer && password_verify($currentPassword, $customer['Password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            return $this->customerModel->updateCustomerPassword($customerId, $hashedPassword);
        }
        return false;
    }

    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}

if (isset($_GET['action'])) {
    $allowedActions = ['register', 'login', 'logout', 'update'];
    $action = $_GET['action'];

    if (!in_array($action, $allowedActions, true)) {
        header('HTTP/1.1 400 Bad Request');
        exit('Invalid action.');
    }

    $controller = new CustomerController();

    if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $registrationData = [
            'firstName' => $_POST['first_name'] ?? null,
            'lastName' => $_POST['last_name'] ?? null,
            'email' => $_POST['email'] ?? null,
            'password' => $_POST['password'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'suite' => $_POST['suite'] ?? null,
            'street' => $_POST['street'] ?? null,
            'country' => $_POST['country'] ?? null,
            'postalCode' => $_POST['postal_code'] ?? null,
            'city' => $_POST['city'] ?? null,
            'csrf_token' => $_POST['csrf_token'] ?? null
        ];
        $controller->register($registrationData);
    }

    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $controller->login($email, $password);
    }

    if ($action === 'logout') {
        $controller->logout();
    }

    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->updateCustomer($_POST);
    }
}
?>