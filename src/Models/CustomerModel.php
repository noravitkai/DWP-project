<?php
require_once '../../config/dbcon.php';
require_once '../../config/functions.php';

class CustomerModel {
    private $db;

    public function __construct() {
        $this->db = dbCon();
    }

    private function postalCodeExists($postalCode, $city) {
        $query = "SELECT COUNT(*) FROM PostalCode WHERE PostalCode = :postalCode AND City = :city";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function createCustomer($firstName, $lastName, $email, $password, $phone, $suite, $street, $country, $postalCode, $city) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        if (!$this->postalCodeExists($postalCode, $city)) {
            $query = "INSERT INTO PostalCode (PostalCode, City) VALUES (:postalCode, :city)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->execute();
        }

        $query = "INSERT INTO Customer (FirstName, LastName, Email, `Password`, PhoneNumber, SuiteNumber, Street, Country, PostalCode) 
                  VALUES (:firstName, :lastName, :email, :password, :phone, :suite, :street, :country, :postalCode)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':suite', $suite, PDO::PARAM_STR);
        $stmt->bindParam(':street', $street, PDO::PARAM_STR);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getCustomerByEmail($email) {
        $query = "SELECT * FROM Customer WHERE Email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($customerId) {
        $query = "SELECT * FROM Customer WHERE CustomerID = :customerId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}