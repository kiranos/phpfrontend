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
include("db.php");
/* pChart library inclusions */
include ("pChart/class/pData.class.php");
include ("pChart/class/pDraw.class.php");
include ("pChart/class/pImage.class.php");

// show realtime output of siege
//header('X-Accel-Buffering: no');
//ob_end_clean();
//ob_implicit_flush(true);
//flush();
//init arrays
$ConnUsers = array();
$ResponseTime = array();
$TransactionRate = array();
$Errors = array();

$Name = $_POST["name"];
$Delay = $_POST["delay"];
$IncAmount = $_POST["increment"];
$Runs = $_POST["runs"];
$NumFirstRun = $_POST["NumFirstRun"];
$Time = $_POST["time"];
//Default dont edit
$IncStart = 1;
//get unix_timestamp
$timestamp = time();

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

//echo "LOGPATH: $LogPath <br><br>";

//Create jobstatus initial  data. !!OBS not correct mysql tables etc..!
//CREATE TABLE jobstatus (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, jobid INT NOT NULL, LastUpdate INT NOT NULL, STATUS VARCHAR(48) NOT NULL, NumRunsCompleted INT NOT NULL, FOREIGN KEY (jobid) REFERENCES jobs(jobid)) ;
//-status=notstarted,running,completed|failed

    $insert = $pdo->prepare("INSERT INTO jobstatus(LastUpdate,Status,NumRunsCompleted,jobid) VALUES (?,?,?,?)");
    $insert->bindValue(1, "$timestamp", PDO::PARAM_STR);
    $insert->bindValue(2, "notstarted", PDO::PARAM_STR);
    $insert->bindValue(3, "0", PDO::PARAM_INT);
    $insert->bindValue(4, "$JobId", PDO::PARAM_INT);
    $insert->execute();
    $SqlErrorCode = $insert->errorCode();
        if($SqlErrorCode != 00000) {
                $SqlErrorInfo = $insert->errorInfo();
                $insert = null;
                exit("Error: $SqlErrorInfo[2]");
        }
    $insert = null;


// Calculate ConnUsers
$IncAdd = $NumFirstRun;
//array_push($ConnUsers, "$IncAdd");

	while ($IncStart <= $Runs) {
                array_push($ConnUsers, "$IncAdd");
                $IncAdd = $IncAmount + $IncAdd;
//		$exec = "siege -f urls.txt -d".$Delay." -c".$IncAdd." -t".$Time."M -i -l".$LogPath; OBS not here anymore but in the exec to siege_wrapper.sh script
	        $IncStart++;
	}


//print_r(array_values($ConnUsers));

//IMPORT PARSELOG_MYSQL FROM  HERE!!!!
// Parse Logfile (import variable from conf.php)
$txt_file    = file_get_contents($logfilepath);
$rows        = explode("\n", $txt_file);
// remove first row, and remove "," also remove empty elements.
array_shift($rows);
$rows = (str_replace(",","",$rows));
$rows = array_filter($rows);

//check so runs = rows in logfile otherwise fail
$numrows = count($rows);
//DEBUG:
echo "NUMBER OF ROWS: $numrows <br> NUMBER OF RUNS: $Runs <br>";
if (count($rows) != $Runs) {

    exit("check so runs = rows in logfile otherwise fail");
}

//DEBUG:
print_r(array_values($rows));

$iterate = 0;
foreach ($rows as $data) {
// explode doesnt work as its 1 or more whitepsaces in a row
        $row_data = preg_split('/\s+/', $data);
//add statistics to DB
    $insert = $pdo->prepare("INSERT INTO statistics(JobId,ConnUsers,ResponseTime,TransactionRate,Errors) VALUES (?,?,?,?,?)");
    $insert->bindValue(1, "$JobId", PDO::PARAM_INT);
    $insert->bindValue(2, "$ConnUsers[$iterate]", PDO::PARAM_INT);
    $insert->bindValue(3, "$row_data[5]", PDO::PARAM_INT);
    $insert->bindValue(4, "$row_data[6]", PDO::PARAM_INT);
    $insert->bindValue(5, "$row_data[10]", PDO::PARAM_INT);
    $insert->execute();
    $insert = null;
//DEBUG:
//echo "iterate: $iterate <br>";
//echo $row_data[5] . " " . $row_data[6] . " " . $row_data[10] . " " . $ConnUsers[$iterate] ;
        $iterate++;
        }

//include("RespGraph.php");
//include("ErrorGraph.php");
//include("TransactionGraph.php");
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

