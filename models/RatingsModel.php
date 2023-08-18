<?php

/**
 * RatingsModel
 */
class RatingsModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function check_if_rated()
    {
        $movie_id = $_POST["movie_id"];
        $user_id = $_SESSION["user"]->id;

        $sql = "SELECT * FROM movie_ratings WHERE movie_id = '" . $movie_id . "' AND user_id = '" . $user_id . "'";
        $result = mysqli_query($this->connection, $sql);

        return mysqli_num_rows($result) > 0;
    }

    public function add_ratings()
    {
        $movie_id = $_POST["movie_id"];
        $ratings = $_POST["ratings"];
        $user_id = $_SESSION["user"]->id;

        $sql = "INSERT INTO movie_ratings (movie_id, user_id, ratings) VALUES ('" . $movie_id . "', '" . $user_id . "', '" . $ratings . "')";
        mysqli_query($this->connection, $sql);
    }

    public function update_ratings()
    {
        $movie_id = $_POST["movie_id"];
        $ratings = $_POST["ratings"];
        $user_id = $_SESSION["user"]->id;

        $sql = "UPDATE movie_ratings SET ratings = '" . $ratings . "' WHERE movie_id = '" . $movie_id . "' AND user_id = '" . $user_id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function get_user_rating_on_movie($movie_id, $user_id)
    {
        $sql = "SELECT * FROM movie_ratings WHERE movie_id = '" . $movie_id . "' AND user_id = '" . $user_id . "'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            return mysqli_fetch_object($result);
        }
        return null;
    }

    public function get_movie_ratings($movie_id)
    {
        // Getting ratings of current product using ID
        $sql = "SELECT * FROM movie_ratings WHERE movie_id = '" . $movie_id . "'";
        $result_ratings = mysqli_query($this->connection, $sql);
     
        // Adding total ratings from each user
        $ratings = 0;
        while ($row_ratings = mysqli_fetch_object($result_ratings))
        {
            $ratings += $row_ratings->ratings;
        }
     
        // Calculating average from all ratings
        $average_ratings = 0;
        if ($ratings > 0)
        {
            $average_ratings = $ratings / mysqli_num_rows($result_ratings);
        }
        return $average_ratings;
    }

    public function get_charts_data_movies_ratings()
    {
        // Getting ratings of current product using ID
        $sql = "SELECT SUM(movie_ratings.ratings) AS ratings, COUNT(*) AS number_of_raters, movies.name FROM movie_ratings INNER JOIN movies ON movies.id = movie_ratings.movie_id GROUP BY movie_ratings.movie_id LIMIT 20";
        $result = mysqli_query($this->connection, $sql);
     
        // Adding total ratings from each user
        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            $average_ratings = 0;
            if ($row->ratings > 0)
            {
                $average_ratings = $row->ratings / $row->number_of_raters;
            }
            array_push($data, array(
                "ratings" => $average_ratings,
                "name" => $row->name
            ));
        }
        return $data;
    }
}