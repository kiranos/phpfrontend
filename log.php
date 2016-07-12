<?php

 /* pChart library inclusions */
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");


///var/log/siege.log

//init arrays
$ConnUsers = array();
$ResponseTime = array();
$TransactionRate = array();
$Errors = array();
//Variables
$Runs = 10;
$IncAmount = 10;
//$IncStop = 10; (probably not needed)

//Default (dont edit)
$IncStart = 1;

//Create Array for Y-Axis concurrent Users.
while ($IncStart <= $Runs) {

	$IncAdd = $IncAmount * $IncStart;
	array_push($ConnUsers, "$IncAdd");
	$IncStart++;

}
//print_r(array_values($ConnUsers));


// Parse Logfile
$txt_file    = file_get_contents('/var/log/siege.log');
$rows        = explode("\n", $txt_file);
// remove first row, and remove "," also remove empty elements.
array_shift($rows);
$rows = (str_replace(",","",$rows));
$rows = array_filter($rows);

// print array
// print_r(array_values($rows));

foreach ($rows as $data) {
//$row_data = explode(' ', $data);
// explode doesnt work as its 1 or more whitepsaces in a row
$row_data = preg_split('/\s+/', $data);
	array_push($ResponseTime, "$row_data[5]");
        array_push($TransactionRate, "$row_data[6]");
        array_push($Errors, "$row_data[10]");
//	print_r(array_values($row_data));
	//$time = $row_data[1];
	//echo $time;
}

//print_r(array_values($ResponseTime));
//print_r(array_values($TransferRate));
//print_r(array_values($Errors));


include("RespGraph.php");
include("ErrorGraph.php");
include("TransactionGraph.php");
//foreach($rows as $row => $data)
//{
    //get row data
//    $row_data = explode('^', $data);

//    $info[$row]['id']           = $row_data[0];
//    $info[$row]['name']         = $row_data[1];
//    $info[$row]['description']  = $row_data[2];
//    $info[$row]['images']       = $row_data[3];

    //display data
//    echo 'Row ' . $row . ' ID: ' . $info[$row]['id'] . '<br />';
//    echo 'Row ' . $row . ' NAME: ' . $info[$row]['name'] . '<br />';
//    echo 'Row ' . $row . ' DESCRIPTION: ' . $info[$row]['description'] . '<br />';
//    echo 'Row ' . $row . ' IMAGES:<br />';

    //display images
//    $row_images = explode(',', $info[$row]['images']);

//    foreach($row_images as $row_image)
//    {
//        echo ' - ' . $row_image . '<br />';
//    }

//    echo '<br />';


//}
?>
<img src="images/ResponseGraph.png">
<img src="images/ErrorGraph.png">
<img src="images/TransactionGraph.png">
