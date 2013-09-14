<?php
    require("../scripts/lib.php");

    $faction = verify($gt_name);

    if($faction != 'human' and $faction !='zombie' and $faction != 'admin'){
        header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
    }

    $quizzed = taken_quiz($gt_name);
    if ($quizzed == False)
    {
        header("Location: http://hvz.gatech.edu/faction/inactive.php");
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Humans vs Zombies - Georgia Tech</title>
    <link type="text/css" rel="stylesheet" href="/css/base.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function post(){
            var newChat = encodeURI(document.getElementById("chatarea").value);
            var audience = document.getElementById("audience").value;
            var request = new XMLHttpRequest();
            var uri = "twit.php?faction="+audience+"&comment="+newChat;
            request.open("GET", uri, false);
            request.send(null);
            document.getElementById("chatarea").value = "";
            sendChatRefresh(audience.toUpperCase(), 0);
        }

        function convert_case(string) {
            return (string.substr(0,1).toUpperCase() + string.substr(1).toLowerCase());
        }

        function loadChat(faction) {
            var factionchat = faction + "chat";

            if (faction != "all") {
                document.getElementById("allchat").className = "hide";
            }

            if (faction != "admin") {
                document.getElementById("adminchat").className = "hide";
            }

            if (faction != "human") {
                document.getElementById("humanchat").className = "hide";
            }

            if (faction != "zombie") {
                document.getElementById("zombiechat").className = "hide";
            }

            document.getElementById(factionchat).className = "show";
            document.getElementById("audience").value = faction;
            document.getElementById("full_current_chat").innerHTML = convert_case(faction) + " Chat";
            toChatBottom();
        }

        function toChatBottom(){
            document.getElementById("full_multichat").scrollTop = document.getElementById("full_multichat").scrollHeight;
        }

        var chatReq = new XMLHttpRequest();
        var newChatFation;

        function sendChatRefresh(faction, count) {
            newChatFaction = faction.toLowerCase() + "chat";
            var url = "chat_update.php?faction="+faction;
            chatReq = new XMLHttpRequest();
            chatReq.open("GET", url, true);
            chatReq.onreadystatechange = refreshChat;
            chatReq.send(null);
            //alert("Sent To: " + url);
        }

        function refreshChat() {
            if (chatReq.readyState == 4)
            {
                document.getElementById(newChatFaction).innerHTML = chatReq.responseText;
                toChatBottom();
            }
        }

        function updateRequest(count) {
            var audience = document.getElementById("audience").value;
            sendChatRefresh(audience, count);
        }

        function setHeight() {
            $("#full_meat").height($(window).height() - 20);
            $("#full_chatbar").offset({ top: $(window).height() - $("#full_chatbar").height() - 10, left: 10});
            $("#full_multichat").height($("#full_meat").height() - $("#full_chatbar").height() - $("#full_nav").height() - 20);
            $("#chatarea").height($("#subbtn").height());
            toChatBottom();
        }

        $(document).ready(function() {
            setHeight();
            toChatBottom()
        });

        $(window).resize( function() {
                setHeight();
                toChatBottom();
            }
        );
    </script>

</head>
<body>
<div id="full_meat">
    <div id="full_nav">
        <input type="button" value="All" id="all" class="selectors" onclick="loadChat('all')">
        <?php
        if ($faction == "admin")
        {
            echo <<<BUTTON1
                    <input type="button" value="Admin" id="admin" class="flat_selectors" onclick="loadChat('admin')")>
BUTTON1;
        }
        if ($faction == "admin" || $faction == "human" || isOZ($gt_name))
            echo <<<BUTTON2
                    <input type="button" value="Human" id="human" class="flat_selectors" onclick="loadChat('human')">
BUTTON2;

        if ($faction == "admin" || $faction == "zombie")
            echo <<<BUTTON3
                    <input type="button" value="Zombie" id="zombie" class="flat_selectors" onclick="loadChat('zombie')">
BUTTON3;

        ?>
        <h3 id="full_current_chat">All Chat</h3>
    </div>
    <div id="full_multichat">
        <table id="allchat" class="show">
            <?php beta_print_chat(FALSE, "ALL",""); ?>
        </table>
        <?php
        echo <<<HUMAN
                <table id="humanchat" class="hide">
HUMAN;

        if ($faction == "admin" || $faction == "human" || isOZ($gt_name))
        {
            beta_print_chat(false, "HUMAN","");
        }
        echo "</table>";

        echo <<<ZOMBIE
                <table id="zombiechat" class="hide">
ZOMBIE;

        if ($faction == "admin" || $faction == "zombie")
        {
            beta_print_chat(false, "ZOMBIE","");
        }
        echo "</table>";

        echo <<<ADMIN
                <table id="adminchat" class="hide">
ADMIN;
        if ($faction == "admin")
        {
            beta_print_chat(false, "ADMIN","");
        }
        echo "</table>";
        ?>
    </div>
    <div id="full_chatbar">
        <input type="hidden" name="faction" id="audience" value="all"/>
        <textarea name="comment" onkeyup="if (event.keyCode == 13) document.getElementById('subbtn').click()" class="full_twitbox" id="chatarea"></textarea>
        <input type="button" id="subbtn" onclick="post()" value="Post" style="display:inline; vertical-align: top;"/>
        <input type="button" onclick="updateRequest(0)" value="Update" style="display:inline; vertical-align: top;"/>
    </div>
    <div class="footer"></div>
</div>
</body>
</html>