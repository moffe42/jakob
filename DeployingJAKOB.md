Deploying JAKOB in a WAYF-like setup requires you to:
  * Deploy JAKOB
  * Deploy an Authentication Processing filter on your SimpleSAMLphp (SSP) to connect to JAKOB

**NOTE** that the prerequisites list applies to the current WAYF-like setup and is non-exhaustive and will be extended as needed.

# Global prerequisites #
  * MySQL database (accessible from both the SSP server and JAKOB server), can be located anywhere.


---


# Installing SSP server #

  * All requirements for a SimpleSAMLphp server (see http://simplesamlphp.org/docs/1.10/simplesamlphp-install)
  * PDO and the appropriate driver (mysql)

## Installing JAKOB filter on SimpleSMALphp ##
You need to check out the SimpleSAMLphp authentication processing filter in the modules directory of your SSP installation
```
$ svn export http://jakob.googlecode.com/svn/trunk/ssp-module/jakob <SSP-DIR>/modules/jakob
```
Then copy the config template file to the config dir and edit to fit your needs
```
$ cp <SSP-DIR>/modules/jakob/config-templates/module_jakob.php <SSP-DIR>/config/
```
Config file  (modify for your setup)
```
$config = array(
    'dsn'      => 'mysql:host=localhost;dbname=jakob_db',
    'username' => 'jakobuser',
    'password' => 'jakobpass',
    'table'    => 'jakob__configuration',
    'salt'     => 'pezo340fkvd3afnywz3ab2fuwf5enj8h',

    'joburl'   => 'http://JAKOB-URL/job/',
);
```
Next you should add the filter to your configuration. It can be placed in any of the places AuthProc filters are configured. Below is an example configuration:
```
'authproc.idp' => array(
    10 => array(
        'class' => 'jakob:jakob',    
    ),
),
```


---


# Installing JAKOB #
## Prerequisites ##
  * Ubuntu Server 10.04 LTS (plain) or newer
  * Apcahe/2.2 (latest stable on Ubuntu)
  * PHP version 5.3.2 or higher (latest stable on Ubuntu)
  * libgearman > 0.21 (for latest Gearman PECL extension)
  * Gearman Job Server
  * Gearman PECL extension
  * Memcache
  * Memcache PHP extension

## Installing the JAKOB server ##
**NOTE** that this guide applies to the current WAYF setup. Your setup may vary.

This guide is not an exhaustive and you might have to install additional packages. Please report any missing dependencies as an issue [here](http://code.google.com/p/jakob/issues/list).

  1. Install all required packages
```
apt-get install openssh-server mysql-server mysql-client apache2 libapache2-mod-php5 php5-mysql memcached php5-memcache php-pear php5-dev g++ libboost-dev libboost-program-options-dev libevent-dev uuid-dev libboost-thread-dev gearman gearman-job-server gearman-tools
```
  1. The Gearman PECL extention requires Gearman > 0.21 (which is not part of the Ubuntu 10.04 repositories). See this [page](MiscNotes.md) for more detailed information.
```
wget -q http://launchpad.net/gearmand/trunk/0.27/+download/gearmand-0.27.tar.gz
tar xzf gearmand-0.27.tar.gz
cd gearmand-0.27
./configure
make
make install
ldconfig
```
  1. Install the PECL Gearman extension for PHP"
```
pecl install gearman
```
  1. Enable the extension in both apache and cli
```
cd /etc/php5/apache2/
echo "extension=gearman.so" >> php.ini
cd /etc/php5/cli/
echo "extension=gearman.so" >> php.ini
/etc/init.d/apache2 restart
```
  1. Install JAKOB
```
mkdir -p <JAKOB-DIR>
svn export http://jakob.googlecode.com/svn/trunk/ <JAKOB-DIR>
chmod 777 <JAKOB-DIR>/log
```
  1. Adapt the config file to fit your needs. Found in `/<JAKOB-DIR>/config/config.php`
  1. Create the database and set up the tables. SQL found in `/<JAKOB-DIR>/docs/jakob.sql`
  1. Setup Apache
```
a2enmod ssl
a2enmod rewrite
/etc/init.d/apache2 restart
"Create site file for JAKOB"
a2ensite jakob.wayf.dk
a2dissite 000-default
/etc/init.d/apache2 reload
```
  1. Get metadata for Admin interface
```
wget -q -O /<JAKOB-DIR>/config/metadata/wayf-idp.xml "<IDP-METADATA-URL>"
wget -q -O /<JAKOB-DIR>/config/metadata/wayf-sp.xml "<SP-METADATA-URL>"
```
  1. Restarting Gearman job server
```
killall gearmand
gearmand -d
```
  1. Start JAKOB workers
```
./<JAKOB-DIR>/bin/jakob-worker.php
```
  1. Set correct permissions on log files
```
chmod 777 <JAKOB-DIR>/log/jakob.log
```