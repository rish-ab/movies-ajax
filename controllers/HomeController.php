<?php

    class HomeController extends Controller
    {
        public function index($message = "")
        {
            $movies = $this->load_model("MovieModel")->get_all_for_home();
            $comming_soon = $this->load_model("MovieModel")->get_comming_soon();
            $seasons = $this->load_model("SeasonModel")->get_all_for_home();

            if (!empty($message))
            {
                if ($message == "verified")
                {
                    $message = "Email has been verified. Please login now.";
                }

                if ($message == "reset")
                {
                    $message = "Password has been reset. Please try login now.";
                }
            }

        	require_once VIEW . "layout/header.php";
            require_once VIEW . 'home.php';
            require_once VIEW . "layout/footer.php";
        }
    }

?>