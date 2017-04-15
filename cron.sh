#!/bin/bash

for i in {1..12}
do
	echo "Start Cron jobs"

	php index.php Cron jobs

	sleep 5
done