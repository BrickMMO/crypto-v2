<?php

/**
 * This API call initiates a transaction between two wallets.
 */

 // Include config and function files
include('includes/config.php');
include('includes/functions.php');

// Apply JSON headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

echo '<h1>GENERATE TRANSACTION</h1>';