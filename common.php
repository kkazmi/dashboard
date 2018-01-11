<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_NONE);
ini_set('display_errors', '0');
define("_BASE_URL_PATH_", "wallet");
define("IN_WALLET", true);
define("ADMIN_EMAIL", "rapidzhelp@gmail.com");



session_start();
//header('Cache-control: private'); // IE 6
$testing = 1;
$dbserverflag =0;
if ($dbserverflag == 1) {
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "wallet";
    define("_LIVE_", true); // for testing in local system no coin function will call
} else {
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "wallet";
    define("_LIVE_", false); // for testing in local system no coin function will call
}

define("WITHDRAWALS_ENABLED", true); //Disable withdrawals during maintenance

$mysqli = new Mysqli($db_host, $db_user, $db_pass, $db_name);


include('jsonRPCClient.php');
if ($testing == 1) {
    include('classes/Client.php');
} else {
    include('classes/Client.php');
}
include('classes/User.php');

// function by zelles to modify the number to coin format ex. 0.00120000
function satoshitize($satoshitize2)
{
    return sprintf("%.8f", $satoshitize2);
}

function isEmail($email)
{
    return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? true : false;
}

function getRandomString($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}


function getRandomOTPString($length = 8)
{
    $string = '';
    $characters = '0123456789';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}

// function by zelles to trim trailing zeroes and decimal if need
function satoshitrim($satoshitrim)
{
    return rtrim(rtrim($satoshitrim, "0"), ".");
}

$dir_path = "mywallet";

$server_ip = "127.0.0.1";
$server_url = "http://".$server_ip."/".$dir_path."/";  // website url

$rpc_host = "137.59.23.42"; 
$rpc_user="rapidzrpcvps"; 
$rpc_pass="2MUbk4hPUpvps"; 
$rpc_port="23550";
/*
$rpc_host ="110.173.57.186";
$rpc_user="rapidzrpc";
$rpc_pass="2MUbk4hPUp";
$rpc_port="23548";
*/
$coin_fullname = "Rapidz Coin "; //Website Title (Do Not include 'wallet')
$coin_short = "RDZ"; //Coin Short
$server_name = "";
$explorer = "href='http://:3001/'"; // explorer link
$logo = "image/logofinal.png"; //logo ( logo should be 65 height max and width 200 px max)
$favicon ="image/favicon.ico"; // favicon (should be in .ico )

$blockchain_url = $server_name.":3001/tx/"; //Blockchain Url
$support = "rapidzhelp@gmail.com"; //Your support eMail
$hide_ids = array(1); //Hide account from admin dashboard
$donation_address = ""; //Donation Address


$fee = "0.001"; //Set a fee to prevent negitive balances.

function sendpmail($smto, $smsub, $smbody)
{
    $email_from ="rapidzhelp@gmail.com";
    $headers = 'From: '.$email_from."\r\n".
                                'Reply-To: '.$email_from."\r\n" .
                                'X-Mailer: PHP/' . phpversion();
    @mail($smto, $smsub, $smbody, $headers);
}

function sendMailToAdmin($smto, $smfrom, $smsub, $smbody)
{
    $headers = 'From: '.$smfrom."\r\n".
                                'Reply-To: '.$smfrom."\r\n" .
                                'X-Mailer: PHP/' . phpversion();
    @mail($smto, $smsub, $smbody, $headers);
}


function page_protect()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
            logout();
            exit;
        }
    }
}
function filter($data)
{
    $data = trim(htmlentities(strip_tags($data)));
    if (get_magic_quotes_gpc()) {
        $data = stripslashes($data);
    }
    //  $data = mysql_real_escape_string($data);
    return $data;
}
function logout()
{
    global $db;
    global $pathString;
    session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email_id']);
    unset($_SESSION['user_session']);
    unset($_SESSION['user_admin']);
    unset($_SESSION['user_supportpin']);
    unset($_SESSION['HTTP_USER_AGENT']);
    session_unset();
    session_destroy();
    header("Location:login.php");
}
