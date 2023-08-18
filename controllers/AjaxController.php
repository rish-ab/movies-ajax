<?php

class AjaxController extends Controller
{
    public function get_charts_data_tickets_sold()
    {
        $data = $this->load_model("TicketModel")->get_charts_data_tickets_sold();
        echo json_encode($data);
    }

    public function get_charts_data_movies_ratings()
    {
        $data = $this->load_model("RatingsModel")->get_charts_data_movies_ratings();
        echo json_encode($data);
    }

    public function get_my_booked_seats()
    {
        $movie_cinema_id = $_POST["movie_cinema_id"];
        $movie_id = $_POST["movie_id"];
        $cinema_id = $_POST["cinema_id"];
        
        if ($this->get_logged_in_user() == null)
        {
            echo json_encode([
                "status" => "error",
                "message" => "User has been logged out. Please login again."
            ]);
            exit();
        }

        $user_id = $this->get_logged_in_user()->id;
        $seats_booked = $this->load_model("TicketModel")->get_booked_seats($movie_id, $movie_cinema_id, $cinema_id);

        echo json_encode([
            "status" => "success",
            "message" => "Data has been fetched.",
            "seats_booked" => $seats_booked
        ]);
        exit();
    }
}