<?php

include ("../../../db.php");

/**
 * Server-side file.
 * This file is an infinitive loop. Seriously.
 * It gets the file data.txt's last-changed timestamp, checks if this is larger than the timestamp of the
 * AJAX-submitted timestamp (time of last ajax request), and if so, it sends back a JSON with the data from
 * data.txt (and a timestamp). If not, it waits for one seconds and then start the next while step.
 *
 * Note: This returns a JSON, containing the content of data.txt and the timestamp of the last data.txt change.
 * This timestamp is used by the client's JavaScript for the next request, so THIS server-side script here only
 * serves new content after the last file change. Sounds weird, but try it out, you'll get into it really fast!
 */

// set php runtime to unlimited
set_time_limit(0);

// where does the data come from ? In real world this would be a SQL query or something
//$data_source_file = 'data.txt';

//get status for while loop
$res = $pdo->query("select LastUpdate,STATUS,NumRunsCompleted from jobstatus where jobid = 35");
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['STATUS'];
}
$res = null;

// main loop
while ($status == "running" || $status == "notstarted") {

    // if ajax request has send a timestamp, then $last_ajax_call = timestamp, else $last_ajax_call = null
    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

    // PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
    clearstatcache();
    // get timestamp of when file has been changed the last time
    $res = $pdo->query("select LastUpdate,STATUS,NumRunsCompleted from jobstatus where jobid = 35");
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $LastUpdate = $row['LastUpdate'];
            $status = $row['STATUS'];
            $NumRunsCompleted = $row['NumRunsCompleted'];
    }
    $res = null;
    $last_change_in_db_row = $LastUpdate;

    // if no timestamp delivered via ajax or data.txt has been changed SINCE last ajax timestamp
    if ($last_ajax_call == null || $last_change_in_db_row > $last_ajax_call) {


        $res = $pdo->query("select LastUpdate,STATUS,NumRunsCompleted from jobstatus where jobid = 35");
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $data = "status is " . $row['STATUS'] . " and its run number:" . $row['NumRunsCompleted'];
	$res = null;

        $result = array(
            'data_from_file' => $data,
            'timestamp' => $row['LastUpdate']
        );

        // encode to JSON, render the result (for AJAX)
        $json = json_encode($result);
        echo $json;

        // leave this loop step
        break;

    } else {
        // wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
        sleep( 1 );
        continue;
    }
}

        //when the loop is done disply a static value
        $res = $pdo->query("select LastUpdate,STATUS,NumRunsCompleted from jobstatus where jobid = 35");
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $data = "status is " . $row['STATUS'] . " and its run number:" . $row['NumRunsCompleted'];
        $res = null;

        $result = array(
            'data_from_file' => $data,
            'timestamp' => $row['LastUpdate']
        );

        // encode to JSON, render the result (for AJAX)
        $json = json_encode($result);
        echo $json;

?>
