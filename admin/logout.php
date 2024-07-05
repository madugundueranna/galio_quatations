<?php
session_start();
unset( $_SESSION['USER_ID']);
unset( $_SESSION['USER_NAME']);
unset( $_SESSION['ROLE']);
header("location:index.php");
?>