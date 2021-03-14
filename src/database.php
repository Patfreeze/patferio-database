<?php declare(strict_types=1);

/**
 * Program class Database.php
 * This class wrap some functionality of mysqli
 * Made by Patrick Frenette
 */

namespace patferio\database;

class Database {

	private $objDB;
	private $sHost;
	private $sUser;
	private $sPassword;
	private $sDatabase = '';

	/**
	 * The constructor of the class
	 * @param String $sHost
	 * @param String $sUser
	 * @param String $sPassword
	 * @param String $sDatabase
	 * @return patferio\database
	 */
	public function __construct(String $sHost, String  $sUser, String $sPassword, String $sDatabase)
	{

		$this->objDB = new \mysqli($sHost, $sUser, $sPassword, $sDatabase);
		//---> Verification of the connection
		if (\mysqli_connect_errno()) {
			$sMessage = \mysqli_connect_error();
			throw new \Exception("Connection error : {$sMessage}\n",\mysqli_connect_errno());
		}
		$this->sHost = $sHost;
		$this->sUser = $sUser;
		$this->sPassword = $sPassword;
		$this->sDatabase = $sDatabase;

		// If nothing to return, return the object it self
		return $this;
	}

	/**
	 * Return the current database name
	 * @return String
	 */
	public function getDatabaseName() {
		return $this->sDatabase;
	}

	/**
	 * Fonction to escape unwanted char
	 * @param String $sString
	 */
	public function real_escape_string(String $sString) {
		return $this->objDB->real_escape_string($sString);
	}
	
	/**
	 * Change the database
	 * @param String $sDatabase
	 * @return patferio\database
	 */
	public function changeDatabase(String $sDatabase) {
		$this->objDB = new \mysqli($this->sHost, $this->sUser, $this->sPassword, $sDatabase);
		//---> Verification of the connection
		if (\mysqli_connect_errno()) {
			$sMessage = \mysqli_connect_error();
			throw new \Exception("Connection error : {$sMessage}\n",\mysqli_connect_errno());
		}
		$this->sDatabase = $sDatabase;

		// If nothing to return, return the object it self
		return $this;
	}
	
	/**
	 * Simple select tu retreive data
	 * @param String $sSQL
	 * @return Object result;
	 */
	public function select(String $sSQL) {
		 $objDB = $this->objDB;
		 
		// Verification if query is OK
		if(!($result = $objDB->query($sSQL))) {
			throw new \Exception("Unable to do the request : ".print_r($objDB->error_list, true));
		}

		return $result;
	}
	
	/**
	 * Return the database and the query generated at the end
	 * @param String $sSQL
	 * @return String data and query;
	 */
	public function renderSelect(String $sSQL) {
		$objDB = $this->objDB;

		// Verification if query is OK
		if(!($result = $objDB->query($sSQL))) {
			throw new \Exception("Unable to do the request : ".print_r($objDB->error_list, true));
		}
	
		$sReturn = '<b>Database: '.$this->getDatabaseName().'</b><table style="border:0; border-collapse:collapse; width:100%;">'."\n";
		$bPassOne = false;

		while($row = $result->fetch_assoc()) {
			$sReturn .= '<tr>'."\n";
			if(!$bPassOne) {
				foreach($row as $key => $val) {
						$sReturn .= '<th style="text-align:center; padding:4px; margin:0; border:1px solid red; background-color:rgb(75,75,75); color:white;">'.$key.'</th>'."\n";
				}
				$sReturn .= '</tr><tr>'."\n";
				$bPassOne = true;
			}
			foreach($row as $key => $val) {
					$sReturn .= '<td style="padding:4px; border:1px solid black;">'.$val.'</td>'."\n";
			}
			$sReturn .= '</tr>'."\n";
		}
		$sReturn .= '</table><br><div style="text-align:left;"><pre>'.$sSQL.'</pre></div>'."\n";
		
		if($result->num_rows == 0) {
			$sReturn = "NO ROW RETURNED<br>\n".$sReturn;
		}
		
		return $sReturn;
	}
	
	/**
	 * Same as real_escape_string
	 * @param String $sString
	 * @return String
	 */
	public function quoteSmart(String $sString) {
		return $this->real_escape_string($sString);
	}
	
	/**
	 * If the query return only one value like a Count
	 * @param String $sSQL
	 * @param Boolean $bReturnNULL
	 * @return Mixed
	 */
	public function getOne(String $sSQL, bool $bReturnNULL = false) {
		$objDB = $this->objDB;
		 $result = $objDB->query($sSQL)
			   OR die("Impossible d'exécuter la requête :".print_r($objDB->error_list, true));

		$sReturn = NULL;
		
		if($result->num_rows > 1) {
			if(!$bReturnNULL) {
				throw new \exception ('MORE THAN JUST ONE RESULT');
				die();
			}
		}
		else if($result->num_rows == 0) {
			return 0;
		}
		else {
			$row = $result->fetch_assoc();
			foreach($row as $val) {
				$sReturn = $val;
			}
		}
		
		if(!$bReturnNULL) {
			if(is_null($sReturn)) {
				$sReturn = 0;
			}
		}
		
		return $sReturn;
	}
	
	/**
	 * This is used to save data in database
	 * @param String $sSQL
	 * @return Mixed
	 */
	public function saveDB(String $sSQL) {
		
		$objDB = $this->objDB;
		try {
			return $objDB->multi_query($sSQL);
		}
		catch(\Exception $e) {
			$objDB->rollback();
			throw new \exception($objDB->error.' '.$e);
			die();
		}
	}
	
	/**
	 * Encode all data from array
	 * @param Mixed $a_array array|string
	 * @return Array
	 */
	public function convertUTF8(Mixed $a_array) {
		
		$a_sReturn = array();
	
		if(is_null($a_array)) {
			return false;
		}
		else {
			foreach($a_array as $sKey => $sValue) {
				$a_sReturn[$sKey] = utf8_encode($sValue);
			}
		}
		
		return $a_sReturn;
	}
	
}


?>