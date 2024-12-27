<?php

include('includes/config.php');
include('includes/functions.php');

/*
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");
*/

$nodes = nodes_fetch();

$nodes_found = 0;
$nodes_missing = 0;

foreach($nodes as $key => $node)
{

    $url = $node['url'].'/api/nodes';

    if($node['url'] == DOMAIN)
    {
        continue;
    }

    $headers[] = 'Content-type: application/json';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200)
    {

        $nodes[$key]['responded_at'] = time();
        $nodes[$key]['attempts'] = 0;
        $nodes_found ++;

        $nodes = nodes_merge($nodes, json_decode($response, true));

    }
    else
    {

        $nodes[$key]['attempts'] += 1;
        $nodes_missing ++;

    }

    /*
    echo 'Code: '.$code.'<br>';
    echo 'Error: '.curl_error($ch).'<br>';
    echo '<hr>';
    */

    curl_close($ch);

}

nodes_set($nodes);

$data = array(
    'message' => 'Node list found.', 
    'error' => false,
    'nodes_found' => $nodes_found,
    'nodes_missing' => $nodes_missing,
);

echo json_encode($data);
