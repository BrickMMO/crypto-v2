<?php

/**
 * /usr/local/bin/php -q /home/ikatsfn3/alpha.loot.brickmmo.com/cron-fond-nodes.php
 * > /dev/null
 */

// Include config and function files
include('includes/config.php');
include('includes/functions.php');

// Apply JSON headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

// Remove inactive nodes
nodes_check_missing();

// Fetch local nodes
$nodes_local = nodes_fetch();
$nodes_remote = array();

// Define variables to store how many nodes are found, missing, or self
$nodes_found = 0;
$nodes_missing = 0;
$nodes_self = 0;

// Loop through each local node
foreach($nodes_local as $key => $node)
{

    // Do not initiaate API call for self node
    if($node['url'] == DOMAIN)
    {
        
        // Update local node details
        $nodes_local[$key]['responded_at'] = time();
        $nodes_local[$key]['attempts'] = 0;

        // Increment self stats
        $nodes_self ++;
        
    }
    else
    {

        // Define API URL
        $url = $node['url'].'/api/nodes?url='.urlencode(DOMAIN);

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
            $nodes_local[$key]['responded_at'] = time();
            $nodes_local[$key]['attempts'] = 0;

            // Increment found stats
            $nodes_found ++;

            // Merge local nosde list with remote list
            $nodes_remote = array_merge($nodes_remote, json_decode($response, true));

        }
        else
        {

            // Increemtn attemtps to remove inactive nodes in the future
            $nodes_local[$key]['attempts'] += 1;

            // Increment missing stats
            $nodes_missing ++;

        }

        // Close CURL
        curl_close($ch);

        // Slow script down to generate different time in node list
        sleep(1);

    }

    nodes_set($nodes_local);

}

// Check list of remote nodes for nodes that need to be added to the local list.
nodes_add($nodes_remote, $nodes_local);

// Place data in array for JSON output
$data = array(
    'message' => 'Node list found.', 
    'error' => false,
    'nodes_found' => $nodes_found,
    'nodes_missing' => $nodes_missing,
    'nodes_self' => $nodes_self,
);

// Output local list of nodes using JSON 
echo json_encode($data);
