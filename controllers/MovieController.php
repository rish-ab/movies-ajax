<?php

class MovieController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {
        if ($this->is_admin_logged_in())
        {
            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'movies/add.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function detail($movie_id)
    {
        $MovieModel = $this->load_model("MovieModel");
        
        $movie = $MovieModel->get_detail($movie_id);
        $next_movie = $MovieModel->get_next($movie_id);
        $previous_movie = $MovieModel->get_previous($movie_id);

        $is_currently_playing = $MovieModel->is_currently_playing($movie_id);
        $ratings = $this->load_model("RatingsModel")->get_movie_ratings($movie_id);

        $my_ratings = null;
        if (isset($_SESSION["user"]))
        {
            $my_ratings = $this->load_model("RatingsModel")->get_user_rating_on_movie($movie_id, $_SESSION["user"]->id);
        }

        $cinemas = array();
        foreach ($movie->cinemas as $movie_cinema)
        {
            $date = new DateTime($movie_cinema->movie_time);
            $now = new DateTime();

            if($date < $now)
            {
                continue;
            }

            $flag = false;
            foreach ($cinemas as $cinema)
            {
                if ($movie_cinema->id == $cinema->id)
                {
                    array_push($cinema->times, array(
                        "movie_time" => date("M/d/Y h:i A", strtotime($movie_cinema->movie_time)),
                        "time_id" => $movie_cinema->movie_cinema_id
                    ));

                    $flag = true;
                    break;
                }
            }
            if (!$flag)
            {
                
                $cinema_obj = new stdClass();
                $cinema_obj->id = $movie_cinema->id;
                $cinema_obj->name = $movie_cinema->name;

                $cinema_obj->times = [];
                array_push($cinema_obj->times, array(
                    "movie_time" => date("M/d/Y h:i A", strtotime($movie_cinema->movie_time)),
                    "time_id" => $movie_cinema->movie_cinema_id
                ));

                array_push($cinemas, $cinema_obj);

            }
        }
        
        $rows = 5;
        $columns = 3;
        $seats_per_column = 7;

        $alphabets = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'movies/movie-detail.php';
        require_once VIEW . "layout/footer.php";
    }

    public function add_comment()
    {
        $response = $this->load_model("MovieModel")->add_comment();
        if ($response["status"] == "success")
        {
            $_SESSION["post_comment_success"] = $response["message"];
        }
        else
        {
            $_SESSION["post_comment_error"] = $response["message"];
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    public function all()
    {
        $movies = $this->load_model("MovieModel")->get_all_for_home();
        $comming_soon = $this->load_model("MovieModel")->get_comming_soon();

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'movies/all.php';
        require_once VIEW . "layout/footer.php";
    }

    public function add_ratings()
    {
        if (isset($_SESSION["user"]))
        {
            // is exists
            $if_rated = $this->load_model("RatingsModel")->check_if_rated();
            if ($if_rated)
            {
                $this->load_model("RatingsModel")->update_ratings();
            }
            else
            {
                $this->load_model("RatingsModel")->add_ratings();
            }
            
            echo json_encode(array(
                "status" => "success",
                "message" => "Ratings has been saved."
            ));
        }
        else
        {
            echo json_encode(array(
                "status" => "error",
                "message" => "Please login to leave a review."
            ));
        }
    }
}