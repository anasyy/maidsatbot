<?php

require_once  'config.php' ;

// check token at setup
if ($_REQUEST['hub_verify_token'] === $verify_token) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

require_once dirname(__FILE__) . '/autoload.php';

use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\Messages\VideoMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageQuickReply;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;

/*********************************************** Database Related***************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

	
    // Create connection
    $db = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed : " . $db->connect_error);
    } 
    //echo "Connected successfully (".$db->host_info.")";
//------------------------------------------------------------------------------------------------------------------------------    

//Checking if the user is already in our database-------------------------------------------------------------------------------
    function checkUser($dbInstance, $id)
    {
        $sql = "SELECT `ID`, `UserId`, `UserName`, `ProfilePic`, `Gender`, `Country`, `Candidate`, `Requirement`, `Satisfaction`, `Subscribed`, `Interactions`, `Talking`, `Initializing`, `FirstReached`, `LastReached` FROM `Test_Users_List` WHERE `UserId`=";
        $sql .= "'".$id."'";
        
        $result = $dbInstance->query($sql);
        
        if ($result->num_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Get a certain field in a user's data-------------------------------------------------------------------------------
    function getUserData($dbInstance, $id, $field)
    {
        $sql = "SELECT `";
        $sql .= $field;
        $sql .= "` FROM `Test_Users_List` WHERE `UserId`=";
        $sql .= "'".$id."'";
        
        $result = mysqli_query($dbInstance, $sql);
        $row = mysqli_fetch_array($result);
        $value = $row[$field];
        
        return $value;
    }
//------------------------------------------------------------------------------------------------------------------------------

//update a certain user info in the database---------------------------------------------------------------------------------------------    
    function updateUser($dbInstance, $id, $field, $value)
    {
        $sql = "UPDATE `Test_Users_List` SET `";
        $sql .= $field."` = ";
        $sql .= "'".$value."'";
        $sql .= " Where userId = '";
        $sql .= $id."'";
        
        if (mysqli_query($dbInstance, $sql))
        {
           echo "record updated successfully";
        }
        else
        {
           echo "Error: " . $sql . "<br>" . mysqli_error($dbInstance);
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Insert a new user in the database---------------------------------------------------------------------------------------------    
    function insertUser($dbInstance, $id, $fname, $lname, $pic, $gender, $country, $candidate, $requirement, $satisfaction, $subscribed, $interactions, $talking, $initializing, $firstReached, $lastReached)
    {
        $sql = "INSERT INTO Test_Users_List (UserId, UserName, ProfilePic, Gender, Country, Candidate, Requirement, Satisfaction, Subscribed, interactions, Talking, Initializing, FirstReached, LastReached) VALUES ('";
        $sql .= $id;
        $sql .= "', '";
        $sql .= $fname." ".$lname;
        $sql .= "', '";
        $sql .= $pic;
        $sql .= "', ";
        $sql .= "'".$gender."'";
        $sql .= ", ";
        $sql .= "'".$country."'";
        $sql .= ", ";
        $sql .= "'".$candidate."'";
        $sql .= ", ";
        $sql .= "'".$requirement."'";
        $sql .= ", ";
        $sql .= "'".$satisfaction."'";
        $sql .= ", ";
        $sql .= $subscribed;
        $sql .= ", ";
        $sql .= $interactions;
        $sql .= ", ";
        $sql .= $talking;
        $sql .= ", ";
        $sql .= $initializing;
        $sql .= ", ";
        $sql .= "'".$firstReached."'";
        $sql .= ", ";
        $sql .= "'".$lastReached."'";
        $sql .= ")";
        
        if (mysqli_query($dbInstance, $sql))
        {
           echo "New record created successfully";
        }
        else
        {
           echo "Error: " . $sql . "<br>" . mysqli_error($dbInstance);
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Get the time difference between now and lastReached---------------------------------------------------------------------------

function getTimeDiff($dbInstance, $id)
    {
        $now = date("Y-m-d G:i:s", strtotime('+4 hours'));
        
        $sql = "Select time_to_sec(timediff(";
        $sql .= "'".$now."',";
        $sql .= "LastReached)) AS Time_Diff From `Test_Users_List` Where userId = ";
        $sql .= "'".$id."'";
        
        $result = mysqli_query($dbInstance, $sql);
        $row = mysqli_fetch_array($result);
        $value = $row['Time_Diff'];
        
        return $value;
    }

//------------------------------------------------------------------------------------------------------------------------------

/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

// Make Bot Instance
$bot = new FbBotApp($token);
// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

    // Other event

    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);
    if (!empty($data['entry'][0]['messaging'])) {
        foreach ($data['entry'][0]['messaging'] as $message) {

            // Skipping delivery messages
            if (!empty($message['delivery'])) {
                exit;
            }
            
            $user = $bot->userProfile($message['sender']['id']);

            $id = $message['sender']['id'];
            $fname = $user->getFirstName();
            $lname = $user->getLastName();
            $pic = $user->getPicture();
            $gender = $user->getGender();
            $country = 'Undefined';
            $candidate = "";
            $requirement = "";
            $satisfaction = "";
            $subscribed = 0;
            $interactions = 1;
            $talking = 0;
            $initializing =0;
            
            $timePassed = getTimeDiff($db, $id);
            

            $command = "";

            // When bot receive message from user
            if (!empty($message['message']))
            {
                if(!checkUser($db, $id))
                {
                    $firstReached = date("Y-m-d G:i:s", strtotime('+4 hours'));
                    $lastReached = date("Y-m-d G:i:s", strtotime('+4 hours'));
                    insertUser($db, $id, $fname, $lname, $pic, $gender, $country, $candidate, $requirement, $satisfaction, $subscribed, $interactions, $talking, $initializing, $firstReached, $lastReached);
                    $command = "FirstTimeVisitor";
                }
                else
                {
                    $lastReached = date("Y-m-d G:i:s", strtotime('+4 hours'));
                    updateUser($db, $id, 'LastReached', $lastReached);
                    $subscribed = getUserData($db, $id, 'Subscribed');
                    $interactions = getUserData($db, $id, 'Interactions');
                    $interactions = $interactions + 1;
                    updateUser($db, $id, 'Interactions', $interactions);
                    $command = $message['message']['text']; 
                }
            }
            // When bot receive button click from user
            else if (!empty($message['postback'])) {
                $lastReached = date("Y-m-d G:i:s", strtotime('+4 hours'));
                updateUser($db, $id, 'LastReached', $lastReached);
                $subscribed = getUserData($db, $id, 'Subscribed');
                $interactions = getUserData($db, $id, 'Interactions');
                $interactions = $interactions + 1;
                updateUser($db, $id, 'Interactions', $interactions);
                $command = $message['postback']['payload'];
            }
            
// Handle command------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------

            switch (strtolower($command)) {

                case "firsttimevisitor":
                case "start":
                    $initialized = getUserData($db, $id, 'Initializing');
                    if ($initialized == 0)
                    {
                        updateUser($db, $id, 'Initializing', 1);
                        $bot->send(new Message($message['sender']['id'], "Congratulations ".$fname.", If you are currently a housemaid in an Arab country, you are automatically approved to work for Maids.at :)"));
                        sleep(6);
                        $bot->send(new Message($message['sender']['id'], "When your employer tells you your exact travel date to the Philippines,immediately message us here or miss call us on +1-424-2430506 to organize your flight back to Dubai."));
                        sleep(9);
                 /*
                        $bot->send(new Message($message['sender']['id'], "Can you tell us where are you now?"));
                        sleep(2);
                        $buttonsElementuae = [new MessageButton(MessageButton::TYPE_POSTBACK, 'UAE')];
                        $buttonsElementqatar = [new MessageButton(MessageButton::TYPE_POSTBACK, 'Qatar')];
                        $buttonsElementsaudi = [new MessageButton(MessageButton::TYPE_POSTBACK, 'Saudi')];
                        $buttonsElementbahrain = [new MessageButton(MessageButton::TYPE_POSTBACK, 'Bahrain')];
                        $buttonsElementoman = [new MessageButton(MessageButton::TYPE_POSTBACK, 'Oman')];
                        $buttonsElementkuwait = [new MessageButton(MessageButton::TYPE_POSTBACK, 'Kuwait')];
                        $bot->send(new StructuredMessage($message['sender']['id'],
                            StructuredMessage::TYPE_GENERIC,
                            [
                                'elements' => [new MessageElement("UAE", "", "http://i.imgur.com/By1Gjb0.png",$buttonsElementuae),
                                           new MessageElement("Qatar", "", "http://i.imgur.com/wcB92J1.jpg", $buttonsElementqatar),
                                           new MessageElement("Bahrain", "", "http://i.imgur.com/FeIl7E4.png", $buttonsElementbahrain),
                                           new MessageElement("Saudi", "", "http://i.imgur.com/zSwjYn4.png", $buttonsElementsaudi),
                                           new MessageElement("Oman", "", "http://i.imgur.com/FUO8icM.png", $buttonsElementoman),
                                           new MessageElement("Kuwait", "", "http://i.imgur.com/RgdXMom.jpg", $buttonsElementkuwait)]
                            ]
                        ));
                        sleep(3);
                
                        updateUser($db, $id, 'Initializing', 0);
                    }
                    else exit;
                  break;
                  
                  
                case 'uae':
                case 'qatar':
                case 'saudi':
                case 'oman':
                case 'kuwait':
                case 'bahrain':
                        $initialized = getUserData($db, $id, 'Initializing');
                        if ($initialized == 0)
                        {
                            updateUser($db, $id, 'Initializing', 1);
                            updateUser($db, $id, 'Country', $command);    
                            $bot->send(new Message($message['sender']['id'], "Ok great, Watch these videos to learn more, and then ask us any question"));
                            sleep(3);
                            $buttonsElement1 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1145735112104073/')];
                            $buttonsElement2 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1145650345445883/')];
                            $buttonsElement3 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1143447275666190/')];
                            $buttonsElement4 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1169929476351303/')];
                            $buttonsElement5 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1143447142332870/')];
                            $buttonsElement6 = [new MessageButton(MessageButton::TYPE_WEB, 'Watch', 'https://www.facebook.com/www.maids.at/videos/1145562465454671/')];
                            $bot->send(new StructuredMessage($message['sender']['id'],
                            StructuredMessage::TYPE_GENERIC,
                                [
                                    'elements' => [new MessageElement("Maligayang Pagsali sa Maids.at", "", "http://i.imgur.com/kCCZOR9.png",$buttonsElement1),
                                           new MessageElement("Ang kwento ni Annilyn", "", "http://i.imgur.com/6yrRUaD.png", $buttonsElement2),
                                           new MessageElement("Libutin mo ang opisina nang Maids.at", "", "http://i.imgur.com/2L5I1RZ.png", $buttonsElement3),
                                           new MessageElement("Animation tungkol sa pagligtas namin sa inyo mula sa inyong amo", "", "http://i.imgur.com/T2V5aDk.png", $buttonsElement4),
                                           new MessageElement("Ehemplo nang maid na nakuha sa airport", "", "http://i.imgur.com/IEGWFWA.png", $buttonsElement5),
                                           new MessageElement("Masaya sa Maids.at", "", "http://i.imgur.com/lsFlQL2.png", $buttonsElement6)]
                                ]
                            ));
                            sleep(20);
                    */
                            $bot->send(new Message($message['sender']['id'],'Ok, great. Watch this video to learn more, and then ask us any questions.')) ; 
                            sleep(1);
                            $bot->send(new VideoMessage($message['sender']['id'],'http://www.html5videoplayer.net/videos/toystory.mp4'));
                            sleep(10);
                            $bot->send(new Message($message['sender']['id'], "Thank you for watching the video ".$fname.", we're done, Just a couple of questions :)"));
                            sleep(3);
                            $bot->send(new Message($message['sender']['id'],
                                'How likely would you join us?',
                                [
                                    new MessageQuickReply('Likely', 'Likely'),
                                    new MessageQuickReply('Maybe', 'Maybe'),
                                    new MessageQuickReply('Unlikely', 'Unlikely')
                                ]
                            ));
                            sleep(3);
                            updateUser($db, $id, 'Initializing', 0);
                        }
                        else exit;
                        break;
                  
                    
                case 'unlikely':
                case 'maybe':
       
                    updateUser($db, $id, 'Candidate', $command);
                    $bot->send(new Message($message['sender']['id'],
                      'Ok, what can we do to convince you?',
                      [
                          new MessageQuickReply('Safety guarantee','Safety guarantee'),
                          new MessageQuickReply('Transport guarantee','Transport guarantee'),
                          new MessageQuickReply('Raise your salary','Raise your salary') 
                      ]
                    ));
                    break;    
                case 'likely':
                    
                  //  updateUser($db, $id, '????', $command);
                    $bot->send(new Message($message['sender']['id'],
                      'What made you interested in joining us? :)',
                      [
                          new MessageQuickReply('Good salary','Good salary'),
                          new MessageQuickReply('Plenty of Benefits','Plenty of Benefits'),
                          new MessageQuickReply('Protection from Bad Clients','Protection from Bad Clients') 
                      ]
                    ));
                    break;
                
                case 'raise your salary':
                case 'safety guarantee':
                case 'transport guarantee':
                    updateUser($db, $id, 'Requirement', $command);
                    $bot->send(new StructuredMessage($message['sender']['id'],
                      StructuredMessage::TYPE_BUTTON,
                      [
                          'text' => "Ok one last question, How satisfied are you with how we answered your questions?",
                          'buttons' => [
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'Satisfied'),
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'Normal'),
                              new MessageButton(MessageButton::TYPE_POSTBACK, 'Unsatisfied')
                          ]
                      ]
                    ));
                    break;    
               
                case 'raise your salary':
                case 'safety guarantee':
                case 'transport guarantee':
                    //updateUser($db,$id,'????',$command);
                    $bot->send(new Message($message['sender']['id'],
                      'Ok '.$fname.', That\'s it, would you like to get updated about your job and travel information between now and the travel date? This is important information for you, but you can turn it off anytime by typing \'notification off\' ',
                       [ new MessageQuickReply( 'Yes, update me', 'Yes, update me') ]
                  ));
                break;
                
                case "unsatisfied":
                case "normal":
                case "satisfied":
                    updateUser($db, $id, 'Satisfaction', $command);
                   
                    $bot->send(new Message($message['sender']['id'],
                      'Ok '.$fname.', That\'s it, would you like to get updated about your job and travel information between now and the travel date? This is important information for you, but you can turn it off anytime by typing \'notification off\' ',
                       [ new MessageQuickReply( 'Yes, update me', 'Yes, update me') ]                ));
                  break;
                    
                case 'yes, update me':
                case 'notifications on':
                    updateUser($db, $id, 'Subscribed', 1);
                    $bot->send(new Message($message['sender']['id'], "OK thank you ".$fname." :)
We will be updating you as we have new information about your job and travel arrangements."));
                    break;
                
                case 'notifications off':
                    updateUser($db, $id, 'Subscribed', 0);
                    $bot->send(new Message($message['sender']['id'], "Notifications turned off, but that's really breaks our hearts, ".$fname." :(
You can turn them on again by typing 'Notifications On'"));
                    break;
                
                case '':
                    break;
                
                default:
                    $initialized = getUserData($db, $id, 'Initializing');
                    if ($initialized == 1)
                    {
                        exit;
                    }
                    else
                    {
                        $talking = getUserData($db, $id, 'Talking');
                        if($talking == 0)
                        {
                            updateUser($db, $id, 'Talking', 1);
                            $bot->send(new Message($message['sender']['id'], "We will route you now to one of our hiring managers ".$fname.", it may take a minute or two, feel free to ask him any question that comes to your mind :) :)"));
                            sleep(3);
                            $bot->send(new Message($message['sender']['id'], "Routing..."));
                        }
                        else if ($talking == 1)
                        {
                            if ($timePassed > 86400)
                            {
                                updateUser($db, $id, 'Initializing', 1);
                                updateUser($db, $id, 'Talking', 0);
                                $bot->send(new Message($message['sender']['id'], "When your employer tells you your exact travel date to the Philippines,immediately message us here or miss call us on +1-424-2430506 to organize your flight back to Dubai."));
                                sleep(9);
                            
                                $bot->send(new Message($message['sender']['id'],'Watch this video to learn more, and then ask us any questions.')) ; 
                                sleep(1);
                                $bot->send(new VideoMessage($message['sender']['id'],'http://www.html5videoplayer.net/videos/toystory.mp4'));
                                sleep(10);
                                updateUser($db, $id, 'Initializing', 0);
                            }
                        }
                    }
            }
        }
    }
}


function LogRequest ($output) 
{
	$filename = 'log.txt' ; 
	$date     = date ('YYYY-mm-DD HH::MM:SS') ;
	$server   = var_export ( $_SERVER  , true ) ; 
	$request  = var_export ( $_REQUEST , true ) ; 
	$input    = file_get_contents ( 'php://input' ) ; 
	$log 	  = "Start: $date\n Server : \n Input: \n $input \n Output : \n \{ $output \} \n End \n" ;
	
	file_put_contents ( $filename , $log , FILE_APPEND ) ;
	
}

?>