<?php

 /**
  *  @file dbtest.php
  *  @autho Anas Yassine <anasyy.mana@gmail.com>
  */
require_once 'config.php';
require_once ('db.php');
require_once ('autoload.php');


try
{
  
$uid='1047107118700838';
echo checkUser  (null,$uid) ; 
echo getUserData ( null , $uid, 'UserName') ; 
echo updateUser ( null , $uid, 'Interactions',333) ; 
echo  insertUser( null , '123testid', '123testfname', '123testlname', '123testpic', '123testgender', '123testcountry', '123testcandidate', '123testrequirement', '123testsatisfaction', '123testsubscribed', '13', '123testtalking', '123testinitializing', date( "Y-m-d\TH:i:s.000\Z" ) , date( "Y-m-d\TH:i:s.000\Z" ));
echo getTimeDiff(null,$uid);
}
catch ( Exception $e )
{
  print_r($e) ; 
  echo ( "Exception Ocurred .") ; 
 
}
?>