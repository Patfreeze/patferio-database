<?php declare(strict_types=1);

require_once("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use patferio\database\Database as PatDatabase;

// You must put your own credentials
define('HOST_DB', 'localhost');
define('USER_DB', 'root');
define('PASS_DB', '');
define('DATABASE_NAME', '');
define('TABLE_TEST', '');


final class DatabaseTest extends TestCase
{

    public function testCanBeCreatedFromValidCredential(): void
    {
        // Testing
        $this->assertInstanceOf(
            PatDatabase::class,
            new PatDatabase(HOST_DB, USER_DB, PASS_DB, DATABASE_NAME)
        );
       
    }

    /**
     * This will return the object DB
     */
    private function getObjectDB() {
        return new PatDatabase(HOST_DB, USER_DB, PASS_DB, DATABASE_NAME);
    }

    /**
     * @depends testCanBeCreatedFromValidCredential
     */
    public function testCanUseSelect(): void
    {
        // Need this for other test
        $objDB = $this->getObjectDB();

        $sSQL = "SELECT * FROM ".TABLE_TEST." LIMIT 0, 10";

        // Testing object
        $this->assertIsObject(
            $objDB->select($sSQL)
        );

        //Testing if return string
        $this->assertIsString(
            $objDB->renderSelect($sSQL)
        );
    }

    /**
     * @depends testCanBeCreatedFromValidCredential
     */
    public function testCanUseGetDatabaseName(): void
    {
        // Need this for other test
        $objDB = $this->getObjectDB();

        // Testing
        $this->assertIsString(
            $objDB->getDatabaseName()
        );
    }

    /**
     * @depends testCanBeCreatedFromValidCredential
     */
    public function testCanUseGetOneNumeric(): void
    {
        // Need this for other test
        $objDB = $this->getObjectDB();

        // Testing
        $this->assertIsNumeric(
            $objDB->getOne("SELECT COUNT(*) FROM ".TABLE_TEST)
        );
    }

}

?>