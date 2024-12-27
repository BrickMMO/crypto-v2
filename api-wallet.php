<?php

/**
 * This API call will create a new wallet encryption key. These keys are not 
 * stored anywhere. Wallet keys must be saved on the local device or wallet
 * funds will be lost.
 */

 // Include config and function files
include('includes/config.php');
include('includes/functions.php');

// Apply JSON headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

echo '<h1>GENERATE WALLET</h1>';
