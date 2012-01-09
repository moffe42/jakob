#!/bin/bash

echo -e "Restarting all JAKOB workers\n"

echo -e "The following workers are killed\n"
echo -n -e "\E[31;40m"
pgrep -l -f connector-worker.php
echo -n -e "\x1b[39;49;00m"

echo -e "\nKilling all JAKOB workers\n"
pkill -f connector-worker.php

echo -e "Starting all JAKOB workers again\n"
./jakob-worker.php

echo -e "\nThe following workers are now running\n"
echo -n -e "\E[32;40m"
pgrep -l -f connector-worker.php
echo -n -e "\x1b[39;49;00m"
