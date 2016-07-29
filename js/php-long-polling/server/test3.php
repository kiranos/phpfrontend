<?php
// where does the data come from ? In real world this would be a SQL query or something
$data_source_file = 'data.txt';

    // if ajax request has send a timestamp, then $last_ajax_call = timestamp, else $last_ajax_call = null
    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

    // PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
    clearstatcache();
    // get timestamp of when file has been changed the last time
    $last_change_in_data_file = filemtime($data_source_file);


echo "$last_change_in_data_file <br>" ;

echo time();

$timestamp = time();

//CREATE TABLE jobstatus (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, jobid INT NOT NULL, TIMESTAMP INT NOT NULL, STATUS VARCHAR(48) NOT NULL, FOREIGN KEY (jobid) REFERENCES jobs(jobid)) ;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=siege', 'root', 'beFoh1Daegieca4');

    $insert = $pdo->prepare("INSERT INTO jobstatus(jobid,TIMESTAMP,STATUS) VALUES (?,?,?)");
    $insert->bindValue(1, "1", PDO::PARAM_INT);
    $insert->bindValue(2, "$timestamp", PDO::PARAM_INT);
    $insert->bindValue(3, "INCOMPLETE", PDO::PARAM_STR);
    $insert->execute();
    $SqlErrorCode = $insert->errorCode();
        if($SqlErrorCode != 00000) {
                $SqlErrorInfo = $insert->errorInfo();
                $insert = null;
                exit("Error: $SqlErrorInfo[2]");
        }

?>
