<?php

include ("../../../db.php");

$res = $pdo->query("select LastUpdate,STATUS,NumRunsCompleted from jobstatus where jobid = 35");
$row = $res->fetch(PDO::FETCH_ASSOC);
$data = "status is " . $row['STATUS'] . " and its run number:" . $row['NumRunsCompleted'];

        $result = array(
            'data_from_file' => $data,
            'timestamp' => $row['LastUpdate']
        );

        // encode to JSON, render the result (for AJAX)
        $json = json_encode($result);
        echo $json;
?>
