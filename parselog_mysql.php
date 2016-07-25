<?php

 /* pChart library inclusions */
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");

$pdo = new PDO('mysql:host=127.0.0.1;dbname=siege', '', '');

///var/log/siege.log

//init arrays
//$ConnUsers = array();
$ResponseTime = array();
$TransactionRate = array();
$Errors = array();
//Variables
// $Runs = 10;
// $IncAmount = 10;

//Default (dont edit)
// $IncStart = 1;

//MYSQL CREATE TABLE statistics (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, jobid INT, ConnUsers INT, ResponseTime DECIMAL, TransactionRate DECIMAL, Errors DECIMAL);

//$select = $pdo->query("SELECT * FROM jobs");
//while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
//
//    echo "jobid: " . $row['jobid'] . " firstrun: " . $row['NumFirstRun'] . "<br />";
//
//}

//Get jobid for current job (SELECT MAX(jobid) FROM jobs;)
$JobIdArray = $pdo->query("SELECT MAX(jobid) FROM jobs");
$JobIdArray = $JobIdArray->fetch(PDO::FETCH_ASSOC);
$JobId = implode($JobIdArray);


//Create Array for Y-Axis concurrent Users.
//while ($IncStart <= $Runs) {
//
//	$IncAdd = $IncAmount * $IncStart;
//	array_push($ConnUsers, "$IncAdd");
//	$IncStart++;
//
//}
//print_r(array_values($ConnUsers));
//echo "$logfilepath";

// Parse Logfile (import variable from conf.php)
$txt_file    = file_get_contents($logfilepath);
$rows        = explode("\n", $txt_file);
// remove first row, and remove "," also remove empty elements.
array_shift($rows);
$rows = (str_replace(",","",$rows));
$rows = array_filter($rows);

//check so runs = rows in logfile otherwise fail
$numrows = count($rows);
echo "NUMBER OF ROWS: $numrows <br> NUMBER OF RUNS: $Runs <br>";
if (count($rows) != $Runs) {

    exit("check so runs = rows in logfile otherwise fail");
}

print_r(array_values($rows));

// print array
//print_r(array_values($rows));

$iterate = 0;
//echo "iterate: $iterate";
foreach ($rows as $data) {
//$row_data = explode(' ', $data);
// explode doesnt work as its 1 or more whitepsaces in a row
	$row_data = preg_split('/\s+/', $data);
//	array_push($ResponseTime, "$row_data[5]");
//        array_push($TransactionRate, "$row_data[6]");
//        array_push($Errors, "$row_data[10]");
// ADD TO DB

//add to DB SKALL LAGGA PA NAMN, TIMESTAMP, STATUS, SAMT HUR DET GICK
// mysql> CREATE TABLE jobs (jobid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Delay INT, IncAmount INT, Runs INT, NumFirstRun INT, Time INT) ;

    $insert = $pdo->prepare("INSERT INTO statistics(JobId,ConnUsers,ResponseTime,TransactionRate,Errors) VALUES (?,?,?,?,?)");
    $insert->bindValue(1, "$JobId", PDO::PARAM_INT);
    $insert->bindValue(2, "$ConnUsers[$iterate]", PDO::PARAM_INT);
    $insert->bindValue(3, "$row_data[5]", PDO::PARAM_INT);
    $insert->bindValue(4, "$row_data[6]", PDO::PARAM_INT);
    $insert->bindValue(5, "$row_data[10]", PDO::PARAM_INT);
    $insert->execute();

echo "\nPDO::errorCode(): ", $insert->errorCode();
    $insert = null;

//TEST
//echo "iterate: $iterate <br>";
//echo $row_data[5] . " " . $row_data[6] . " " . $row_data[10] . " " . $ConnUsers[$iterate] ;
	$iterate++;
	}

//print_r(array_values($ResponseTime));
//print_r(array_values($TransferRate));
//print_r(array_values($Errors));


//include("RespGraph.php");
//include("ErrorGraph.php");
//include("TransactionGraph.php");
?>
