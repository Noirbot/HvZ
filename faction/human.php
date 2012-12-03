<?php 
	require("../scripts/lib.php"); 

	$faction = verify($gt_name);
	
	if($faction != 'human' and $faction != 'admin'){
		if($local)
			header( "Location: http://localhost/faction/$faction.php" );
		else
			header( "Location: http://hvz.gatech.edu/faction/$faction.php" );
	}
	
	$quizzed = taken_quiz($gt_name);
	if ($quizzed == False)
	{
		if($local)
			header("Location: http://localhost/faction/inactive.php");
		else
			header("Location: http://hvz.gatech.edu/faction/inactive.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<link type="text/css" rel="stylesheet" href="/css/base.css">

<script type="text/javascript">
    function toggle(e1, e2){
        e1.value = (e1.value == "Show") ? "Hide" : "Show";
        e2.className = (e2.className == "body hide") ? "body show" : "body hide";
    }

    function post(){
        var newChat = encodeURI(document.getElementById("chatarea").value);
        var audience = document.getElementById("audience").value;
        var request = new XMLHttpRequest();
        request.open("GET", "../chat/twit.php?faction="+audience+"&comment="+newChat, false);
        request.send(null);
        document.getElementById("chatarea").value = "";
        sendChatRefresh(audience.toUpperCase(), 10);
    }
    
    function chk_len(e){
        if(e.value.length>140){
            e.value = e.value.substr(0,140);
            alert("Messages are capped at 140 characters");
        }
    }

    function convert_case(string) {
        return (string.substr(0,1).toUpperCase() + string.substr(1).toLowerCase());
    }

    function loadChat(faction) {
        var factionchat = faction + "chat";

        if (faction != "all") {
            document.getElementById("allchat").className = "hide";
        }

        if (faction != "human") {
            document.getElementById("humanchat").className = "hide";
        }

        document.getElementById(factionchat).className = "show";
        document.getElementById("audience").value = faction;
        document.getElementById("current_chat").innerHTML = convert_case(faction) + " Chat";
        toChatBottom();
    }

    function toChatBottom(){
        document.getElementById("factionmultichat").scrollTop = document.getElementById("factionmultichat").scrollHeight;
    }

    var chatReq = new XMLHttpRequest();
    var newChatFation;

    function sendChatRefresh(faction, count) {
        newChatFaction = faction.toLowerCase() + "chat";
        var url = "../scripts/chat_update.php?faction="+faction+"&count="+(count <= 0 ? "" : "LIMIT " + count);
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

</script>

</head>
<body onload="toChatBottom()">
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
    Humans vs Zombies</div>
    </div>
    <div id="utilityBar"></div>
    <div id="breadcrumb"><form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>
<div id="meat">
   
    <div id="support"><a name="navigation"></a>
        <?php printSideBar(); ?>
    </div>
	<div id="deathbar">
		<?php print_deathbar(); ?>
	</div>
   <div id="content" class="">
     <div id="missions" class="roundedCorners">
       	 <h3 class="title">Missions
	         <input type="button" value="Hide"  onclick="toggle(this, document.getElementById('mblock'))">
         </h3>
       <div id='mblock' class="body show">
       		<?php print_missions("HUMAN"); ?>
       </div>
    </div>
      

	<div id="chats">
        <h2>Available Chat Rooms:</h2>
        <input type="button" value="All" id="all" class="selectors" onclick="loadChat('all')">
        <?php
            echo <<<BUTTON2
                <input type="button" value="Human" id="human" class="selectors" onclick="loadChat('human')">
BUTTON2;
        ?>
        <h3 id="current_chat">Human Chat</h3>
        <div id="factionmultichat">
            <table id="allchat" class="hide">
                <?php beta_print_chat(FALSE, "ALL","LIMIT 10"); ?>
            </table>
            <?php
                echo <<<HUMAN
                        <table id="humanchat" class="show">
HUMAN;
                beta_print_chat(FALSE, "HUMAN","LIMIT 10");
                echo "</table>";
            ?>
        </div>
        <input type="hidden" name="faction" id="audience" value="human"/>
        <textarea name="comment" onkeyup='chk_len(this)' class="twitbox" id="chatarea"></textarea>
        <input type="button" onclick="post()" value="Post" style="display:inline; vertical-align: top;"/>
        <input type="button" onclick="updateRequest(10)" value="Update" style="display:inline; vertical-align: top;"/>
    </div>  
	
    <p style="clear:both;">To see all chats ever, head on over to the dedicated <a href="/chat/">discussions</a> page</p>

      
   </div>

</div>
</body>
</html>