<?php

/**
 * TicketModel
 */
class TicketModel extends Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function get_booked_seats($movie_id, $movie_cinema_id, $cinema_id)
    {
        $sql = "SELECT * FROM `tickets` WHERE `movie_id` = '" . $movie_id . "' AND cinema_id = '" . $cinema_id . "' AND movie_cinema_id = '" . $movie_cinema_id . "'";
        $result = mysqli_query($this->connection, $sql);

        $data = [];
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function get_user_booked_seats($user_id, $movie_id, $movie_cinema_id, $cinema_id)
    {
        $sql = "SELECT * FROM `tickets` WHERE `movie_id` = '" . $movie_id . "' AND cinema_id = '" . $cinema_id . "' AND user_id = '" . $user_id . "' AND movie_cinema_id = '" . $movie_cinema_id . "'";
        $result = mysqli_query($this->connection, $sql);

        $data = [];
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function create($movie_cinema, $order_id)
    {
        $id = $_POST["id"];
        $selected_seats = $_POST["selected_seats"];
        $selected_cinema = $_POST["selected_cinema"];

        $selected_time = $movie_cinema->movie_time;
        // $old_date_timestamp = strtotime($old_date);
        // $selected_time = date('M/d/Y h:i A', $old_date_timestamp);

        // $selected_time = date_format(date_create_from_format("M/d/Y h:i A", $movie_cinema->movie_time), "Y-m-d H:i:s");
        $user_id = $_SESSION["user"]->id;

        // Check if seat is already booked.
        // Only possible when 2 people try to book the same seat at the same time.
        foreach ($selected_seats as $seat):
            $sql = "SELECT * FROM `tickets` WHERE `movie_id` = '" . $id . "' AND cinema_id = '" . $selected_cinema . "' AND ticket_location = '" . $seat . "' AND movie_time = '" . $selected_time . "'";
            $result = mysqli_query($this->connection, $sql);
            if (mysqli_num_rows($result) > 0)
            {
                return array(
                    "status" => "error",
                    "message" => "Seat " . $seat . " has already been booked"
                );
            }
        endforeach;

        foreach ($selected_seats as $seat):
            $sql = "INSERT INTO `tickets`(`movie_id`, `user_id`, `cinema_id`, `ticket_location`, `movie_time`, `movie_cinema_id`, `order_id`) VALUES ('" . $id . "', '" . $user_id . "', '" . $selected_cinema . "', '" . $seat . "', '" . $selected_time . "', '" . $movie_cinema->id . "', '" . $order_id . "')";

            mysqli_query($this->connection, $sql);
        endforeach;

        return array(
            "status" => "success",
            "message" => "Seats has been booked"
        );
    }

    public function get_by_movie($movie_id)
    {
        $sql = "SELECT users.name AS username, users.email, users.mobile_number, cinemas.name AS cinema_name, tickets.movie_time, GROUP_CONCAT(ticket_location SEPARATOR ', ') AS tickets, GROUP_CONCAT(tickets.id SEPARATOR ',') AS ticket_ids FROM tickets INNER JOIN users ON users.id = tickets.user_id INNER JOIN cinemas ON cinemas.id = tickets.cinema_id WHERE tickets.movie_id = '" . $movie_id . "' GROUP BY tickets.user_id, tickets.cinema_id, tickets.movie_time";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function get_tickets_sold($movie_id)
    {
        $sql = "SELECT COUNT(*) AS total FROM tickets WHERE movie_id = '" . $movie_id . "'";
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_object($result)->total;
    }

    public function get_all_tickets_sold()
    {
        $sql = "SELECT COUNT(*) AS total FROM tickets";
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_object($result)->total;
    }

    public function get_charts_data_tickets_sold()
    {
        $sql = "SELECT COUNT(*) AS tickets, movies.name FROM tickets INNER JOIN movies ON movies.id = tickets.movie_id GROUP BY tickets.movie_id LIMIT 20";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function delete_ticket($id)
    {
        $sql = "DELETE FROM tickets WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function get_ticket_detail($id)
    {
        $sql = "SELECT * FROM tickets WHERE id = " . $id;
        $result = mysqli_query($this->connection, $sql);
        
        if (mysqli_num_rows($result) > 0)
        {
            $ticket = mysqli_fetch_object($result);
            
            $obj = new \stdClass();
            $obj->ticket = $ticket;

            $sql = "SELECT * FROM movies WHERE id = " . $ticket->movie_id;
            $result = mysqli_query($this->connection, $sql);
            
            if (mysqli_num_rows($result) > 0)
            {
                $obj->movie = mysqli_fetch_object($result);
            }

            $sql = "SELECT * FROM users WHERE id = " . $ticket->user_id;
            $result = mysqli_query($this->connection, $sql);
            
            if (mysqli_num_rows($result) > 0)
            {
                $obj->user = mysqli_fetch_object($result);
            }

            $sql = "SELECT * FROM cinemas WHERE id = " . $ticket->cinema_id;
            $result = mysqli_query($this->connection, $sql);
            
            if (mysqli_num_rows($result) > 0)
            {
                $obj->cinema = mysqli_fetch_object($result);
            }

            return $obj;
        }

        return null;
    }
}
