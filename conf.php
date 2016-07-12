<?php
$file = $_SERVER['HOME'] . "/.siege/siege.conf";

if (!file_exists($file)) {
    $file = $_SERVER['HOME'] . "/.siegerc";
}

if (!file_exists($file)) {
    //echo "no siege config file is present";
    exit("no siege config file is present");
}

$searchfor = 'logfile';
$contents = array();

// the following line prevents the browser from parsing this as HTML.
header('Content-Type: text/plain');

// get the file contents, assuming the file to be readable (and exist)
$rawcontents = file_get_contents($file);
// escape special characters in the query
$pattern = preg_quote($searchfor, '/');
// finalise the regular expression, matching the whole line
$pattern = "/^$pattern.*\$/m";

// remove lines which start with #
$array = explode("\n",$rawcontents);
foreach($array as $arr) {
	if ( !preg_match("/^#.*\$/m",$arr))
	array_push($contents, "$arr");
}
// search, and store all matching occurences in $matches
foreach($contents as $arr) {
	if(preg_match($pattern, $arr)){
	$logfile = "$arr";
	}
}

if (empty($logfile)) {
//	echo "please add logfile path in config file $file";
      exit("please add logfile path in config file $file");
}

$array = explode(" ",$logfile);
$logfilepath = $array[2];

//echo $logfilepath;

if (!file_exists($logfilepath)) {
//    echo "cant find $logfilepath, please use full path in configfile (no environment variables)";
    exit("cant find $logfilepath, please use full path in $file (no environment variables) and that logging = true");
}
?>


