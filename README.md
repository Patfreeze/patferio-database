Database lib for mysqli 
=======================

Patferio Database Library wrap the most commun function of mysqli.
Just install the package, add the config and it is ready to use!



Requirements
============

* PHP >= 7.2


Installation
============

composer require patferio/database
Add the service provider and facade in your config/app.php



Usage
=====

```PHP
/*
 A simple example
*/

// A simple way to use the namespace
use patferio\database\database as PatDatabase;

// Define Host User and Password
define('HOST_DB', 'LOCALHOST');
define('USER_DB', 'ROOT');
define('PASS_DB', 'YOUR_PASSWORD');

// The name of the database
$sDatabaseName = 'MyDatabase';

// Instance of PatDatabase
$objPatDatabase = new PatDatabase(HOST_DB, USER_DB, PASS_DB, $sDatabaseName);

$dtNow = Date('Y-m-d');
echo $objPatDatabase->renderSelect("
    SELECT 
        id,
        date,
        nofac 
    FROM 
        invoice 
    WHERE 
        invoice.date > '{$dtNow}'
");

/* example output
Database: MyDatabase
id      date        nofac
2099    2021-01-02  20210102183714
2100    2021-01-02  20210102183829
2104    2021-01-29  20210103102326
2105    2021-01-06  20210109182157
2106    2021-01-06  20210111173050
2107    2021-01-22  20210122194245

SELECT 
    * 
FROM 
    invoice 
WHERE 
    invoice.date > '2021-01-01'
*/

```

Credits
=======

* **Patrick Frenette** alias **_Patferio_ or _Patfreeze_**