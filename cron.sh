#!/bin/bash

dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

for i in {1..12}
do
        echo "Start Cron jobs"

        php "$dir/index.php" Cron jobs

        sleep 5
done
