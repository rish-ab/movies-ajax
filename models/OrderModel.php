<?php

/**
 * OrderModel
 */
class OrderModel extends Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            $detail = $this->get_detail($row->id);
            array_push($data, $detail);
        }
        return $data;
    }

    public function create()
    {
        $id = $_POST["id"];
        $user_id = $_SESSION["user"]->id;

        $sql = "INSERT INTO `orders`(`movie_id`, `user_id`, `coupon_code_id`, `created_at`) VALUES ('" . $id . "', '" . $user_id . "', 0, NOW())";

        mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

        return mysqli_insert_id($this->connection);
    }

    public function update_coupon_code($order_id, $coupon_code_id)
    {
        $sql = "UPDATE `orders` SET coupon_code_id = '" . $coupon_code_id . "' WHERE id = '" . $order_id . "'";

        mysqli_query($this->connection, $sql);
    }

    public function get_detail($order_id)
    {
        $sql = "SELECT * FROM orders WHERE id = '" . $order_id . "'";
        $result = mysqli_query($this->connection, $sql);
        
        if (mysqli_num_rows($result) == 0)
        {
            die("Order does not exists.");
        }

        $order = mysqli_fetch_object($result);

        $sql = "SELECT * FROM coupon_codes WHERE id = '" . $order->coupon_code_id . "'";
        $coupon_result = mysqli_query($this->connection, $sql);
        $coupon = mysqli_fetch_object($coupon_result);

        $sql = "SELECT * FROM movies WHERE id = '" . $order->movie_id . "'";
        $movie_result = mysqli_query($this->connection, $sql);
        $movie = mysqli_fetch_object($movie_result);

        $sql = "SELECT * FROM users WHERE id = '" . $order->user_id . "'";
        $user_result = mysqli_query($this->connection, $sql);
        $user = mysqli_fetch_object($user_result);

        $sql = "SELECT * FROM payments WHERE order_id = '" . $order->id . "'";
        $payment_result = mysqli_query($this->connection, $sql);
        $payment = mysqli_fetch_object($payment_result);

        $data = [
            "order" => $order,
            "movie" => $movie,
            "user" => $user,
            "tickets" => [],
            "coupon" => $coupon,
            "payment" => $payment
        ];

        $sql = "SELECT * FROM tickets WHERE order_id = '" . $order_id . "'";
        $ticket_result = mysqli_query($this->connection, $sql);

        while ($ticket = mysqli_fetch_object($ticket_result))
        {
            $sql = "SELECT * FROM cinemas WHERE id = '" . $ticket->cinema_id . "'";
            $cinema_result = mysqli_query($this->connection, $sql);
            $cinema = mysqli_fetch_object($cinema_result);

            array_push($data["tickets"], [
                "ticket" => $ticket,
                "cinema" => $cinema
            ]);
        }

        return $data;
    }
}
