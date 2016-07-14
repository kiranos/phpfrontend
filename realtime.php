
<?php

header('X-Accel-Buffering: no');
ob_end_clean();
ob_implicit_flush(true);
flush();

$statusOK=0;

//filter and remove element if it contains "=>"
function myFilter($string) {
  return strpos($string, '?') === false;
}


$cmd = "siege -v -c2 -t3M -d10 eriksson.cn";
//$cmd = "ping 127.0.0.1";
$descriptorspec = array(
   0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
   2 => array("pipe", "w")    // stderr is a pipe that the child will write to
);
flush();
$process = proc_open($cmd, $descriptorspec, $pipes );
echo "<pre>";
if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {

	$output = $s;
	$output = rtrim($output);
        $output = explode("\n", $output);
        foreach ($output as $data) {
		flush();
		//remove one array which only includes line break.
		$data = rtrim($data);
		$data = explode(" ", $data);
//                print_r(array_values($data));




//		echo $data[1];
//		echo $data . "<br>";

		if (strpos($data[1], '2') === 0) {
			$statusOK++;
			echo "found $statusOK amount of 20X";
		} else {
		    echo "its not starting with 2";
		}
		flush();
       }






        flush();
    }
}
echo "</pre>";
?>
