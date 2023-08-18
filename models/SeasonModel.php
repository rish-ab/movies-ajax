<?php

/**
 * SeasonModel
 */
class SeasonModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function delete_episode($id)
    {
        $episode = $this->get_episode($id);
        if ($episode != null)
        {
            $season = $this->get($episode->season_id);
            if ($season != null)
            {
                $this->deleteDirectory("uploads/seasons/" . $season->name . "/" . $episode->name);
            }            
        }

        $sql = "DELETE FROM `season_episodes` WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Episode has been deleted."
        );
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public function get_episode($id)
    {
        $sql = "SELECT * FROM `season_episodes` WHERE id = '" . $id . "'";
        $result = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($result) == 0)
        {
            return null;
        }
        $data = mysqli_fetch_object($result);
        return $data;
    }

    public function add_episode()
    {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $duration = $_POST["duration"];
        $poster = $_FILES["poster"];
        $video = $_FILES["video"];
        $celebrities = $_POST["celebrities"];
        $imdb_ratings = $_POST["imdb_ratings"];

        $season = $this->get($id);
        if ($season == null)
        {
            return array(
                "status" => "error",
                "message" => "Season does not exists."
            );
        }

        if (!file_exists("uploads/seasons/" . $season->name))
        {
            mkdir("uploads/seasons/" . $season->name);
        }

        if (!file_exists("uploads/seasons/" . $season->name . "/" . $name))
        {
            mkdir("uploads/seasons/" . $season->name . "/" . $name);
        }

        $poster_path = "uploads/seasons/" . $season->name . "/" . $name . "/poster-" . $poster["name"];
        move_uploaded_file($poster["tmp_name"], $poster_path);

        $video_path = "uploads/seasons/" . $season->name . "/" . $name . "/video-" . $video["name"];
        move_uploaded_file($video["tmp_name"], $video_path);

        $sql = "INSERT INTO `season_episodes`(`season_id`, `name`, `duration`, `poster`, `video`, `celebrities`, `imdb_ratings`, `created_at`) VALUES ('" . $id . "', '" . $name . "', '" . $duration . "', '" . $poster_path . "', '" . $video_path . "', '" . $celebrities . "', '" . $imdb_ratings . "', NOW())";
        mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

        return array(
            "status" => "success",
            "message" => "Episode has been added."
        );
    }

    public function update()
    {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $sponsors = $_POST["sponsors"];

        $sql = "UPDATE `seasons` SET name = '" . $name . "', sponsors = '" . $sponsors . "' WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Season has been updated."
        );
    }

    public function get_all_for_home()
    {
        $seasons = $this->get_all();
        $data = [];
        foreach ($seasons as $season)
        {
            array_push($data, $this->get($season->id));
        }
        return $data;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM `seasons` WHERE id = '" . $id . "'";
        $result = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($result) == 0)
        {
            return null;
        }
        $data = mysqli_fetch_object($result);

        $sql = "SELECT * FROM `season_episodes` WHERE season_id = '" . $id . "' ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);
        $episodes = [];
        while ($row = mysqli_fetch_object($result))
        {
            array_push($episodes, $row);
        }
        $data->episodes = $episodes;

        return $data;
    }

    public function destroy($id)
    {
        $season = $this->get($id);
        if ($season != null)
        {
            $this->deleteDirectory("uploads/seasons/" . $season->name);
        }

        $sql = "DELETE FROM `seasons` WHERE id = '" . $id . "'";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Season has been deleted."
        );
    }

    public function get_all()
    {
        $sql = "SELECT * FROM `seasons` ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);

        $data = [];
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function add()
    {
        $name = $_POST["name"];
        $sponsors = $_POST["sponsors"];

        $sql = "INSERT INTO `seasons`(`name`, `sponsors`, `created_at`) VALUES ('" . $name . "', '" . $sponsors . "', NOW())";
        mysqli_query($this->connection, $sql);

        return array(
            "status" => "success",
            "message" => "Season has been added."
        );
    }
}