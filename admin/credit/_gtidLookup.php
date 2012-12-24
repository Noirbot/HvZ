<?php
require("../../scripts/lib.php");

$faction = verify($gt_name);

$table = "<tr><td><strong>GTID</strong></td><td><strong>Name</strong></td><td><strong>Early</strong></td><td><strong>Late</strong></td><td><strong>Other</strong></td></tr>";

if($faction != 'admin'){
    header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
}

$toparse = $_POST["gtids"];


if($query = $db->prepare("SELECT gtid, fname, lname, early_mission, late_mission, mission_count FROM users WHERE gtid=?"))
{
    foreach($toparse as $line){
        if (is_null($line) || $line == "")
            continue;

        if (!is_numeric($line))
        {
            $table .= "<tr><td>$line</td><td>Is not valid</td><td>-</td><td>-</td><td>-</td></tr>";
            continue;
        }

        $query->bind_param("s", $line);
        $query->execute();
        $query->bind_result($gtid, $fname, $lname, $early, $late, $count);

        if (!$query->fetch())
        {
            $table .= "<tr><td>$line</td><td>No Results</td><td>-</td><td>-</td><td>-</td></tr>";
            continue;
        }

        if ($early == 1)
            $early = "✓";
        else
            $early = "-";

        if ($late == 1)
            $late = "✓";
        else
            $late = "-";

        $table .= "<tr><td>$gtid</td><td>$fname $lname</td><td>$early</td><td>$late</td><td>$count</td></tr>";
    }

    $query->close();
}

echo $table;