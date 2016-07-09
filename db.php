<?php
     
    /*
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
         //  echo "record updated successfully";
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
         //  echo "New record created successfully";
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
    
    */
    
    



//Checking if the user is already in our database-------------------------------------------------------------------------------
    function checkUser($dbInstance, $id)
    {
        try
        {
            $q = "SELECT Id, UserId_on_Bot__c FROM Domestic_Helper__c WHERE UserId_on_Bot__c='$id' LIMIT 1";
            
            $c = new SforcePartnerClient();
            
            
            
            $c->createConnection(sf_endpoint);
            $l=$c->login(sf_username, sf_password) ; 
            $r = new QueryResult($c->query($q));
            $c->logout() ; 
            
            if ($r->size > 0)
            {
                return true;
            }
            else
            {
                
                return false;
            }
        }
        catch(Exception $e)
        {
            echo 'Exceptions Ocurred: ' . print_r($e); 
            echo $e->faultString ;
            return null ;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Get a certain field in a user's data-------------------------------------------------------------------------------
    function getUserData($dbInstance, $id, $field)
    {
        $F0 = array (
         'UserId'       => 'UserId_on_Bot__c'    ,
         'UserName'     => 'Name'		         ,
         'ProfilePic'   => 'Picture__c'			 ,
         'Gender'       => 'Gender__c'	         ,
         'Country'      => 'Initial_location__c'                    ,
         'Candidate'    => 'Candidate__c' 	     ,
         'Requirement'  => 'Requirement__c'       ,
         'Satisfaction' => 'Response_Satisfaction__c'         ,
         'Subscribed'   => 'Subscribed__c'       ,
         'Interactions' => 'Interactions__c'     ,
         'Talking'      => 'Talking__c'          , 
         'Initializing' => 'Initializing__c'     ,
         'FirstReached' => 'First_Contact__c'    ,
         'LastReached'  => 'Last_Contact__c'    
        ) ; 
        $fieldReal = $F0[$field]  ; 
        
        try
        {
            $q = "SELECT Id,$fieldReal FROM Domestic_Helper__c WHERE UserId_on_Bot__c='$id' LIMIT 1";
            
            $c = new SforcePartnerClient();
            $c->createConnection(sf_endpoint);
            $l=$c->login(sf_username, sf_password) ; 
            $r = new QueryResult($c->query($q));
            $c->logout() ; 
            
            if ($r->size > 0)
            {
                $r->rewind();
                
                return json_decode(json_encode(new sObject($r->records[0])),true)['fields'][$fieldReal] ;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->faultString ; 
            return null ;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//update a certain user info in the database---------------------------------------------------------------------------------------------    
    function updateUser($dbInstance, $id, $field, $value)
    {
       $F0 = array (
         'UserId'       => 'UserId_on_Bot__c'    ,
         'UserName'     => 'Name'		         ,
         'ProfilePic'   => 'Picture__c'			 ,
         'Gender'       => 'Gender__c'	         ,
         'Country'      => 'Initial_location__c'                    ,
         'Candidate'    => 'Candidate__c' 	     ,
         'Requirement'  => 'Requirement__c'       ,
         'Satisfaction' => 'Response_Satisfaction__c'         ,
         'Subscribed'   => 'Subscribed__c'       ,
         'Interactions' => 'Interactions__c'     ,
         'Talking'      => 'Talking__c'          , 
         'Initializing' => 'Initializing__c'     ,
         'FirstReached' => 'First_Contact__c'    ,
         'LastReached'  => 'Last_Contact__c'    
        ) ; 
       try
        {
            $q = "SELECT Id , UserId_on_Bot__c FROM Domestic_Helper__c WHERE UserId_on_Bot__c='$id' LIMIT 1";
            
            $c = new SforcePartnerClient();
            $c->createConnection(sf_endpoint);
            $l=$c->login(sf_username, sf_password) ; 
            $r = new QueryResult($c->query($q));
            
            
            
            if ($r->size > 0)
            {
                $sid =  json_decode (json_encode(new sObject($r->records[0])),true)['Id'];
                    
                 $h = new sObject () ;
                 $h->fields = array( $F0[$field] => $value) ;
                 $h->type='Domestic_Helper__c' ;
                 $h->Id=$sid;
                
                $c->update(array($h),'Domestic_Helper__c')   ;
                $c->logout();
                
                return true ;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            print_r($e)  ;
            echo $e->faultString ; 
            return null ;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Insert a new user in the database---------------------------------------------------------------------------------------------    
    function insertUser($dbInstance, $id, $fname, $lname, $pic, $gender, $country, $candidate, $requirement, $satisfaction, $subscribed, $interactions, $talking, $initializing, $firstReached, $lastReached)
    {
        $h = array ( 
                     'UserId_on_Bot__c'     => "$id"  ,
                     'Name'                 => "$fname $lname",
                     'Picture__c'           => "$pic"  ,
                     'Gender__c'            => "$gender"  ,
                     'Initial_location__c'  => "$country" ,
                     'Candidate__c'         => "$candidate" ,
                     'Requirement__c'       => "$requirement"  ,
                     'Response_Satisfaction__c'  => "$satisfaction" ,
                     'Subscribed__c'        => "$subscribed"  ,
                     'Interactions__c'      => "$interactions" ,
                     'Talking__c'           => "$talking"    ,
                     'Initializing__c'      => "$initializing" ,
                     'First_Contact__c'     => $firstReached,
                     'Last_Contact__c'      => $lastReached,
                    ) ; 
 
        
        try
        {
            
            $c = new SforcePartnerClient();
            $c->createConnection(sf_endpoint);
            $l=$c->login(sf_username, sf_password) ; 
            $s = new sObject();
            $s->fields=$h;
            $s->type='Domestic_Helper__c'; 
            $c->create(array(json_decode(json_encode($s))),'Domestic_Helper__c');
            $c->logout();
            
        }
        catch(Exception $e)
        {
            print_r($e); 
            return null ;
        }
    }
//------------------------------------------------------------------------------------------------------------------------------

//Get the time difference between now and lastReached---------------------------------------------------------------------------

function getTimeDiff($dbInstance, $id)
    {
         
        try
        {
            $q = "SELECT Last_Contact__c FROM Domestic_Helper__c WHERE UserId_on_Bot__c='$id' LIMIT 1";
            
            $c = new SforcePartnerClient();
            $c->createConnection(sf_endpoint);
            $l=$c->login(sf_username, sf_password) ; 
            $r = new QueryResult($c->query($q));
            $c->logout() ; 
            
            if ($r->size > 0)
            {
              
               $str=json_decode(json_encode(new sObject($r->records[0])),true)['fields']['Last_Contact__c'] ;
               return time()-strtotime($str);
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->faultString ; 
            return null ;
        }
    }
?>
