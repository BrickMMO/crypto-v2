<?php

/**
 * Outputs a complex variable in an each to read format.
 * @param variable $data complex data to be outputted
 */
function debug_pre($data)
{

    // Wrap output in PRE tag to preserve spacing and lines
    echo '<pre>';
    print_r($data);
    echo '</pre>';

}

/**
 * Fetches existing nodes from nodes.json, if this is the first time this
 * function has been executed it twill create a new list of nodes using
 * the nodes-genesis.json file and add itself.
 * @return array An array of current nodes
 */
function nodes_fetch()
{

    // Check for the nodes.json file
    if(file_exists('nodes.json'))
    {

        // Open nodes.json and convert contents to an array
        $nodes = file_get_contents('nodes.json');
        $nodes = json_decode($nodes, true);

    }

    // If there is no nodes.json file, use the nodes-genesis.json file
    else
    {

        // Open nodes-genesis.json and convert contents to an array// Open file 
        $nodes = file_get_contents('nodes-genesis.json');
        $nodes = json_decode($nodes, true);

        $self = false;

        foreach($nodes as $key => $node)
        {
            if($node['url'] == DOMAIN) $self = true;
        }

        if($self == false)
        {

            // Add itself to the node array
            $nodes[] = array(
                'url' => DOMAIN,
                'responded_at' => null,
                'attempts' => 0,
            );

        }

    }

    // Return an array of nodes
    return $nodes;

}

/**
 * Saves a node list to nodes.json file.
 * @param array $nodes an array of current nodes
 */
function nodes_set($nodes)
{

    file_put_contents(
        'nodes.json',
        json_encode($nodes)
    );

}

/**
 * 
 */
function nodes_add($url)
{

    // Get a list of local nodes
    $nodes = nodes_fetch();

    // Define a boolean variable to track if this node should be aded to the 
    // local list
    $exists = false;

    // Loop through local nodes
    foreach($nodes as $key => $node)
    {

        // If node is alreay on the list, ignore the node
        if($url == $node['url'])
        {
            $exists = true;
        }

    }

    // If node is no to be ignored, add it to the local list
    if($exists == false)
    {
        $nodes[] = array(
            'url' => $url,
            'responded_at' => null,
            'attempts' => 0
        );
    }
    
    // Save revised node list to the nodes.json file
    nodes_set($nodes);
}

/**
 * Merges two arrays of nodes into a single array with no duplicates. 
 * @param array $nodes_local array of nodes from the local node
 * @param array $nodes_remote array of nodes from a remote node
 * @return array An array of merged nodes
 */
function nodes_merge($nodes_local, $nodes_remote)
{

    // Create an array to store new nodes
    $nodes_new = array();

    // Loop through the remote nodes list
    foreach($nodes_remote as $key => $node_new)
    {

        // Define a boolean variable to track if this node should be aded to the
        $ignore = false;

        // Loop through local nodes
        foreach($nodes_local as $key => $node_check)
        {

            // If node is already on the list, ignore the node
            if($node_new['url'] == $node_check['url'])
            {
                $ignore = true;
            }

            // If attempts if over 50, ignore the node
            if($node_new['attempts'] >= 50)
            {
                $ignore = true;
            }

        }

        // If node is not to be ignored, add it to the local list
        if($ignore == false)
        {
            $nodes_local[] = $node_new;
        }

    }

    // Retuen array of merged local list
    return $nodes_local;

}

/**
 * Opens nodes.json and removes inactive nodes. 
 */
function nodes_check_missing()
{

    // Get a list of local nodes
    $nodes = nodes_fetch();

    // Define an aray of nodes to keep on the local list
    $nodes_keep = array();

    // Define a variable to store the current time
    $time = time();

    // Loop through the list of local nodes
    foreach($nodes as $key => $node)
    {

        // If node has not responded over 50 timers, or has not responded in over 
        // 24 hours, remove the node from the list
        if(($node['responded_at'] == 0 || $time - $node['responded_at'] > 60 * 60 * 24) && $node['attempts'] >= 50)
        {

        }
        else
        {

            // Add node to list of nodes to keep
            $nodes_keep[] = $node;

        }

    }

    // Save revised node list to the nodes.json file
    nodes_set($nodes_keep);

}
