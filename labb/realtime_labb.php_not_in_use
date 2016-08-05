<?php

//header('X-Accel-Buffering: no');
//ob_end_clean();
ob_implicit_flush(true);
flush();

$statusOK=0;

$cmd = "siege -v -c2 -t2M -d10 eriksson.cn";
//$cmd = "ping 127.0.0.1";
$descriptorspec = array(
   0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
   2 => array("pipe", "w")    // stderr is a pipe that the child will write to
);
$process = proc_open($cmd, $descriptorspec, $pipes );
echo "<pre>";
flush();
//if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {

	$output = $s;
	$output = rtrim($output);
	echo "$output";
	flush();
    }
//}
?>
