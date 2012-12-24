<?php
require("../../scripts/lib.php");

$faction = verify($_SESSION["gtname"]);


if($faction != 'admin'){
    header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
}

$toparse = $_POST["gtids"];
$late = $_POST["isLate"];


if (!strcasecmp($late, "true"))
    $query = $db->prepare("UPDATE users SET late_mission = late_mission + 1 WHERE gtid=?");
else
    $query = $db->prepare("UPDATE users SET early_mission = early_mission + 1 WHERE gtid=?");

if ($query)
{
    foreach($toparse as $line){
        if (is_null($line) || $line == "" || !is_numeric($line))
            continue;

        $query->bind_param("s", $line);
        $query->execute();
    }

    $query->close();
}
