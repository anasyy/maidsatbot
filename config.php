<?php

error_reporting(9999999);
ini_set('display_errors', 1);

$verify_token = 'Token123Test';
//$token = "EAABgZCEcIOZCkBAP0j1VPnjPZAi6EsBrfv4d34dqXWDuKZAPTOaJXccht4ehBfHdVDwC7TWcmiecSWCwd4XfJOu5iwfhhbfrzQw76je1vYL5ceL2IvAvisutz8uOXQoiEsgZC7x4pXnmAdQgBMlyaAjyuacLrb4xW8vpSZAQlZCLQZDZD";
//$token='EAAXGfZBD4kzkBANHKInrujrBjZAPvo4zuqLjZADbeZCujhds731tzwDSgS8PmVoa44foDAhYqkii08AJr57NUqIbZCthzST1BsBxiRz2qrecDR2z5YT8a6Ub4pDmeHRZB0oM9trO4qOhyVHlg1SlWxZAV0qEseeDxhIe5CvFsV7GgZDZD';
$token='EAADZCyJRvqsoBAFPCPacUAdU6o2NXOU9DPJICwtjh6ImZBzYYLMnoJnztqRvHp60E3rYfSA6Pz3YEWoJ5eg2Y5OZBaAkHwMN9alALOXYq2Uctr3VxrJ4A0XBBDkLdVI9oHEVZAegb2nfWsAdlvtZB01LhWDifrUL2hZCaScbEy2QZDZD';


$servername = "127.0.0.1";
$username = "danimagna";
$password = "";
$database = "maidsatbot";
$dbport = 3306;



$sf_username = 'maids.ae@magna.com.sandbox' ; 
$sf_password = 'RockandRoll#01lKn7hqI3Ykm1j0gg8lfDIE4S' ; 

$sf_wsdl     = dirname(__FILE__).'/salesforce-php-toolkit/soapclient/partner.wsdl.xml';


/**
$dburl ='mysql://adminZvN61vt:BTgWDQ2k_3MA@577e55a97628e16d32000062-mediatestbot.rhcloud.com:51446/';
$servername = "577e55a97628e16d32000062-mediatestbot.rhcloud.com";
$username = "adminZvN61vt";
$password = "BTgWDQ2k_3MA";
$database = "maidsatbot;
$dbport = 51446;
**/


function end_flush()
{
    //header('HTTP/1.1 200 OK');
    http_response_code (200);
    flush();
    exit() ; 
}

function __sleep($s)
{}
register_shutdown_function('end_flush');
?>