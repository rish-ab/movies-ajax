<?php

/**
 * SubscriberModel
 */
class SubscriberModel extends Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function subscribe()
    {
        $email = $_POST["email"];

        $sql = "SELECT * FROM subscribers WHERE email = '" . $email . "'";
        $result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($result) == 0)
        {
            $sql = "INSERT INTO subscribers(email) VALUES('" . $email . "')";
            mysqli_query($this->connection, $sql);
        }
    }

    public function get_all()
    {
        $sql = "SELECT * FROM subscribers ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function do_delete($id)
    {
        $sql = "DELETE FROM subscribers WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);
    }

    public function unsubscribe($email)
    {
        $sql = "DELETE FROM subscribers WHERE email = '" . $email . "'";
        mysqli_query($this->connection, $sql);
    }
}
