<?php 
session_start();
function sendWhatsAppMessage($phone, $otp_msg)
{
    $username = "kumarpumps";

    $password = "Ramesh@123";

    $message = $otp_msg;


    $mobile_number = $phone;

    $url = "https://login.bulksmsgateway.in/textmobilesmsapi.php?user=" . urlencode($username) . "&password=" . urlencode($password) . "&mobile=" . urlencode($mobile_number) . "&message=" . urlencode($message) . "&type=" . urlencode('3');

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);
}
