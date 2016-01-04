# Gearman #

## Starting a worker ##
Start gearman workers with the following command:

```
$ nohup php jakob-worker.php &
```

## Installing Gearman on Ubuntu 10.04 ##
First you need to include the PPA for the latest version of Gearman.

```
$ sudo apt-get install python-software-properties
```

```
$ sudo add-apt-repository ppa:gearman-developers/ppa
```
As of december 2011, the above repository have not been maintained since May 2011.
```
$ sudo apt-get update
```

```
$ apt-get install gearman gearman-job-server gearman-tools libgearman4 libgearman-dev
```

Now you can start the Gearman job server
```
$ gearmand -d
```

```
# check gearmand running 
ps auxw | grep [g]earmand

# check germand listening for jobs on tcp port 4730
lsof -i tcp:4730
```

## Installing Gearman PECL extension ##
```
sudo apt-get install php-pear
```
```
sudo apt-get install php5-dev
```
```
sudo pecl install gearman
```
Does not work Need libgearman >= 0.21

```
You should add "extension=gearman.so" to php.ini
```
## Install libgearman > 0.21 ##
You need Boost headers version >= 1.39.0
```
sudo apt-get install libboost-dev
```
```
sudo apt-get install libboost-program-options1.40-dev
```
```
udo apt-get install uuid-dev
```
Get latest gearmand
```
wget http://launchpad.net/gearmand/trunk/0.26/+download/gearmand-0.26.tar.gz
```
```
tar xzf gearmand-0.26.tar.gz
cd gearmand-0.26
./configure
make
sudo make install
sudo ldconfig
```


## References ##
  * http://gearman.org/index.php?id=getting_started
  * http://www.modernfidelity.co.uk/tech/installing-configuring-and-running-gearman-php-ubuntu
  * http://toys.lerdorf.com/archives/51-Playing-with-Gearman.html
  * https://launchpad.net/~gearman-developers/+archive/ppa
  * http://sudhirvn.blogspot.com/2009/08/installing-gearman-on-ubuntu.html
  * http://pecl.php.net/package/gearman
  * http://www.mkfoster.com/2009/01/04/how-to-install-a-php-pecl-extensionmodule-on-ubuntu/