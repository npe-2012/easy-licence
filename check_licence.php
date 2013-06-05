<?php

/* 
*
* Easy License
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

// ===================================================
// End Configuration
// ===================================================

// You can disable queries for maintenance or w/e
if(ALLOW == FALSE) die(er100);

// declare GET data as variables
$ownerid = $_GET['a'];
$productkey = $_GET['b'];

//Check if the GET data is valid
if(!isset($ownerid) or empty($ownerid)) die(er150);
if(!isset($productkey) or empty($productkey)) die(er160);













?>