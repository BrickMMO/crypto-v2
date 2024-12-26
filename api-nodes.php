<?php

include('includes/config.php');
include('includes/functions.php');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

$nodes = fetch_nodes();
echo json_encode($nodes);
