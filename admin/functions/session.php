<?php include('functions/myfunctions.php');
include('../config/dbcon.php');
if (!isset($_SESSION['USER_ID'])) {
    header("location:index.php");
    die();
}
// print_r($_SESSION);
// exit;

if (isset($_SESSION['id'])) {
    $sql = "SELECT * FROM employees WHERE id=" . $_SESSION['id'] . " AND status=1";
    $res = mysqli_query($conn, $sql);
    $branch = mysqli_fetch_assoc($res);

    if ($branch['status'] == 0) {
        session_destroy();
        header("Location: index.php");
        die();
    }
}
