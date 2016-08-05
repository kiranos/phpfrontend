<?php
header('X-Accel-Buffering: no');
ob_end_clean();
ob_implicit_flush(true);
out("Starting");
flush();

function out($string) {
    echo $string . PHP_EOL;
}

$i = 0;
while ($i++ < 100) {
    out($i);
    flush();
    sleep(1);
}

out("Done");
?>
