<?php
	require("lib.php");

	class Kill
	{
		public $name;
		public $tier;

		function __construct($newName, $newTier)
		{
			$this->name = $newName;
			$this->tier = $newTier;
		}
	}

	$killer = new Kill("dvaldez3", 0);
	$stack = array();
	$fp = fopen("tree.txt", 'w');
	array_push($stack, $killer);

	while (count($stack) > 0)
	{
		$killer = array_pop($stack);
		$res = mysql_query("SELECT * FROM `kills` WHERE `killer` = $killer->name");

		while ($r = mysql_fetch_array($res))
		{
			array_push($stack, new Kill($r['victim'], ($killer->tier + 1)));
		}

		$res = mysql_query("SELECT `fname`, `lname` FROM `users` WHERE `gt_name` = $killer->name;");
		$r = mysql_fetch_array($res);
		fprintf($fp, "%s%s %s\n", str_repeat("\t", $killer->tier), $r['fname'], $r['lname']);
	}

	fclose($fp);
?>