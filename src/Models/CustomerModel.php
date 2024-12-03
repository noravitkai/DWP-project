<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../../config/functions.php';

class CustomerModel {
    private $db;

    public function __construct() {
        $this->db = dbCon();
    }

    private function getPostalCodeID($postalCode, $city) {
        $query = "SELECT PostalCodeID FROM PostalCode WHERE PostalCode = :postalCode AND City = :city";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->execute();
        $postalCodeID = $stmt->fetchColumn();

        if (!$postalCodeID) {
            $query = "INSERT INTO PostalCode (PostalCode, City) VALUES (:postalCode, :city)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->execute();
            $postalCodeID = $this->db->lastInsertId();
        }

        return $postalCodeID;
    }

    public function createCustomer($firstName, $lastName, $email, $password, $phone, $suite, $street, $country, $postalCode, $city) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $postalCodeID = $this->getPostalCodeID($postalCode, $city);

        $query = "INSERT INTO Customer (FirstName, LastName, Email, `Password`, PhoneNumber, SuiteNumber, Street, Country, PostalCodeID) 
                  VALUES (:firstName, :lastName, :email, :password, :phone, :suite, :street, :country, :postalCodeID)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':suite', $suite, PDO::PARAM_STR);
        $stmt->bindParam(':street', $street, PDO::PARAM_STR);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->bindParam(':postalCodeID', $postalCodeID, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getCustomerByEmail($email) {
        $query = "SELECT * FROM Customer WHERE Email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerWithCityById($customerId) {
        $query = "SELECT Customer.*, PostalCode.PostalCode, PostalCode.City 
                  FROM Customer 
                  INNER JOIN PostalCode ON Customer.PostalCodeID = PostalCode.PostalCodeID 
                  WHERE Customer.CustomerID = :customerId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCustomerById($customerId, $data) {
        $newPostalCodeID = $this->getPostalCodeID($data['PostalCode'], $data['City']);

        $query = "UPDATE Customer SET 
                    FirstName = :firstName,
                    LastName = :lastName,
                    Email = :email,
                    PhoneNumber = :phone,
                    SuiteNumber = :suite,
                    Street = :street,
                    Country = :country,
                    PostalCodeID = :postalCodeID
                  WHERE CustomerID = :customerId";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':firstName', $data['FirstName'], PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $data['LastName'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['PhoneNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':suite', $data['SuiteNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':street', $data['Street'], PDO::PARAM_STR);
        $stmt->bindParam(':country', $data['Country'], PDO::PARAM_STR);
        $stmt->bindParam(':postalCodeID', $newPostalCodeID, PDO::PARAM_INT);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function updateCustomerPassword($customerId, $hashedPassword) {
        $query = "UPDATE Customer SET `Password` = :password WHERE CustomerID = :customerId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}