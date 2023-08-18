<?php

class AdminModel extends Model
{
    private $table = "admins";

    public function delete_coupon_code($id)
    {
        $sql = "DELETE FROM `coupon_codes` WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function add_coupon_code($admin_id)
    {
        $movie_id = $_POST["movie_id"];
        $coupon_code = $_POST["coupon_code"];
        $discount = $_POST["discount"];
        $valid_till = $_POST["valid_till"];

        $sql = "INSERT INTO `coupon_codes`(`movie_id`, `coupon_code`, `discount`, `valid_till`, `created_by`, `created_at`) VALUES ('" . $movie_id . "', '" . $coupon_code . "', '" . $discount . "', '" . $valid_till . "', '" . $admin_id . "', NOW())";
        mysqli_query($this->connection, $sql);
    }

    public function get_all_coupon_codes($admin_id)
    {
        $sql = "SELECT * FROM `coupon_codes` WHERE `created_by` = '" . $admin_id . "'";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            // get movie
            $sql_movie = "SELECT * FROM `movies` WHERE `id` = '" . $row->movie_id . "'";
            $result_movie = mysqli_query($this->connection, $sql_movie);

            if (mysqli_num_rows($result_movie) > 0)
            {
                $movie = mysqli_fetch_object($result_movie);
                $row->movie = $movie;
                array_push($data, $row);
            }
        }
        return $data;
    }

    public function delete_movie_manager($id)
    {
        $sql = "DELETE FROM `" . $this->table . "` WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function add_movie_manager()
    {
        $sql = "INSERT INTO `" . $this->table . "`(`name`, `email`, `password`, `picture`, `role`) VALUES ('" . $_POST["name"] . "', '" . $_POST["email"] . "', '" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "', '', 'movie_manager')";
        mysqli_query($this->connection, $sql);
    }

    public function get_all_managers()
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `role` = 'movie_manager'";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function get_user_object()
    {
        $admin_id = $_SESSION["admin"];

        $sql = "SELECT * FROM `" . $this->table . "` WHERE `id` = '" . $admin_id . "'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            $admin_object = mysqli_fetch_object($result);

            $sql = "SELECT * FROM `users` WHERE `email` = '" . $admin_object->email . "'";
            $result = mysqli_query($this->connection, $sql);

            if (mysqli_num_rows($result) > 0)
            {
                return mysqli_fetch_object($result);
            }
        }

        return null;
    }

    public function login($email, $password)
    {
        $response = array();
        $response["error"] = "";
        $response["msg"] = "";

        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = '$email'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_object($result);
            if (password_verify($password, $row->password))
            {
                $response["msg"] = $row;
            }
            else
            {
                $response["error"] = "Password does not match";
            }
        }
        else
        {
            $response["error"] = "Admin does not exists";
        }

        return $response;
    }

    public function get_admin($admin_id)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `id` = '" . $admin_id . "'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) > 0)
            return mysqli_fetch_object($result);
        else
            return null;
    }

    public function update_settings()
    {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        $admin = $this->get_admin($_SESSION["admin"]);
        if (password_verify($current_password, $admin->password))
        {
            if ($new_password == $confirm_password)
            {
                $sql = "UPDATE `" . $this->table . "` SET password = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $_SESSION["admin"] . "'";
                mysqli_query($this->connection, $sql);

                return array(
                    "status" => "success",
                    "message" => "Password has been changed."
                );
            }
            else
            {
                return array(
                    "status" => "error",
                    "message" => "Password does not match."
                );
            }
        }
        else
        {
            return array(
                "status" => "error",
                "message" => "Current password not correct."
            );
        }
    }

    public function update_profile()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];

        $admin = $this->get_admin($_SESSION["admin"]);

        if ($_FILES["picture"]["error"] == 0)
        {
            // delete old image, upload new
            if (file_exists($admin->picture))
            {
                unlink($admin->picture);
            }

            $picture = "uploads/profile_images/" . time() . "-" . $_FILES["picture"]["name"];
            move_uploaded_file($_FILES["picture"]["tmp_name"], $picture);
        }
        else
        {
            $picture = $admin->picture;
        }

        $sql = "UPDATE `" . $this->table . "` SET name='" . $name . "', email='" . $email . "', picture='" . $picture . "' WHERE id = '" . $_SESSION["admin"] . "'";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Profile has been updated"
        );
    }
    
    public function verify_user($id)
    {
        $sql = "UPDATE `users` SET verified_at=NOW() WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function remove_photo()
    {
        $admin = $this->get_admin($_SESSION["admin"]);
        if (file_exists($admin->picture))
        {
            unlink($admin->picture);
        }

        $sql = "UPDATE `" . $this->table . "` SET picture='' WHERE id = '" . $_SESSION["admin"] . "'";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Image has been removed"
        );
    }
}