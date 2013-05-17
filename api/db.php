<?php

/* Holds singletons of various things in the program,
 * such as database handlers and the GT name
 */
class InstanceHolder
{
   private static $_instances;

   public function setInstance($name, &$var)
   {
      self::$_instances[$name] = $var;
   }

   public function getInstance($name)
   {
      if (isset(self::$_instances[$name]))
         return self::$_instances[$name];
      return null; // or throw an exception if you like
   }
}  

session_start();
//Get the GT name of the logged in player
$gt_name = $_ENV["REMOTE_USER"] ? $_ENV["REMOTE_USER"] : "";

//Main database access information
$host = "web-db1.gatech.edu";
$dbname = "mobile_app_dev";
$user = "dev";
$pass = "whiteheads";
$DBH = null;

//Achievement database access information
$ach_dbname = "achievements";
$ach_user = "achieve";
$ach_pass = "legopibb";
$ach_DBH = null;

//Try to make new database handlers
try {  
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
   $ach_DBH = new PDO("mysql:host=$host;dbname=$ach_dbname", $ach_user, $ach_pass);
}  
catch(PDOException $e) {  
	echo $e->getMessage();
}

//Set instances of the things we have created
InstanceHolder::setInstance('DB', $DBH);
InstanceHolder::setInstance('AchievementDB', $ach_DBH);
InstanceHolder::setInstance('gt_name', $gt_name);
