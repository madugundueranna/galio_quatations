<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
define('SITE_ON_LIVE', false);

if (SITE_ON_LIVE) {

    define('DB_SERVER', "");

    define('DB_DATABASE', "");

    define('DB_USER', "");

    define('DB_PASS', "");
} else {

    define('DB_SERVER', "localhost");

    define('DB_DATABASE', "u130520044_gailo_quoation");

    define('DB_USER', "root");

    define('DB_PASS', "");
}


$conn= mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
if (!$conn) die(mysqli_connect_error());