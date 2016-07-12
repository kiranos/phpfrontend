<?php

$Delay = $_POST["delay"];
$IncAmount = $_POST["increment"];
$Runs = $_POST["runs"];
$ConnUsers = $_POST["start"];
$Time = $_POST["time"];
//Default dont edit
$IncStart = 1;
$time = $_POST["time"];


if (is_numeric($Delay) && is_numeric($IncAmount) && is_numeric($Runs) && is_numeric($ConnUsers) && is_numeric($Time)) {

	while ($IncStart <= $Runs) {

	        $IncAdd = $IncAmount * $IncStart;
		$exec = "siege -d".$Delay." -c".$IncAdd." -t".$Time."M http://eriksson.cn";
		echo $exec;
	        system($exec);
	        $IncStart++;
	}


	include "(log.php)";

}

?>


<form method="post" action="exec4.php">

Time DELAY, random delay before each requst in seconds:<br>
<input type="text" name="delay" value="3" /><br>
Additional simulated users per run:<br>
<input type="text" name="increment" value="5" /><br>
number of times to run the test:<br>
<input type="text" name="runs" value="10" /><br>
Number of simulated users for first run:<br>
<input type="text" name="start" value="5" /><br>
Duration of each run (mins):<br>
<input type="text" name="time" value="1" /><br>
<input type="submit" value="run siege">
</form>
