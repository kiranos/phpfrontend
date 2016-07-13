<?php
include ("preflight.php"); //retrive logfile path

// show realtime output of siege
header('X-Accel-Buffering: no');
ob_end_clean();
ob_implicit_flush(true);
flush();
//init arrays
$ConnUsers = array();

$Delay = $_POST["delay"];
$IncAmount = $_POST["increment"];
$Runs = $_POST["runs"];
$NumFirstRun = $_POST["NumFirstRun"];
$Time = $_POST["time"];
//Default dont edit
$IncStart = 1;

if (is_numeric($Delay) && is_numeric($IncAmount) && is_numeric($Runs) && is_numeric($NumFirstRun) && is_numeric($Time)) {

//check so urls.txt exist
$urlfilepath = $_SERVER['DOCUMENT_ROOT'] . "/urls.txt";
if (!file_exists($urlfilepath)) {
    exit("urls.txt is not present");
}
$file = 'urls.txt';
$urls = file_get_contents($file);
echo "now running loadtest on following urls: $urls";

$IncAdd = $NumFirstRun;
array_push($ConnUsers, "$IncAdd");

	while ($IncStart <= $Runs) {
		echo "run $IncStart";
	        $IncAdd = $IncAmount + $IncAdd;
		array_push($ConnUsers, "$IncAdd");
		$exec = "siege -f urls.txt -d".$Delay." -c".$IncAdd." -t".$Time."M -i";
	        system($exec);
		flush();
	        $IncStart++;
	}

//        include ("log.php"); //generate graphs from logfile
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
