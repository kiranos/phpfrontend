#!/bin/bash

set -x

JobId="0"
IncAdd="0"
IncAmount="5"
Delay="3"
Runs="10"
NumFirstRun="5"
Time="1"
LogPath=""
IncStart="1"


# Include MySQL credentials
if [ -r config.ini ]; then
        . config.ini
fi

function connectmysql {
	mysql -s -N -p -h$host -u $user -p$pass $db -e "$1" 2>&1| grep -v "Warning: Using a password"
}

function runsiege {
	timestamp=`date +"%s"`
	echo "siege -f urls.txt -d$1 -c$2 -t$3M -i -l$4"
	sleep 5
}

MaxId=$(connectmysql "SELECT MAX(jobid) FROM jobs")

#echo $MaxId

IncAdd=$NumFirstRun
while [ $IncStart -le $Runs ]
do
echo "run $IncStart"
runsiege "$Delay" "$IncAdd" "$Time" "$LogPath"
IncAdd=$(bc <<< "scale=2;$IncAmount+$IncAdd")
connectmysql "UPDATE jobstatus SET LastUpdate = $timestamp , Status = 'running' , NumRunsCompleted = $IncStart WHERE jobid = $MaxId"
IncStart=$[$IncStart+1]
done

connectmysql "UPDATE jobstatus SET LastUpdate = $timestamp , Status = 'completed' WHERE jobid = $MaxId"

#    $insert = $pdo->prepare("INSERT INTO jobstatus(LastUpdate,Status,NumRunsCompleted,jobid) VALUES (?,?,?,?)");
#    -status=notstarted,running,completed|failed
