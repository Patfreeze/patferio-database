<?php
/**
 * This is only a file example
 */

// Load vendor
require_once("./vendor/autoload.php");

use patferio\database\Database as PatDatabase;

// Add your own credential in this file
require_once("./kilfrmens.fhg");

$objDB = new PatDatabase(HOST_DB, USER_DB, PASS_DB, DATABASE_NAME);

// Output some HTML
echo '
<!html>
<html>
<head></head>
<body style="background-color:#dddddd;">
';

// This will render a table with all data from table invoice where date > 2021-01-01
echo $objDB->renderSelect("
    SELECT 
        *
    FROM 
    invoice 
    WHERE 
        invoice.date > '2021-01-01' 
    LIMIT 0,6
");

// Output end of HTML
echo '</body></html>';
?>