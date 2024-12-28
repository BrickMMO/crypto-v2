<?php

/**
 * /usr/local/bin/php -q /home/ikatsfn3/alpha.loot.brickmmo.com/cron-fond-nodes.php
 * > /dev/null
 */

// Include config and function files
include('includes/config.php');
include('includes/functions.php');

// Apply JSON headers
/*
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");
*/

// Remove inactive nodes
nodes_check_missing();

// Fetch local nodes
$nodes = nodes_fetch();

// Define variables to store how many nodes are found, missing, or self
$nodes_found = 0;
$nodes_missing = 0;
$nodes_self = 0;

// Loop through each local node
foreach($nodes as $key => $node)
{

    // Define API URL
    $url = $node['url'].'/api/nodes?url='.urlencode(DOMAIN);

    // Do not initiaate API call for self node
    if($node['url'] == DOMAIN)
    {

        // Update local node details
        $nodes[$key]['responded_at'] = time();
        $nodes[$key]['attempts'] = 0;

        // Increment self stats
        $nodes_self ++;

        // Merge local nosde list with remote list
        $nodes = nodes_merge($nodes, $nodes_remote);

        continue;
        
    }

    // Add CURL headers
    $headers[] = 'Content-type: application/json';

    // Initiate API call using CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
    $response = curl_exec($ch);

    // Check if API call was completed scessfully
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // If sucessful
    if($code == 200)
    {
        
        // Update local node details
        $nodes[$key]['responded_at'] = time();
        $nodes[$key]['attempts'] = 0;

        // Increment found stats
        $nodes_found ++;

        // Merge local nosde list with remote list
        $nodes = nodes_merge($nodes, $nodes_remote);

    }
    else
    {

        // Increemtn attemtps to remove inactive nodes in the future
        $nodes[$key]['attempts'] += 1;

        // Increment missing stats
        $nodes_missing ++;

    }

    /*
    echo 'Code: '.$code.'<br>';
    echo 'Error: '.curl_error($ch).'<br>';
    echo '<hr>';
    */

    // Close CURL
    curl_close($ch);

}

// Place data in array for JSON output
$data = array(
    'message' => 'Node list found.', 
    'error' => false,
    'nodes_found' => $nodes_found,
    'nodes_missing' => $nodes_missing,
    'nodes_self' => $nodes_self,
    'nodes_new' => count($nodes) - count(nodes_fetch()),
);

// Save revised local lsit to nodes.json
nodes_set($nodes);

// Output local list of nodes using JSON 
echo json_encode($data);
