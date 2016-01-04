# JAKOB Operations #
This document describes some of the standard operation items.

## Tools ##
JAKOB comes with some simple maintenance tools, all located in the `bin` directory located under `JAKOB`.

### connector-worker.php ###
Script for running a connector. The script will handle `SIGTERM`to the connector and shut down the conector nicely if possible. The script also writes a pid file in the directory supplied in the main config file under `pid.directory`. The pid file can be used with the [[Unix start-stop-daemon](http://www.unix.com/man-page/Linux/8/start-stop-daemon/)] for easy control over the connector.

The script should be called with a connector configuration file as its first and only argument. Below is an example on how to call the script.

Ex.
```
nohup php ./bin/connector-worker.php connector_cpr.php 1> ./log/nohup.out 2> ./log/nohup.error &
```

### jakob-workers.php ###
The script will start connectors for all configuration files located in the `./config/connectors/` directory. The script uses the connector-worker.php script for running the individual connectors.

### restart-workers.sh ###
This script will stop all running connectors and start connectors for all configuration files located in the `./config/connectors/` directory. The script uses the jakob-workers.php for starting the actual connectors.


---


## Connectors ##

### Find running connectors ###
Find all workers (conectors) running
```
$ pgrep -f connector-worker.php
```

### Stop all running connectors ###
Kill all running workers
```
$ pkill -f connector-worker.php
```
If one or more connector are not stopping use
```
$ pkill -9 -f connector-worker.php
```

### See the running workers ###
See running workers
```
$ nc localhost 4730
- status
- workers
```


---


## Gearman job Server ##

### Start Gearman Job Server ###
```
$ gearmand -d
```