<?php require("../scripts/lib.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Humans vs Zombies - Georgia Tech</title>
<!-- link type="text/css" rel="stylesheet" href="custom.css" -->
<link type="text/css" rel="stylesheet" href="../css/base.css">
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
    Humans vs Zombies</div>
    </div>
    <div id="utilityBar"></div>
    <div id="breadcrumb"><form action='../logout.php'><input type='submit' value='Logout' style='float:right; margin-right:250px;'/></form></div>
</div>
<div id="meat">

    <div id="support"><a name="navigation"></a>
        <?php printSideBar(); ?>
    </div>

   <div id="content" class="">

        <h2>Rules</h2>

        <div id="rules">
            <h3>Overview</h3>
            <p>Humans versus Zombies is a week-long campus-wide game of tag. All players sign up and receive a yellow bandanna. All players start the first day as a human, except for an Original Zombie (OZ), who is marked as a human but starts tagging people. Humans can defend themselves from zombies by stunning them with approved stunning implements. Once a player has been tagged, they turn into a zombie and continue the game by tagging more humans. Missions occur throughout the week.</p>
            <h3>Don't Be a Jerk</h3>
            <ul>
                <li>The number one rule of the game is to not be a jerk</li>
                <li>A player's face must be visible and identifiable at all times</li>
                <li>Anything that is against school rules or the law is against our rules</li>
                <li>No items which resemble or obscure the official bandanna in any way may be worn in such a way as to mislead other players.</li>
            </ul>
            <h3> Admins</h3>
            <ul>
                <li>...wear black and white checkered bandannas (typically on the arm or head)</li>
                <li>...run all aspects of the game</li>
                <li>...have final say on all decisions of the game</li>
                <li>...are not players</li>
                <li>...may not be argued with during the game (send us an email)</li>
                <li>...will address all your concerns through <a href="mailto:hvzgatech@gmail.com">the email</a></li>
                <li>...may enact punishment upon rule breakers. For example, admins may:
                    <ul>
                        <li>stun zombies</li>
                        <li>zombify humans</li>
                        <li>make players stand in the corner</li>
                        <li>send players home for the day</li>
                        <li>remove players from the game</li>
                        <li>permanently remove players from all games</li>
                        <li>refer players to proper authorities</li>
                    </ul>
                </li>
            </ul>
            <h3>Humans</h3>
            <ul>
                <li>...must wear their yellow bandanna on the upper arm or upper leg.  The bandanna must be visible from all angles.</li>
                <li>...defend against zombies using <strong>approved</strong> stunning implements only</li>
                <li>...must carry their Player ID at all times</li>
                <li>...must attend (and check in with an admin) at <strong>three</strong> or more missions, including at least one mission on Thursday or Friday, to play in the finale as human</li>
                <li>...must be on campus for at least 8 hours of game play a day (if this might cause a problem, talk to an admin about your particular situation)</li>
                <li>...can request the stun timer of a zombie, and the zombie must comply honestly</li>
                <li>...can, while within safe zones, request "earshot": meaning zombies must give humans approximately 10-15 feet of space to provide privacy to discuss and plan</li>
            </ul>
            <h3>Zombies</h3>
            <ul>
                <li>...wear yellow bandanna on forehead while in play and around their neck when stunned. Bandannas must be visible from all angles and must not be obstructed by hair, hats, scarves, etc.</li>				<li>...tag humans while in play using a firm touch</li>
                <li>...can be stunned by approved stunning implements, after which the zombie is out of play for the duration of the current stun timer (default is 10 minutes)</li>
                <li>...must turn in a "feed" every 48 hours or "starve" (removed from play).  A tag feeds the tagging zombie and two other zombies.</li>
                <li>...cannot use shields of any kind</li>
                <li>...while stunned, may tell other zombies any information they knew <strong>before</strong> they were stunned.  Stunned zombies are otherwise treated as non-players</li>
                <li>The Original Zombie (OZ) wears their bandanna as a human for the first day, but tags humans normally.  The OZ can be stunned, but does not make any visual indication of being stunned</li>
            </ul>
            <h3>Stunning</h3>
            <ul>
                <li>A human stuns a zombie by hitting the zombie with an approved stunning implement</li>
                <li>A stunning implement must leave its launching device (hand, barrel, etc) and follow an uncontrolled parabolic arc, making contact with an in-play zombie before hitting the ground to count as a valid stun (<strong>No Melee</strong>)</li>				<li>A zombie cannot have a shield: contacting a zombie's backpack (or purse, or satchel, etc.) with an approved stunning implement is a legitimate stun.</li>
                <li>If a zombie removes their bandanna for more than a few seconds for any reason, they are stunned for the full length of the current stun timer.  Zombies may self-stun.</li>
                <li>When a zombie is stunned, they are considered out of play and must move their bandanna to their neck (the "stunned zombie" position). While stunned players may only share information they knew before being stunned. They may not interact with the game in <strong>any other way</strong></li>
            </ul>
            <h3>Approved Stunning Implements</h3>
            <ul>
                <li>No stunning implement may inflict any pain</li>
                <li>Approved stunning implements consist of standard or jumbo-sized fresh marshmallows, fresh mini-marshmallows (cannot be thrown, must be launched from an admin-approved launcher), clean sock balls, and soft foam items.</li>
                <li>Players may use bought or constructed launchers to launch the approved stunning implements.  All launchers must be approved by admins.  No launcher may have a pistol grip, trigger, or resemble any weapon.  No NERF or generic NERF off-brand blasters are permitted.</li>
                <li>Only one stunning implement may be thrown (per hand) or launched (per barrel) at a time.  If multiple implements from the same barrel or hand are thrown or launched at a time, none of the implements count.</li>
                <li>Any stunning implement or launching device not explicitly described above must be approved by the admins before use during the game</li>
                <li>To get a stunning implement approved, <a href="mailto:hvzgatech@gmail.com">email us</a> to set up a time to meet</li>
            </ul>
            <h3>Tagging</h3>
            <ul>
                <li>An unstunned zombie whose feet have been in a play zone for at least 3 seconds can tag a human by touching the human with their hand on an appropriate part of the human's body.  Tagging a human's personal effects (backpack, laptop, purse, etc.) does not count as a tag.</li>
                <li>The human may request the daily code word (distributed via email) of the zombie to verify they are a valid player</li>
                <li>Once a human has been tagged, they must give their Player ID to the zombie who tagged them and move their bandanna to the "stunned zombie" position. They then wait 1 hour as an incubation period before turning into a playing zombie.</li>
                <li>Once a zombie has made a tag, they must turn in the player code to the website within 3 hours.</li>
                <li>All tags must be reported before midnight the day of the tag. (Tags at 10:30pm must be entered before midnight. Not 1:30am.</li>
                <li>Failure to report tags in a timely manner will result in penalties</li>
            </ul>
            <h3>Player ID Card</h3>
            <ul>
                <li>All humans are required to create and carry a player ID card</li>
                <li>The Player ID card consists of a <strong>piece of paper</strong> with the player's full name and Player ID code on it</li>
                <li>The Player ID code can be found on the players profile page</li>
            </ul>
            <h3>Missions</h3>
            <ul>
                <li>Check the website for mission details. There are usually two missions per day.  One in the middle of day during class, one during the evening</li>
                <li>Missions drive the plot of the game, and successful completion of a mission usually results in a directly beneficial reward to the winning side</li>
                <li>During missions, zombies are not allowed inside of mission-related buildings unless explicitly allowed by admins</li>
                <li>Player meetings, which are held during sign-up week, may be counted as a maximum of one mission credit. See <a href="http://www.hvz.gatech.edu">homepage</a> for times and locations.</li>
            </ul>
            <h3 style="display:inline;">Play Zones and Times</h3>
            <a href="../images/playzone_map.png?phpMyAdmin=j%2Cut7v5h8%2CBIQWu%2CTPZMLMN1lAc" style="display:inline">MAP</a>
            <ul>
                <li>HvZ may only be played on the Georgia Tech campus from 10th street to North Ave and Tech Parkway/Northside Dr to I-75/85 on the Tech side of all fences</li>
                <li>Game play is from 7am to 11pm.</li>
                <li>A human must leave or enter campus using the most direct path (i.e. to go to Rocky Mountain you must cross 10th St at Hemphill Ave.) and may not leave	campus if they are being chased by a zombie.  Stun the zombie, then leave campus</li>
            </ul>
            <h3> No Play Zones </h3>
            <ul>
                <li>No game play may occur within a no play zone</li>
                <li>Players may only be in no play zones for non-game related reasons</li>
                <li>Zombies may not "camp" within 10 feet of an entrance to a no play zone</li>
                <li>No play zones include
                    <ul>
                        <li>All fraternity, sorority, and religious organization buildings and properties</li>
                        <li>All sanctioned athletic events and areas</li>
                        <ul>
                            <li>Athletes do not wear their bandannas during the event</li>
                            <li>Athletes are <strong>not</strong> safe during transit to or from the event</li>
                        </ul>
                        <li>All required academic events</li>
                        <li>Classes in session</li>
                        <li>Fire alarms or other mandatory evacuation areas</li>
                        <li>All athletic fields and facilities</li>
                        <li>Any construction area</li>
                        <li>Library</li>
                        <li>CRC</li>
                        <li>Dramatech</li>
                        <li>SAC Fields</li>
                        <li>Klaus Parking Deck</li>
                        <li>CULC Roof</li>
                        <li>Stamps Health Center</li>
                    </ul>
                </li>
            </ul>
            <h3>Safe Zones</h3>
            <ul>
                <li>All buildings are considered safe zones unless listed as no play zones</li>
                <li>Humans within a safe zone can stun zombies in play areas</li>
                <li>Humans within a safe zone can be tagged if the zombie's feet are in play outside of the safe zone</li>
                <li>Zombies cannot be stunned within a safe zone</li>
            </ul>
            <h3>Free Play Zones</h3>
            <ul>
                <li>Any area not covered by the no play or safe zones</li>
            </ul>
            <h3>Vehicles</h3>
            <ul>
                <li>Players may not use any wheeled transportation on campus (e.g. bikes, buses, cars, etc.)</li>
                <li>If a player requires transportation to or from campus, the player must remove their bandanna while in and around their vehicle, but must put the bandanna on at the earliest opportunity</li>
                <li>Zombies may use the buses, however, buses are considered no play zones.  They cannot be used for game related purposes.</li>
            </ul>
            <h3>Be a Good Player</h3>
            <ul>
                <li>Non-players are strictly prohibited from interacting with the game in any way.
                    <ul>
                        <li>No meat shields</li>
                        <li>No information sharing</li>
                        <li>No lookouts</li>
                        <li>No food delivery</li>
                        <li>etc</li>
                    </ul>
                </li>
                <li>Players must not solicit any of the above from non-players</li>
                <li>Players may take no action to endanger or physically harm another person</li>
                <ul>
                    <li>No tackles, slides, or trips</li>
                    <li>No punches, pushing, or biting</li>
                    <li>Tags are ok</li>
                </ul>
                <li>In the event of an injury, game play will be temporarily halted in the area around the injury until the issue is resolved. If an injured person needs medical attention, call 911 before reporting it to the admins.</li>
            </ul>
        </div>

      <div class="footer"><p></p></div>

   </div>

</div>
</body>
</html>