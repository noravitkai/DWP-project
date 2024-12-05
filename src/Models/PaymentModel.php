<?php
class PaymentModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function createPaymentRecord($reservationId, $stripeSessionId, $amount, $customerId = null) {
        $query = "
            INSERT INTO Payment (PaymentStatus, TransactionAmount, StripeSessionID, ReservationID, CustomerID)
            VALUES (:status, :amount, :session_id, :reservation_id, :customer_id)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'Pending', PDO::PARAM_STR);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':session_id', $stripeSessionId, PDO::PARAM_STR);
        $stmt->bindValue(':reservation_id', $reservationId, PDO::PARAM_INT);
        
        if ($customerId !== null) {
            $stmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':customer_id', null, PDO::PARAM_NULL);
        }
        
        $stmt->execute();
    }

    public function updatePaymentStatus($stripeSessionId, $status, $paymentIntentId = null) {
        $query = "
            UPDATE Payment 
            SET PaymentStatus = :status, StripePaymentIntentID = :intent_id, TransactionDate = NOW()
            WHERE StripeSessionID = :session_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        
        if ($paymentIntentId !== null) {
            $stmt->bindValue(':intent_id', $paymentIntentId, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':intent_id', null, PDO::PARAM_NULL);
        }
        
        $stmt->bindValue(':session_id', $stripeSessionId, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getPaymentBySessionId($stripeSessionId) {
        $query = "SELECT * FROM Payment WHERE StripeSessionID = :session_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':session_id', $stripeSessionId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaymentByReservationId($reservationId) {
        $query = "SELECT * FROM Payment WHERE ReservationID = :reservation_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':reservation_id', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>