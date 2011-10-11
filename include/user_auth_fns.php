<?php 
require_once dirname(__FILE__).'/../'."connect_db.php"; 

function islogin()
{
 global $_SESSION;
 global $_COOKIE;
 if(empty($_SESSION['uid']))
 {
   $userinfo = getUserInfo($_COOKIE['email'],$_COOKIE['password']);
   if(!empty($userinfo['id']))
   {
     $_SESSION['uid']=intval($userinfo['id']);
     $_SESSION['username']=$userinfo['username'];
	 return 1;
   }
   return 0;
 }
 else
 {
   return 1;
 }
}

function getUserInfo($email, $password)
{
global $DB;
global $db_prefix;
global $_COOKIE;
$email=addslashes(htmlspecialchars(trim($email)));
$passwd=trim($password);
$result = $DB->fetch_one_array("SELECT id,username FROM ".$db_prefix."user WHERE email='".$email."' AND passwd='".$passwd."'");
return $result;
}

/*
function register($email, $password, $username) {
// register new person with db
// return true or error message

  // connect to db
  //$conn = db_connect();

  // check if email is unique
  $result = $DB->query("select * from ".$db_prefix."user where email='".$email."'");
  if (!$result) {
    throw new Exception('Could not execute query');
  }

  if ($result->num_rows>0) {
    throw new Exception('That email is taken - go back and choose another one.');
  }

  // if ok, put in db
  $result = $DB->query("insert into ".$db_prefix."user values
                         ('".$email."',  sha1('".$password."'), '".$username."')");
  if (!$result) {
    throw new Exception('Could not register you in database - please try again later.');
  }

  return true;
}

function login($email, $password) {
// check email and password with db
// if yes, return true
// else throw exception

  // connect to db
  $conn = db_connect();

  // check if email is unique
  $result = $conn->query("select * from user
                         where email='".$email."'
                         and passwd = sha1('".$password."')");
  if (!$result) {
     throw new Exception('Could not log you in.');
  }

  if ($result->num_rows>0) {
     return true;
  } else {
     throw new Exception('Could not log you in.');
  }
}


function change_password($email, $old_password, $new_password) {
// change password for email/old_password to new_password
// return true or false

  // if the old password is right
  // change their password to new_password and return true
  // else throw an exception
  login($email, $old_password);
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where email = '".$email."'");
  if (!$result) {
    throw new Exception('Password could not be changed.');
  } else {
    return true;  // changed successfully
  }
}

function get_random_word($min_length, $max_length) {
// grab a random word from dictionary between the two lengths
// and return it

   // generate a random word
  $word = '';
  // remember to change this path to suit your system
  $dictionary = '/usr/dict/words';  // the ispell dictionary
  $fp = @fopen($dictionary, 'r');
  if(!$fp) {
    return false;
  }
  $size = filesize($dictionary);

  // go to a random location in dictionary
  $rand_location = rand(0, $size);
  fseek($fp, $rand_location);

  // get the next whole word of the right length in the file
  while ((strlen($word) < $min_length) || (strlen($word)>$max_length) || (strstr($word, "'"))) {
     if (feof($fp)) {
        fseek($fp, 0);        // if at end, go to start
     }
     $word = fgets($fp, 80);  // skip first word as it could be partial
     $word = fgets($fp, 80);  // the potential password
  }
  $word = trim($word); // trim the trailing \n from fgets
  return $word;
}

function reset_password($email) {
// set password for email to a random value
// return the new password or false on failure
  // get a random dictionary word b/w 6 and 13 chars in length
  $new_password = get_random_word(6, 13);

  if($new_password == false) {
    throw new Exception('Could not generate new password.');
  }

  // add a number  between 0 and 999 to it
  // to make it a slightly better password
  $rand_number = rand(0, 999);
  $new_password .= $rand_number;

  // set user's password to this in database or return false
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where email = '".$email."'");
  if (!$result) {
    throw new Exception('Could not change password.');  // not changed
  } else {
    return $new_password;  // changed successfully
  }
}


?>*/
