<?php


class UsersModel extends Model
{
    private $table = "users";

    public function register()
    {
        $name = $_POST["name"];
        $mobile_number = $_POST["mobile_number"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $sql = "INSERT INTO `" . $this->table . "`(`name`, `email`, `password`, mobile_number, verification_code) VALUES ('$name', '$email', '$password', '$mobile_number', '" . $verification_code . "')";
        mysqli_query($this->connection, $sql);

        return $verification_code;
    }

    public function is_exists($email)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = '$email'";
        $result = mysqli_query($this->connection, $sql);

        return mysqli_num_rows($result) > 0;
    }

    public function login()
    {
        $response = array();
        $response["error"] = "";
        $response["msg"] = "";

        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = '$email'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_object($result);
            if (password_verify($password, $row->password))
            {
                if ($row->verified_at == NULL)
                {
                    $response["error"] = "Please verify your account in order to activate.";
                }
                else
                {
                    $response["message"] = $row;
                }
            }
            else
            {
                $response["error"] = "Password does not match";
            }
        }
        else
        {
            $response["error"] = "Email does not exists";
        }

        return $response;
    }

    public function get($user_id)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `id` = '$user_id'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
            return mysqli_fetch_object($result);
        else
            return null;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM `" . $this->table . "` ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function get_by_email($email)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = '$email'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
            return mysqli_fetch_object($result);
        else
            return null;
    }

    public function do_verify_email()
    {
        $email = $_POST["email"];
        $verification_code = $_POST["verification_code"];

        $sql = "SELECT * FROM `" . $this->table . "` WHERE email = '$email' AND verification_code = '$verification_code'";
        $result = mysqli_query($this->connection, $sql);

        $is_verified = mysqli_num_rows($result) > 0;
        if ($is_verified)
        {
            $sql = "UPDATE `" . $this->table . "` SET verified_at=NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
            mysqli_query($this->connection, $sql);
        }

        return $is_verified;
    }

    public function make_movie_manager($user_id)
    {
        $role = "manage_movies";
        
        $sql = "UPDATE `" . $this->table . "` SET role='" . $role . "' WHERE id = '" . $user_id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function forgot_password($token)
    {
        $email = $_POST["email"];

        $sql = "UPDATE `" . $this->table . "` SET reset_password='$token' WHERE email = '$email'";
        mysqli_query($this->connection, $sql);
    }

    public function is_reset_password($email, $token)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE email = '$email' AND reset_password = '$token'";
        $result = mysqli_query($this->connection, $sql);

        return mysqli_num_rows($result) > 0;
    }

    public function reset_password($email, $token)
    {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $sql = "UPDATE `" . $this->table . "` SET password='$password', reset_password = NULL WHERE email = '$email' AND reset_password = '$token'";
        mysqli_query($this->connection, $sql);
    }

    public function update_profile($user_id, $folder_path)
    {
        $sql = "UPDATE `" . $this->table . "` SET `profile_image` = '" . $folder_path . "' WHERE id = '" . $user_id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function do_delete($id)
    {
        $sql = "DELETE FROM `movie_ratings` WHERE user_id = '" . $id . "'";
        mysqli_query($this->connection, $sql);

        $sql = "DELETE FROM `tickets` WHERE user_id = '" . $id . "'";
        mysqli_query($this->connection, $sql);

        $sql = "DELETE FROM `" . $this->table . "` WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }
}