<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->header = ADMIN_VIEW . "header.php";
        $this->footer = ADMIN_VIEW . "footer.php";
    }

    public function orders()
    {
        if ($this->is_admin_logged_in())
        {
            $orders = $this->load_model("OrderModel")->get_all();

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'orders/all.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function seasons($page_type, $id = 0)
    {
        $error = "";
        $message = "";

        $admin_id = $this->get_logged_in_admin()->id;

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "delete_episode")
            {
                $response = $this->load_model("SeasonModel")->delete_episode($id);
                if ($response["status"] == "success")
                {
                    $message = $response["message"];
                    $_SESSION["message"] = $response["message"];
                }
                else
                {
                    $error = $response["message"];
                    $_SESSION["error"] = $response["message"];
                }
                header("Location: " . $_SERVER["HTTP_REFERER"]);
                exit();
            }

            if ($page_type == "add_episode")
            {
                $token = $_POST["token"];

                if (Security::is_valid_token($token))
                {
                    $response = $this->load_model("SeasonModel")->add_episode();
                    if ($response["status"] == "success")
                    {
                        $message = $response["message"];
                        $_SESSION["message"] = $response["message"];
                    }
                    else
                    {
                        $error = $response["message"];
                        $_SESSION["error"] = $response["message"];
                    }
                }
                else
                {
                    $error = "Token mismatch";
                    $_SESSION["error"] = "Token mismatch";
                }

                header("Location: " . $_SERVER["HTTP_REFERER"]);
                exit();
            }

            if ($page_type == "add")
            {
                $input = array();

                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("SeasonModel")->add();
                        $message = $response["message"];
                        $input = $_POST;
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $is_super_admin = $this->is_super_admin();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'seasons/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "edit")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("SeasonModel")->update();
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $season = $this->load_model("SeasonModel")->get($id);

                require_once ADMIN_VIEW . "header.php";
                if ($season == null)
                {
                    require_once ADMIN_VIEW . '404.php';
                }
                else
                {
                    require_once ADMIN_VIEW . 'seasons/edit.php';
                }
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "delete")
            {
                $response = $this->load_model("SeasonModel")->destroy($id);
                $message = $response["message"];
            }

            if ($page_type == "all"
                || $page_type == "delete")
            {
                $data = $this->load_model("SeasonModel")->get_all();
                $is_super_admin = $this->is_super_admin();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'seasons/all.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function coupon_codes($page_type, $id = 0)
    {
        $error = "";
        $message = "";

        $admin_id = $this->get_logged_in_admin()->id;

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "add")
            {
                $input = array();

                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $this->load_model("AdminModel")->add_coupon_code($admin_id);
                        $message = "Coupon code has been added.";
                        $input = $_POST;
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $is_super_admin = $this->is_super_admin();
                $movies = $this->load_model("MovieModel")->get_all($admin_id, $is_super_admin);

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'coupon_codes/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "delete")
            {
                $this->load_model("AdminModel")->delete_coupon_code($id);
                $message = "Coupon code has been deleted.";
            }

            if ($page_type == "all"
                || $page_type == "delete")
            {
                $data = $this->load_model("AdminModel")->get_all_coupon_codes($admin_id);
                $is_super_admin = $this->is_super_admin();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'coupon_codes/all.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function movie_managers($page_type, $id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "add")
            {
                $input = array();

                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $this->load_model("AdminModel")->add_movie_manager();
                        $message = "Movie manager has been added.";
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'managers/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "delete")
            {
                $this->load_model("AdminModel")->delete_movie_manager($id);
                $message = "Movie manager has been deleted.";
            }

            if ($page_type == "all"
                || $page_type == "delete")
            {
                $admins = $this->load_model("AdminModel")->get_all_managers();
                $is_super_admin = $this->is_super_admin();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'managers/all.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function index()
    {
        if ($this->is_admin_logged_in())
        {
            header("Location: " . URL . "admin/dashboard");
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function login()
    {
        $error = "";
        $message = "";

        if ($_POST)
        {
            $token = $_POST["token"];

            if (Security::is_valid_token($token))
            {
                $email = $_POST["email"];
                $password = $_POST["password"];

                $model_response = $this->load_model("AdminModel")->login($email, $password);
                $error = $model_response["error"];

                if (empty($error))
                {
                    $admin_data = $model_response["msg"];
                    $_SESSION["admin"] = $admin_data->id;
                    header("Location: " . URL . "admin");
                }
            }
            else
            {
                $error = "Token mismatch";
            }

            require_once ADMIN_VIEW . 'login.php';
        }
        else
        {
            require_once ADMIN_VIEW . 'login.php';
        }
    }

    public function dashboard()
    {
        if ($this->is_admin_logged_in())
        {
            $MovieModel = $this->load_model("MovieModel");
            $comming_soon = $MovieModel->get_comming_soon();
            $played_so_far = $MovieModel->played_so_far();
            $currently_playing = $MovieModel->get_currently_playing();
            $tickets_sold = $this->load_model("TicketModel")->get_all_tickets_sold();

            require_once $this->get_header();
            require_once ADMIN_VIEW . 'dashboard.php';
            require_once $this->get_footer();
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function category($page_type, $category_id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            
            if ($page_type == "add")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CategoryModel")->add();
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }
            }

            if ($page_type == "delete")
            {
                $response = $this->load_model("CategoryModel")->do_delete($category_id);
                if ($response["status"] == "success")
                {
                    $message = $response["message"];
                }
                else
                {
                    $error = $response["message"];
                }
            }

            if ($page_type == "edit")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CategoryModel")->edit($category_id);
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $category = $this->load_model("CategoryModel")->get($category_id);

                require_once ADMIN_VIEW . "header.php";
                if ($category == null)
                {
                    require_once ADMIN_VIEW . '404.php';
                }
                else
                {
                    require_once ADMIN_VIEW . 'categories/edit.php';
                }
                require_once ADMIN_VIEW . "footer.php";
            }
            else
            {
                $categories = $this->load_model("CategoryModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'categories/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function cinema($page_type, $cinema_id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            
            if ($page_type == "add")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CinemaModel")->add();
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }
            }

            if ($page_type == "delete")
            {
                $response = $this->load_model("CinemaModel")->do_delete($cinema_id);
                if ($response["status"] == "success")
                {
                    $message = $response["message"];
                }
                else
                {
                    $error = $response["message"];
                }
            }

            if ($page_type == "edit")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CinemaModel")->edit($cinema_id);
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $cinema = $this->load_model("CinemaModel")->get($cinema_id);

                require_once ADMIN_VIEW . "header.php";
                if ($cinema == null)
                {
                    require_once ADMIN_VIEW . '404.php';
                }
                else
                {
                    require_once ADMIN_VIEW . 'cinemas/edit.php';
                }
                require_once ADMIN_VIEW . "footer.php";
            }
            else
            {
                $cinemas = $this->load_model("CinemaModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'cinemas/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function movie($page_type, $movie_id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            
            if ($page_type == "add")
            {
                $input = array();

                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $admin_user_object = $this->get_logged_in_admin();

                        $MovieModel = $this->load_model("MovieModel");
                        $response = $MovieModel->add($admin_user_object->id);

                        if ($response["status"] == "success")
                        {
                            $movie_data = $MovieModel->get($response["movie_id"]);

                            if (strtotime($movie_data->release_date) > time())
                            {
                                $movie = new stdClass();
                                $movie->thumbnail = $MovieModel->get_thumbnail($response["movie_id"]);
                                $movie->name = $movie_data->name;
                                $movie->description = $movie_data->description;
                                $movie->id = $response["movie_id"];

                                $subscribers = $this->load_model("SubscriberModel")->get_all();
                                foreach ($subscribers as $subscriber)
                                {
                                    $email = file_get_contents("views/emails/new-movie.php");
                                    $variables = array(
                                        "{{ logo_url }}" => IMG . "logo.png",
                                        "{{ website_url }}" => URL,
                                        "{{ movie_thumbnail }}" => URL . $movie->thumbnail,
                                        "{{ movie_name }}" => $movie->name,
                                        "{{ movie_description }}" => $movie->description,
                                        "{{ movie_book_url }}" => URL . "movie/detail/" . $movie->id,
                                        "{{ unsubscribe_url }}" => URL . "user/unsubscribe/" . $subscriber->email
                                    );
                                 
                                    foreach ($variables as $key => $value)
                                        $email = str_replace($key, $value, $email);

                                    $this->send_mail($subscriber->email, "MoviePoint New Movie", $email);
                                }
                            }

                            $message = $response["message"];
                        }
                        else
                        {
                            $input = $response["input"];
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $categories = $this->load_model("CategoryModel")->get_all();
                $cinemas = $this->load_model("CinemaModel")->get_all();
                $casts = $this->load_model("CelebrityModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'movies/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "delete")
            {
                $this->load_model("CategoryModel")->delete_movies($movie_id);
                $this->load_model("CinemaModel")->delete_movies($movie_id);
                $this->load_model("MovieModel")->do_delete($movie_id);

                $error = "Movie has been deleted.";
            }

            if ($page_type == "edit")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("MovieModel")->edit($movie_id);
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $data = $this->load_model("MovieModel")->get_detail($movie_id);
                $categories = $this->load_model("CategoryModel")->get_all();
                $cinemas = $this->load_model("CinemaModel")->get_all();
                $casts = $this->load_model("CelebrityModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'movies/edit.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "detail")
            {
                $data = $this->load_model("MovieModel")->get_detail($movie_id);
                $tickets = $this->load_model("TicketModel")->get_by_movie($movie_id);
                $tickets_sold = $this->load_model("TicketModel")->get_tickets_sold($movie_id);

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'movies/detail.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "all" || $page_type == "delete")
            {
                $admin_id = $this->get_logged_in_admin()->id;
                $is_super_admin = $this->is_super_admin();
                $movies = $this->load_model("MovieModel")->get_all($admin_id, $is_super_admin);

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'movies/all.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function delete_thumbnail($thumbnail_id)
    {
        $this->load_model("MovieModel")->delete_thumbnail($thumbnail_id);
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    public function delete_trailer($trailer_id)
    {
        $this->load_model("MovieModel")->delete_trailer($trailer_id);
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    public function celebrities($page_type, $celebrity_id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "add")
            {
                $input = array();

                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CelebrityModel")->add();
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $input = $response["input"];
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'celebrities/add.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "delete")
            {
                $this->load_model("CelebrityModel")->do_delete($celebrity_id);

                $error = "Celebrity has been deleted.";
            }

            if ($page_type == "edit")
            {
                if ($_POST)
                {
                    $token = $_POST["token"];
                    
                    if (Security::is_valid_token($token))
                    {
                        $response = $this->load_model("CelebrityModel")->edit($celebrity_id);
                        if ($response["status"] == "success")
                        {
                            $message = $response["message"];
                        }
                        else
                        {
                            $error = $response["message"];
                        }
                    }
                    else
                    {
                        $error = "Token mismatch";
                    }
                }

                $data = $this->load_model("CelebrityModel")->get_detail($celebrity_id);

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'celebrities/edit.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "detail")
            {
                $data = $this->load_model("CelebrityModel")->get_detail($celebrity_id);

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'celebrities/detail.php';
                require_once ADMIN_VIEW . "footer.php";
            }

            if ($page_type == "all" || $page_type == "delete")
            {
                $celebrities = $this->load_model("CelebrityModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'celebrities/all.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function settings()
    {
        if ($this->is_admin_logged_in())
        {
            $input = array();
            if ($_POST)
            {
                $token = $_POST["token"];
                
                if (Security::is_valid_token($token))
                {
                    $response = $this->load_model("AdminModel")->update_settings();
                    if ($response["status"] == "success")
                    {
                        $message = $response["message"];
                    }
                    else
                    {
                        $error = $response["message"];
                        $input = $_POST;
                    }
                }
                else
                {
                    $error = "Token mismatch";
                }
            }

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'settings.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function profile()
    {
        if ($this->is_admin_logged_in())
        {
            $admin = $this->get_logged_in_admin();
            $input = array(
                "name" => $admin->name,
                "email" => $admin->email
            );

            if ($_POST)
            {
                $token = $_POST["token"];
                
                if (Security::is_valid_token($token))
                {
                    $response = $this->load_model("AdminModel")->update_profile();
                    if ($response["status"] == "success")
                    {
                        $message = $response["message"];
                    }
                    else
                    {
                        $error = $response["message"];
                    }
                    $input = $_POST;
                }
                else
                {
                    $error = "Token mismatch";
                }
            }

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'profile.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function subscribers($page_type, $id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "delete")
            {
                $this->load_model("SubscriberModel")->do_delete($id);
                $error = "Subscriber has been deleted.";
            }

            if ($page_type == "all" || $page_type == "delete")
            {
                $subscribers = $this->load_model("SubscriberModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'subscribers.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function users($page_type, $id = 0)
    {
        $error = "";
        $message = "";

        if ($this->is_admin_logged_in())
        {
            if ($page_type == "delete")
            {
                $this->load_model("UsersModel")->do_delete($id);
                $error = "User has been deleted.";
            }

            if ($page_type == "verify")
            {
                $this->load_model("AdminModel")->verify_user($id);
                $message = "User has been made verified.";
            }

            if ($page_type == "make_movie_manager" && isset($_POST["user_id"]))
            {
                $this->load_model("UsersModel")->make_movie_manager($_POST["user_id"]);
                $message = "User has been made the movie manager.";
            }

            if ($page_type == "all"
                || $page_type == "delete"
                || $page_type == "verify"
                || $page_type == "make_movie_manager")
            {
                $users = $this->load_model("UsersModel")->get_all();

                require_once ADMIN_VIEW . "header.php";
                require_once ADMIN_VIEW . 'users.php';
                require_once ADMIN_VIEW . "footer.php";
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function is_super_admin()
    {
        if ($this->is_admin_logged_in())
        {
            $admin = $this->get_logged_in_admin();
            return ($admin->role == "super_admin");
        }

        return false;
    }

    public function remove_photo()
    {
        if ($this->is_admin_logged_in())
        {
            $response = $this->load_model("AdminModel")->remove_photo();
            if ($response["status"] == "success")
            {
                $_SESSION["success"] = $response["message"];
            }
            else
            {
                $_SESSION["error"] = $response["message"];
            }

            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function upcoming_movies()
    {
        if ($this->is_admin_logged_in())
        {
            $movies = $this->load_model("MovieModel")->get_comming_soon();

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'movies/all.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function currently_playing()
    {
        if ($this->is_admin_logged_in())
        {
            $movies = $this->load_model("MovieModel")->get_currently_playing();

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'movies/all.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function movies_played_so_far()
    {
        if ($this->is_admin_logged_in())
        {
            $movies = $this->load_model("MovieModel")->get_played_so_far();

            require_once ADMIN_VIEW . "header.php";
            require_once ADMIN_VIEW . 'movies/all.php';
            require_once ADMIN_VIEW . "footer.php";
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function cancel_ticket()
    {
        if ($this->is_admin_logged_in())
        {
            $token = $_POST["token"];

            if (Security::is_valid_token($token))
            {
                $TicketModel = $this->load_model("TicketModel");
                
                $ticket_ids = explode(",", $_POST["ticket_ids"]);
                $last_ticket_id = 0;
                foreach ($ticket_ids as $id)
                {
                    $last_ticket_id = $id;
                }

                if ($last_ticket_id == 0)
                {
                    $_SESSION["error"] = "User has not selected any seat.";
                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }

                $ticket_detail = $TicketModel->get_ticket_detail($last_ticket_id);

                $email = file_get_contents("views/emails/booking-cancelled.php");
                $seats = "";
                $selected_seats = explode(",", $_POST["selected_seats"]);
                foreach ($selected_seats as $seat)
                {
                    $variable_seats = array(
                        "{{ cinema_name }}" => $ticket_detail->cinema->name,
                        "{{ movie_name }}" => $ticket_detail->movie->name,
                        "{{ languages }}" => $ticket_detail->movie->languages,
                        "{{ seat_location }}" => $seat,
                        "{{ time }}" => $ticket_detail->ticket->movie_time,
                        "{{ price }}" => $ticket_detail->movie->price_per_ticket
                    );
                    $textContent = '<tr>
                            <td>
                                <div class="cardWrap">
                                    <div class="card cardLeft">
                                        <h1>{{ cinema_name }}</h1>
                                        <div class="title" style="margin-top: 40px;">
                                            <h2>{{ movie_name }}</h2>
                                            <span>movie</span>
                                        </div>
                                        <div class="name">
                                            <h2>{{ languages }}</h2>
                                            <span>name</span>
                                        </div>
                                        <div class="seat">
                                            <h2>{{ seat_location }}</h2>
                                            <span>seat</span>
                                        </div>
                                        <div class="time">
                                            <h2>{{ time }}</h2>
                                            <span>time</span>
                                        </div>
                                    </div>

                                    <div class="card cardRight">
                                        
                                        <div class="number" style="margin-top: 70px;">
                                            <h3>{{ seat_location }}</h3>
                                            <span>seat</span>
                                        </div>

                                        <div class="number">
                                            <h3>{{ price }}</h3>
                                            <span>price</span>
                                        </div>

                                        <div class="barcode"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>';
                    foreach ($variable_seats as $key => $value)
                        $textContent = str_replace($key, $value, $textContent);
                    $seats .= $textContent;
                }
                $variables = array(
                    "{{ URL }}" => URL,
                    "{{ username }}" => $ticket_detail->user->name,
                    "{{ CONTACTUS_URL }}" => URL . "contact",
                    "{{ seats }}" => $seats
                );
             
                foreach ($variables as $key => $value)
                    $email = str_replace($key, $value, $email);

                $this->send_mail($ticket_detail->user->email, "MoviePoint Booking has been cancelled.", $email);

                foreach ($ticket_ids as $id)
                {
                    $TicketModel->delete_ticket($id);
                }

                $_SESSION["message"] = "Booking has been cancelled.";
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
            else
            {
                $_SESSION["error"] = "Token mismatch.";
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
        else
        {
            $this->goto_admin_login();
        }
    }

    public function logout()
    {
        unset($_SESSION["admin"]);
        header("Location: " . URL . "admin");
    }
}