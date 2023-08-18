<?php


class UserController extends Controller
{
    public function login($message = "")
    {
        $token = $_POST["token"];

        if (Security::is_valid_token($token))
        {
            $model_response = $this->load_model("UsersModel")->login();
            $response["error"] = $model_response["error"];

            if (empty($response["error"]))
            {
                $_SESSION["user"] = $model_response["message"];
                header("Location: " . URL);
            }
            else
            {
                $_SESSION["login_error"] = $response["error"];
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
        else
        {
            $_SESSION["login_error"] = "Token mismatch";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    public function register()
    {
        $token = $_POST["token"];

        if (Security::is_valid_token($token))
        {
            $UsersModel = $this->load_model("UsersModel");
            if ($UsersModel->is_exists($_POST["email"]))
            {
                $_SESSION["login_error"] = "Email already exists";
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
            else
            {
                $verification_code = $UsersModel->register();
                
                $email = file_get_contents("views/emails/verification-email.php");
                $variables = array(
                    "{{ website_url }}" => URL,
                    "{{ verification_code }}" => $verification_code,
                    "{{ name }}" => $_POST["name"]
                );
             
                foreach ($variables as $key => $value)
                    $email = str_replace($key, $value, $email);
                $this->send_mail($_POST["email"], "Verify Email - MoviePoint", $email);

                $_SESSION["login_verification"] = "Account has been created. Please verify your email address.";
                header("Location: " . URL . "user/verify_email/" . $_POST["email"]);
            }
        }
        else
        {
            $_SESSION["login_error"] = "Token mismatch";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    public function verify_email($email, $temp_error = "")
    {
        $error = "";

        if (!empty($temp_error))
        {
            $error = "Please verify your email";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!Security::is_valid_token($_POST["token"]))
            {
                $error = "This page has been expired";
            }
            else
            {
                $is_verified = $this->load_model("UsersModel")->do_verify_email();
                if ($is_verified)
                {
                    header("Location: " . URL . "home/index/verified");
                    exit();
                }
                $error = "Sorry, verification code is wrong";
            }
        }

        require_once $this->get_header();
        require_once VIEW . 'user/verify_email.php';
        require_once $this->get_footer();
    }

    public function forgot_password()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!Security::is_valid_token($_POST["token"]))
            {
                $_SESSION["login_error"] = "This page has been expired";
            }
            else
            {
                $user = $this->load_model("UsersModel")->get_by_email($_POST["email"]);
                if ($user == null)
                {
                    $_SESSION["login_error"] = "Email does not exists.";
                }
                else
                {
                    $token = md5(time());
                    $link = URL . "user/reset_password/" . $_POST["email"] . "/" . $token;

                    $email = file_get_contents("views/emails/reset-password.php");
                    $variables = array(
                        "{{ website_url }}" => URL,
                        "{{ name }}" => $user->name,
                        "{{ reset_link }}" => $link
                    );
                 
                    foreach ($variables as $key => $value)
                        $email = str_replace($key, $value, $email);
                    $this->send_mail($_POST["email"], "Reset Password - MoviePoint", $email);

                    $this->load_model("UsersModel")->forgot_password($token);
                    $_SESSION["login_success"] = "Password reset link has been emailed to you.";
                }
            }
        }

        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    public function reset_password($email, $token)
    {
        $error = "";
        $is_reset_password = $this->load_model("UsersModel")->is_reset_password($email, $token);
        if ($is_reset_password)
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                if (!Security::is_valid_token($_POST["token"]))
                {
                    $error = "This page has been expired";
                }
                else
                {
                    $password = $_POST["password"];
                    $confirm_password = $_POST["confirm_password"];

                    if ($password == $confirm_password)
                    {
                        $this->load_model("UsersModel")->reset_password($email, $token);
                        header("Location: " . URL . "home/index/reset");
                        exit();
                    }
                    else
                    {
                        $error = "Password does not match";
                    }
                }
            }

            require_once $this->get_header();
            require_once VIEW . 'user/reset_password.php';
            require_once $this->get_footer();
        }
        else
        {
            show_404();
        }
    }

    public function goto_login()
    {
        header("Location: " . URL . "user/login");
    }

    public function logout()
    {
        unset($_SESSION["user"]);
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    public function update_profile()
    {
        if (isset($_SESSION["user"]))
        {
            $user = $this->get_logged_in_user();
            
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $folder_path = $user->profile_image;
                if ($_FILES["profile_image"]["error"] == 0)
                {
                    unlink($folder_path);
                    
                    $folder_path = "uploads/profile_images/" . $user->id . "_" . $_FILES["profile_image"]["name"];
                    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $folder_path);
                }
                
                $this->load_model("UsersModel")->update_profile($user->id, $folder_path);
            }
            
            require_once VIEW . "header.php";
            require_once VIEW . "update_profile.php";
            require_once VIEW . "footer.php";
        }
        else
        {
            $this->goto_login();
        }
    }

    public function subscribe()
    {
        $this->load_model("SubscriberModel")->subscribe();
        
        $_SESSION["success_subscribe"] = "User has been subscribed";
        $_SESSION["subscribe_email"] = $_POST["email"];

        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    public function unsubscribe($email)
    {
        $this->load_model("SubscriberModel")->unsubscribe($email);

        require_once VIEW . "layout/header.php";
        require_once VIEW . "unsubscribe.php";
        require_once VIEW . "layout/footer.php";
        
    }
}