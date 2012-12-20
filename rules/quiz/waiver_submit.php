<?php
    require("../../scripts/lib.php");

    if( count($_POST) == 0 ){
        if($local){
            header( "Location: http://localhost/faction/" );
            die();}
        else{
            header( "Location: http://hvz.gatech.edu/faction/" );
            die();}
    }

    /*if (!quiz_open($current_game) && $gt_name != "pshuman3")
        die("Sorry, signups have closed for the Spring 2012 game");*/

    $name = $_POST["name"];
    $gtname = $_POST["gtname"];
    $gtid = $_POST["gtid"];

    if (strlen($gtid) < 9 || !is_numeric($gtid))
    {
        $_SESSION["gtiderror"] = true;
        header("Location: http://hvz.gatech.edu/rules/quiz/waiver.php");
    }

    $player_id = md5(md5(md5($gt_name." ".time())));

    $player_code = "";

    for($i=1; $i<=10; $i++){
        $player_code .= $player_id{32-$i};
    }


    $query = "UPDATE `users` SET `faction`='human', `rules_quiz`='1', `player_code`='$player_code', `gtid`='$gtid' WHERE `gt_name`='$gt_name'";
    $db->query($query) or die("RULES UPDATE query fail");

    if($local){
        header( "Location: http://localhost/profile/" );
        die();}
    else{
        header( "Location: http://hvz.gatech.edu/profile/" );
        die();}
?>