<?php 
require("lib.php"); 

(isset($_POST['name'])) ? $sendname = $_POST['name'] : die("No sender name provided");
(isset($_POST['address'])) ? $sendaddr = $_POST['address'] : $sendaddr = "No address provided.";
(isset($_POST['subject'])) ? $subject = $_POST['subject'] : die("No subject provided");
(isset($_POST['message'])) ? $message = $_POST['message'] : die("No message provided");	

$to = "hvzgatech@gmail.com";
$from = "From: $sendaddr";
$subject = "[Site Form] [$sendname] $subject";

$headers = $from . "\r\n" .  'Reply-To: ' . $sendaddr . "\r\n" .
'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

header("Location: http://hvz.gatech.edu/");
?>

   
