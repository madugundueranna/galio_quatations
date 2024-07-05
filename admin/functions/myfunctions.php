<?php
if (!function_exists('redirecte')) {
    function redirecte($url, $message)
    {
        $_SESSION['message'] = $message;
        echo "<script>window.location.href='$url';</script>";
        exit;
    }
}
?>
