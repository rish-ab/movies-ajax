<?php

class SeasonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function detail($id)
    {
        $season = $this->load_model("SeasonModel")->get($id);

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'seasons/detail.php';
        require_once VIEW . "layout/footer.php";
    }
}
