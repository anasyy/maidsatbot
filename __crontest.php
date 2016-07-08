<?php

require_once 'config.php';

// check token at setup
if ($_REQUEST['hub_verify_token'] === $verify_token) {
  echo $_REQUEST['hub_challenge'];
  exit;
}


require_once dirname(__FILE__) . '/autoload.php';

use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;


// Make Bot Instance
$bot = new FbBotApp($token);

/*********************************************** Database Related***************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

//Database Connecting-----------------------------------------------------------------------------------------------------------



    // Create connection
    $db = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } 
    echo "Connected successfully (".$db->host_info.")";
//------------------------------------------------------------------------------------------------------------------------------    

//Sending Messages to subscribed users in our database-------------------------------------------------------------------------------
    
    $sql = "SELECT `UserId`, `UserName`, `ProfilePic`, `Country`, `Subscribed`, `Interactions` FROM `Test_Users_List` WHERE `UserId`= '10207712843832888'";
    $result = mysqli_query($db, $sql);
        
    while ( $row = $result->fetch_assoc() )
    {
        $bot->send(new ImageMessage($row["UserId"], 'https://video-yyz1-1.xx.fbcdn.net/v/t42.3356-2/13478651_1196669287010655_830128790_n.mp4/video-1466158316.mp4?vabr=79728&oh=25e6ebd4b546b3ac8bb6bd88bf69689a&oe=576524C3&dl=1'));
    }
        
    $result->free();
    
//------------------------------------------------------------------------------------------------------------------------------

/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/




