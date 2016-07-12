<?php

//init arrays
$ConnUsers = array();

$Delay = $_POST["delay"];
$IncAmount = $_POST["increment"];
$Runs = $_POST["runs"];
$NumFirstRun = $_POST["NumFirstRun"];
$Time = $_POST["time"];
//Default dont edit
$IncStart = 1;

//REMOVE
//echo "$Runs <br>";
//echo "$IncStart <br>";
//echo "$IncAmount <br>";


if (is_numeric($Delay) && is_numeric($IncAmount) && is_numeric($Runs) && is_numeric($NumFirstRun) && is_numeric($Time)) {

$IncAdd = $NumFirstRun;
array_push($ConnUsers, "$IncAdd");

	while ($IncStart <= $Runs) {

	        $IncAdd = $IncAmount + $IncAdd;
		array_push($ConnUsers, "$IncAdd");
		$exec = "siege -d".$Delay." -c".$IncAdd." -t".$Time."M http://eriksson.cn";
	//        system($exec);
	//	echo "$exec <br>";
//		print_r(array_values($ConnUsers));
		//REMOVE
//		echo "$Runs <br>";
//		echo "$IncStart <br>";
//		echo "$IncAmount <br>";
	        $IncStart++;
	}

//	print_r(array_values($ConnUsers));
	include ("conf.php"); //retrive logfile path
        include ("log.php"); //generate graphs from logfile



}

?>


<form method="post" action="exec.php">

Time DELAY, random delay before each requst in seconds:<br>
<input type="text" name="delay" value="3" /><br>
Additional simulated users per run:<br>
<input type="text" name="increment" value="5" /><br>
number of times to run the test:<br>
<input type="text" name="runs" value="10" /><br>
Number of simulated users for first run:<br>
<input type="text" name="NumFirstRun" value="5" /><br>
Duration of each run (mins):<br>
<input type="text" name="time" value="1" /><br>
<input type="submit" value="run siege">
</form>
