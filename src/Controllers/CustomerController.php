<?php
require_once __DIR__ . '/../Models/CustomerModel.php';
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../../config/functions.php';

class CustomerController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function register($data) {
        $sanitizedData = [
            'firstName' => sanitizeInput($data['firstName']),
            'lastName' => sanitizeInput($data['lastName']),
            'email' => sanitizeInput($data['email']),
            'password' => $data['password'],
            'phone' => sanitizeInput($data['phone']),
            'suite' => sanitizeInput($data['suite']),
            'street' => sanitizeInput($data['street']),
            'country' => sanitizeInput($data['country']),
            'postalCode' => sanitizeInput($data['postalCode']),
            'city' => sanitizeInput($data['city']),
        ];

        try {
            $result = $this->customerModel->createCustomer(
                $sanitizedData['firstName'],
                $sanitizedData['lastName'],
                $sanitizedData['email'],
                $sanitizedData['password'],
                $sanitizedData['phone'],
                $sanitizedData['suite'],
                $sanitizedData['street'],
                $sanitizedData['country'],
                $sanitizedData['postalCode'],
                $sanitizedData['city']
            );
            if ($result) {
                $this->redirect('../../src/Views/frontend/user_login.php');
            } else {
                throw new Exception("Registration failed. Please try again.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function login($email, $password) {
        $sanitizedEmail = sanitizeInput($email);
        try {
            $customer = $this->customerModel->getCustomerByEmail($sanitizedEmail);
            if ($customer && password_verify($password, $customer['Password'])) {
                $_SESSION['user_id'] = $customer['CustomerID'];
                $_SESSION['user_name'] = $customer['FirstName'];
                $this->redirect('../../src/Views/frontend/profile_page.php');
            } else {
                $this->redirect('../../src/Views/frontend/user_login.php?error=invalid_credentials');
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('../../src/Views/frontend/user_login.php');
    }

    public function getCustomerProfile($customerId) {
        return $this->customerModel->getCustomerById($customerId);
    }

    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}

if (isset($_GET['action'])) {
    $controller = new CustomerController();

    if ($_GET['action'] === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
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
            'city' => $_POST['city'] ?? null
        ];

        $controller->register($registrationData);
    }

    if ($_GET['action'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $controller->login($email, $password);
    }

    if ($_GET['action'] === 'logout') {
        $controller->logout();
    }
}