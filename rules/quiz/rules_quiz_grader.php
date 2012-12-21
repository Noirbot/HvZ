<?php
	require("../../scripts/lib.php");
	
	if( count($_POST) == 0 ){
    	header( "Location: http://hvz.gatech.edu/faction/" );
		die();
	}

    /*if (!quiz_open($current_game) && $gt_name != "pshuman3")
        die("Sorry, signups have closed for the current game");*/

	$questions = $db->query("SELECT * FROM `rules_quiz_questions`");
	$_SESSION["wrong"] = array();
	$_SESSION['rules_error'] = false;
	
	while($r =$questions->fetch_assoc()){
			$qid = 'q'.$r['id'];
			
			$correct_aid = $r['answer_id'];

			$submitted_aid = isset($_POST[$qid]) ? $_POST[$qid] : "No option selected";
			
			if($correct_aid != $submitted_aid){
				$_SESSION['rules_error']=true;
				$_SESSION["wrong"][] = $r['id'];
			}
				
	}
	if ($_SESSION['rules_error']==false)
		header("Location: http://hvz.gatech.edu/rules/quiz/waiver.php");
	else
		header( "Location: http://hvz.gatech.edu/rules/quiz/rules_quiz.php" );
?>