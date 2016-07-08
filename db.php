<?php
    
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
?>
