<?php

/* 
*
* Easy License
* Licence Checker
* (c)2013 Dennis Kupec
*
*/

error_reporting(-1);

// ===================================================
// Configuration
// ===================================================

// MySQL Settings
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "easy-license");

// Allow Queries
define("ALLOW", TRUE); // Change to false to deny any queries

// Custom Error Codes/Messages
define("er100", "Key queries are disabled at this time. Please try again later."); // queries not allowed
define("er110", "MySQL Error. Check configuration."); // MySQL error
define("er120", "This owner ID does not exist."); // Owner ID does not exist
define("er130", "The key provided is invalid."); // Invalid product key
define("er140", "The key provided is valid."); // Product key is valid
define("er150", "Define a valid owner ID. (GET 'a')"); // Owner ID GET not recieved
define("er160", "Define a product key. (GET 'b')"); // Product key GET not recieved
define("er170", "MySQL error. Check configuration."); // MySQL Error.
define("er180", "Owner-ID does not exist."); // Owner does not exist

define("er999", "Product key is valid.");
define("er998", "Product key has expired or is invalid.");

// ===================================================
// End Configuration
// ===================================================

// Global time variable
$time = time();

// You can disable queries for maintenance or w/e
if(ALLOW == FALSE) die(er100);

// declare GET data as variables
$ownerid = $_GET['a'];
$productkey = $_GET['b'];

// Check if the GET data is valid
if(!isset($ownerid) or empty($ownerid)) die(er150);
if(!isset($productkey) or empty($productkey)) die(er160);

// Initialize MySQL
try {  
	$dbc = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);  
	$dbc->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
}  
catch(PDOException $e) {    
	file_put_contents('sql_errorfile.txt', $e->getMessage(), FILE_APPEND); //make sure to delete this file after fixing problems
	die(er170);
} 

// Start querying databases
// Check for owner existence

$sta = $dbc->prepare("SELECT * FROM `owner-list` WHERE owner-id=:owner");
$sta->bindParam(":owner", $ownerid);
$sta->execute();

$stb = $dbc->prepare("SELECT * FROM `product-keys` WHERE owner-id=:owner AND expire-date>:time");
$stb->bindParam(":owner", $ownerid);
$stb->bindParam(":time", $time);
$stb->execute();

if($sta->rowCount() == 0) {
	$dbc = null;
	die(er180);
}

// Grab the key from the database, hash with salt
$row1 = $sta->fetch();
$row2 = $stb->fetch();

$key = sha1(sha1($row1['priv-key-a']).sha1($row2['key']).sha1($row1['priv-key-b']).sha1($row1['priv-key-c']));

if($key !== sha1($productkey)) {
	$dbc = null;
	die(er998);
} else {
	$dbc = null;
	die(er999);
}

$dbc = null;

?>