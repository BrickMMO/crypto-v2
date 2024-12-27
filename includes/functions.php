<?php

function debug_pre($data)
{

    echo '<pre>';
    print_r($data);
    echo '</pre>';

}

function nodes_fetch()
{

    $nodes = file_get_contents('nodes.json');

    $nodes = json_decode($nodes, true);

    return $nodes;

}

function nodes_set($nodes)
{

    file_put_contents(
        'nodes.json',
        json_encode($nodes)
    );

}

function nodes_merge($nodes_local, $nodes_remote)
{

    $nodes_new = array();

    foreach($nodes_remote as $key => $node_new)
    {

        $existing = false;

        foreach($nodes_local as $key => $node_check)
        {

            // echo 'COMPARING<br>';

            if($node_new['url'] == $node_check['url'])
            {
                $existing = true;
            }

            // echo '<hr>';

        }

        if($existing == false)
        {
            $nodes_local[] = $node_new;
        }

    }

    return $nodes_local;

}
