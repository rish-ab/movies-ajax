<?php

/**
 * PaymentModel
 */
class PaymentModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function payment_successfull()
    {
        $type = $_POST["type"];
        $order_id = $_POST["order_id"];
        $payment_id = $_POST["payment_id"];

        $sql = "INSERT INTO `payments`(`order_id`, `type`, `payment_id`) VALUES ('" . $order_id . "', '" . $type . "', '" . $payment_id . "')";
        mysqli_query($this->connection, $sql);
    }
}