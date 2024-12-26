<?php

function debug_pre($data)
{

    echo '<pre>';
    print_r($data);
    echo '</pre>';

}

function fetch_nodes()
{

    $nodes = file_get_contents('nodes.json');
    $nodes = json_decode($nodes, true);

    return $nodes;

}

function set_nodes($nodes)
{

    file_put_contents(
        'nodes.json',
        json_encode($nodes)
    );

}