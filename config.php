<?php

error_reporting(9999999);
ini_set('display_errors', 1);

$verify_token = 'Token123Test';
//$token = "EAABgZCEcIOZCkBAP0j1VPnjPZAi6EsBrfv4d34dqXWDuKZAPTOaJXccht4ehBfHdVDwC7TWcmiecSWCwd4XfJOu5iwfhhbfrzQw76je1vYL5ceL2IvAvisutz8uOXQoiEsgZC7x4pXnmAdQgBMlyaAjyuacLrb4xW8vpSZAQlZCLQZDZD";
//$token='EAAXGfZBD4kzkBANHKInrujrBjZAPvo4zuqLjZADbeZCujhds731tzwDSgS8PmVoa44foDAhYqkii08AJr57NUqIbZCthzST1BsBxiRz2qrecDR2z5YT8a6Ub4pDmeHRZB0oM9trO4qOhyVHlg1SlWxZAV0qEseeDxhIe5CvFsV7GgZDZD';
$token='EAAXGfZBD4kzkBAODH8eNlR7hNN7HOZBnW21YMvFzpZBY6ZCEv6rruZC30VW8IfW0pzjTa2jpRnLut7HPkgZCrUy46inY10ZBPIkKhS9WvbuXJAAadkyj6tRZB3KjyPQqFDp6QEBjJacvhcGYanD2hSBMSF27jCLUQ8dsiLhp5733WgZDZD';

/**
 *  Cloud9 MySql
 **/ 

$servername = "127.0.0.1";
$username = "danimagna";
$password = "";
$database = "maidsatbot";
$dbport = 3306;


/**
 * Salesforce
 */ 
define('sf_username','maids.ae@magna.com.sandbox') ; 
define('sf_password','RockandRoll#01lKn7hqI3Ykm1j0gg8lfDIE4S') ; 
define('sf_endpoint',dirname(__FILE__).'/salesforce-php-toolkit/soapclient/partner.wsdl.xml') ;

/**
/*  Openshift
/**
$dburl ='mysql://adminZvN61vt:BTgWDQ2k_3MA@577e55a97628e16d32000062-mediatestbot.rhcloud.com:51446/';
$servername = "577e55a97628e16d32000062-mediatestbot.rhcloud.com";
$username = "adminZvN61vt";
$password = "BTgWDQ2k_3MA";
$database = "maidsatbot;
$dbport = 51446;
**/

?>