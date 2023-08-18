<?php

/**
 * SearchController
 */
class SearchController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function Movies($value)
    {
        $data = $this->load_model("MovieModel")->search($value);
        foreach ($data as $d)
        {
            $d->date = date("d", strtotime($d->release_date));
            $d->month = date("M", strtotime($d->release_date));
            $d->year = date("Y", strtotime($d->release_date));

            $d->detail = URL . "movie/detail/" . $d->id;
        }

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'search.php';
        require_once VIEW . "layout/footer.php";
    }

    public function Celebrities($value)
    {
        $data = $this->load_model("CelebrityModel")->search($value);
        foreach ($data as $d)
        {
            $d->date = date("d", strtotime($d->birthday));
            $d->month = date("M", strtotime($d->birthday));
            $d->year = date("Y", strtotime($d->birthday));

            $d->detail = URL . "celebrity/detail/" . $d->id;
        }

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'search.php';
        require_once VIEW . "layout/footer.php";
    }
}
