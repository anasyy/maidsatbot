<?php

require_once  'config.php' ;

// check token at setup
if (!empty($_REQUEST['hub_verify_token'])&&$_REQUEST['hub_verify_token'] === $verify_token) {
  echo $_REQUEST['hub_challenge'];
  end_flush();
}




require_once dirname(__FILE__) . '/autoload.php';
require_once dirname(__FILE__) . '/db.php';

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

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    if (!empty($data['entry'][0]['messaging'])) {
        
        foreach ($data['entry'][0]['messaging'] as $message) {

            // Skipping delivery messages
            if (!empty($message['delivery'])) {
                end_flush();
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

            switch (strtolower($command)) 
            {

                case "firsttimevisitor":
                case "start":
                    $initialized = getUserData($db, $id, 'Initializing');
                    if ($initialized == 0)
                {
                    updateUser($db, $id, 'Initializing', 1);
                    $bot->send(new Message($message['sender']['id'], "Congratulations ".$fname.", If you are currently a housemaid in an Arab country, you are automatically approved to work for Maids.at :)"));
                    __sleep(6);
                    $bot->send(new Message($message['sender']['id'], "When your employer tells you your exact travel date to the Philippines,immediately message us here or miss call us on +1-424-2430506 to organize your flight back to Dubai."));
                    __sleep(9);
        
                        $bot->send(new Message($message['sender']['id'],'Ok, great. Watch this video to learn more, and then ask us any questions.')) ; 
                        __sleep(3);
                        $bot->send(new VideoMessage($message['sender']['id'],'http://www.html5videoplayer.net/videos/toystory.mp4'));
                        __sleep(10);
                        $bot->send(new Message($message['sender']['id'], "Thank you for watching the video ".$fname.", we're done, Just a couple of questions :)"));
                        __sleep(3);
                        $bot->send(new Message($message['sender']['id'],
                            'How likely would you join us?',
                            [
                                new MessageQuickReply('Likely', 'Likely'),
                                new MessageQuickReply('Maybe', 'Maybe'),
                                new MessageQuickReply('Unlikely', 'Unlikely')
                            ]
                        ));
                        __sleep(3);
                        updateUser($db, $id, 'Initializing', 0);
                    }
                    else end_flush();
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
                   updateUser($db, $id, 'Candidate', $command);
                    $bot->send(new Message($message['sender']['id'],
                      'What made you interested in joining us? :)',
                      [
                          new MessageQuickReply('Good salary','Good salary'),
                          new MessageQuickReply('Plenty of Benefits','Transport guarantee'),
                          new MessageQuickReply('Protection','Protection') 
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
                        end_flush();
                    }
                    else
                    {
                        $talking = getUserData($db, $id, 'Talking');
                        if($talking == 0)
                        {
                            updateUser($db, $id, 'Talking', 1);
                            $bot->send(new Message($message['sender']['id'], "We will route you now to one of our hiring managers ".$fname.", it may take a minute or two, feel free to ask him any question that comes to your mind :) :)"));
                            __sleep(3);
                            $bot->send(new Message($message['sender']['id'], "Routing..."));
                        }
                        else if ($talking == 1)
                        {
                            if ($timePassed > 86400)
                            {
                                updateUser($db, $id, 'Initializing', 1);
                                updateUser($db, $id, 'Talking', 0);
                                $bot->send(new Message($message['sender']['id'], "When your employer tells you your exact travel date to the Philippines,immediately message us here or miss call us on +1-424-2430506 to organize your flight back to Dubai."));
                                __sleep(9);
                            
                                $bot->send(new Message($message['sender']['id'],'Watch this video to learn more, and then ask us any questions.')) ; 
                                __sleep(1);
                                $bot->send(new VideoMessage($message['sender']['id'],'http://www.html5videoplayer.net/videos/toystory.mp4'));
                                __sleep(10);
                                updateUser($db, $id, 'Initializing', 0);
                            }
                        }
                    }
            }
        }
    }
}

file_put_contents('log.txt',$input.PHP_EOL,FILE_APPEND);
?>