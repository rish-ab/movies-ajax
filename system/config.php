<?php
	define("URL", "http://movie/");
	define("VIEW", "views/");
    define("PUBLIC_PATH", URL . "public/");
	define("CSS", URL . "public/css/");
	define("JS", URL . "public/js/");
	define("IMG", URL . "public/img/");

    define("ADMIN_VIEW", VIEW . "admin/");
    define("ADMIN_CSS", URL . "public/admin/css/");
    define("ADMIN_JS", URL . "public/admin/js/");
    define("ADMIN_IMG", URL . "public/admin/img/");
    define("ADMIN_VENDOR", URL . "public/admin/vendors/");
	
	define("DB_HOST", "localhost:3306");
	define("DB_USER", "root");
    //your MY SQL, mine is "root" db password here
	define("DB_PASS", "root1234");
	define("DB_NAME", "moviepoint");

    /* setup stripe secret and publishable keys */
    define("STRIPE_SECRET_KEY", "");
    define("STRIPE_PUBLISHABLE_KEY", "");
?>