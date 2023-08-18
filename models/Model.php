<?php

class Model
{
	protected $connection = null;
	
	public function __construct()
	{
	    if ($this->connection == null)
	    {
		    $this->connection = mysqli_connect("localhost", "root", "root", "moviepoint");
	    }
	}

    public function secure_input($input)
    {
        $input = str_replace("'", "", $input);
        $input = str_replace("\"", "", $input);
        $input = str_replace(" ", "-", $input);
        $input = mysqli_real_escape_string($this->connection, $input);
        return $input;
    }
}