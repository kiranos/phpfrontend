<html>
<head>
<title>CSS Example</title>
    <link rel="stylesheet" href="style.css">
</title>
</head>
<body>

<?php
include ("menu.html"); // include the menu
include ("preflight.php"); //retrive logfile path

$pdo = new PDO('mysql:host=127.0.0.1;dbname=siege', '', '');

// show realtime output of siege
header('X-Accel-Buffering: no');
ob_end_clean();
ob_implicit_flush(true);
flush();
//init arrays
$ConnUsers = array();


$Name = $_POST["name"];
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

//add to DB SKALL LAGGA PA NAMN, TIMESTAMP, STATUS, SAMT HUR DET GICK
// mysql> CREATE TABLE jobs (jobid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Delay INT, IncAmount INT, Runs INT, NumFirstRun INT, Time INT) ;

    $insert = $pdo->prepare("INSERT INTO jobs(Name,Delay,IncAmount,Runs,NumFirstRun,Time) VALUES (?,?,?,?,?,?)");
    $insert->bindValue(1, "$Name", PDO::PARAM_STR);
    $insert->bindValue(2, "$Delay", PDO::PARAM_INT);
    $insert->bindValue(3, "$IncAmount", PDO::PARAM_INT);
    $insert->bindValue(4, "$Runs", PDO::PARAM_INT);
    $insert->bindValue(5, "$NumFirstRun", PDO::PARAM_INT);
    $insert->bindValue(6, "$Time", PDO::PARAM_INT);
    $insert->execute();
    $SqlErrorCode = $insert->errorCode();
	if($SqlErrorCode != 00000) {
		$SqlErrorInfo = $insert->errorInfo();
		$insert = null;
		exit("Error: $SqlErrorInfo[2]");
	}
    $insert = null;

//$file = 'urls.txt';
//$urls = file_get_contents($file);
//echo "now running loadtest on following urls: $urls";

//Get jobid for current job (SELECT MAX(jobid) FROM jobs;)
$JobIdArray = $pdo->query("SELECT MAX(jobid) FROM jobs");
$JobIdArray = $JobIdArray->fetch(PDO::FETCH_ASSOC);
$JobId = implode($JobIdArray);
//echo "JOBID: $JobId <br><br>";

//generate custom log path

$LogPath = $_SERVER['DOCUMENT_ROOT'] . "/logs/jobid-$JobId.log";

echo "LOGPATH: $LogPath <br><br>";

// START RUN
$IncAdd = $NumFirstRun;
array_push($ConnUsers, "$IncAdd");

	while ($IncStart <= $Runs) {
		echo "run $IncStart";
	        $IncAdd = $IncAmount + $IncAdd;
		array_push($ConnUsers, "$IncAdd");
		$exec = "siege -f urls.txt -d".$Delay." -c".$IncAdd." -t".$Time."M -i -l".$LogPath;
//	        system($exec);
		flush();
	        $IncStart++;
	}

        include ("parselog_mysql.php"); //generate graphs from logfile
}

?>


<form method="post" action="exec.php">

Name of the benchmark:<br>
<input type="text" name="name" value="Unique name" /><br>
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

</body>
</html>

