#!/bin/bash

echo -e "Restarting all JAKOB workers\n"

echo -e "The following workers are killed\n"
pgrep -l -f connector-worker.php

echo -e "\nKilling all JAKOB workers\n"
pkill -f connector-worker.php

echo -e "Starting all JAKOB workers again\n"
./jakob-worker.php

echo -e "\nThe following workers are now running\n"
pgrep -l -f connector-worker.php
