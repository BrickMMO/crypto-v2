<?php

/**
 * This API call returns a list of local nodes. This file is called each minute
 * by each other node to ensure the nodes.json file is synchronized.
 */

 // Include config and function files
include('includes/config.php');
include('includes/functions.php');

// Apply JSON headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

// Feth curret list of local nodes
$nodes = nodes_fetch();

// Output local list of nodes using JSON 
echo json_encode($nodes);
