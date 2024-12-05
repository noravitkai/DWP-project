<?php
require_once __DIR__ . '/../Models/PaymentModel.php';
require_once __DIR__ . '/../Models/ReservationModel.php';
require_once __DIR__ . '/../../config/dbcon.php';

class PaymentController {
    private $paymentModel;
    private $reservationModel;

    public function __construct() {
        $db = dbCon();
        $this->paymentModel = new PaymentModel($db);
        $this->reservationModel = new Reservation($db);
    }

    public function createPaymentRecord($reservationId, $stripeSessionId, $amount) {
        $reservationDetails = $this->reservationModel->getReservationById($reservationId);
        $customerId = $reservationDetails['CustomerID'] ?? null;

        $this->paymentModel->createPaymentRecord($reservationId, $stripeSessionId, $amount, $customerId);
    }

    public function updatePaymentStatus($stripeSessionId, $status, $paymentIntentId = null) {
        $this->paymentModel->updatePaymentStatus($stripeSessionId, $status, $paymentIntentId);
    }

    public function getPaymentBySessionId($stripeSessionId) {
        return $this->paymentModel->getPaymentBySessionId($stripeSessionId);
    }

    public function getPaymentByReservationId($reservationId) {
        return $this->paymentModel->getPaymentByReservationId($reservationId);
    }
}
?>