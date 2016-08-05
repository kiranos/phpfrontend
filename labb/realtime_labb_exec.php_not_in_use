<?php

//header('X-Accel-Buffering: no');
//ob_end_clean();
ob_implicit_flush(true);
flush();

$statusOK=0;

$cmd = "siege -v -c2 -t30S -d10 eriksson.cn";
//$cmd = "ping 127.0.0.1";
exec($cmd, $do);
echo "array content:";

foreach($do as $result) {
    echo $result;
    flush();
}


flush();
//  }
//}
?>
