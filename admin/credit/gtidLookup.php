<?php
require("../../scripts/lib.php");

$faction = verify($gt_name);

if($faction != 'admin'){
    header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Humans vs Zombies - Georgia Tech</title>
    <link type="text/css" rel="stylesheet" href="../../css/base.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function processGTIDs() {
            var input = $("#gtidIn").val().split("\n");

            $.ajax({
                type: "POST",
                url: "_gtidLookup.php",
                data: {gtids: input},
                cache: false,
                success: function(data)
                {
                    $("#results").html(data);
                }
            });
        }

        function addCredit(isLate)
        {

        }
    </script>
</head>
<body>
<div id="header">
    <div class="hiddenItem">
        <a href="#start" accesskey="2">Skip to content.</a>
        <a href="#navigation" accesskey="3">Skip to navigation.</a>
    </div>
    <div id="logoWrapper" class="clearfix">
        <h1 id="logo">
            <a href="http://www.gatech.edu" accesskey="1"> </a>
        </h1>
        <div id="siteLogo">
            Humans vs Zombies
        </div>
    </div>
    <div id="utilityBar"></div>
    <div id="breadcrumb">
        <form action='../../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form>
    </div>
</div>
<div id="meat">
    <div id="support"><a name="navigation"></a>
        <?php printSideBar(); ?>
    </div>
    <div id="deathbar">
        <?php print_deathbar(); ?>
    </div>
    <div id="content" class="">
        <div class='upload'>
            <h2>Paste GTIDs here.</h2>
            <div id="control">
                <textarea id="gtidIn"></textarea>
                <input type="button" value="Process" onclick="processGTIDs()" class="lookupButtons"/>
                <input type="button" value="Credit Early Mission" onclick="addCredit(false)" class="lookupButtons"/>
                <input type="button" value="Credit Late Mission" onclick="addCredit(true)" class="lookupButtons"/>
            </div>

            <table border='1' style="padding:0" id="results">
                <tr>
                    <td><strong>GTID</strong></td>
                    <td><strong>Name</strong></td>
                    <td><strong>Early</strong></td>
                    <td><strong>Late</strong></td>
                    <td><strong>Other</strong></td>
                </tr>
            </table>
            <p id="out"></p>
        </div>
    </div>
</div>
</body>