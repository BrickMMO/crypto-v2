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
        $nodes_local = file_get_contents('nodes.json');
        $nodes_local = json_decode($nodes_local, true);

    }

    // If there is no nodes.json file, use the nodes-genesis.json file
    else
    {

        // Open nodes-genesis.json and convert contents to an array
        $nodes_local = file_get_contents('nodes-genesis.json');
        $nodes_local = json_decode($nodes_local, true);

        // Check if self is on the list
        $self = false;

        // Loop through nodes
        foreach($nodes_local as $key => $node)
        {
            // Check if self is in the list of nodes
            if($nodes_local['url'] == DOMAIN) $self = true;
        }

        // If self is not in the list, add self to the list
        if($self == false)
        {

            // Add itself to the node array
            $nodes_local[] = array(
                'url' => DOMAIN,
                'responded_at' => null,
                'attempts' => 0,
            );

            // Save nodes to local json
            nodes_set($nodes_local);

        }

    }

    // Return an array of nodes
    return $nodes_local;

}

/**
 * Saves a node list to nodes.json file.
 * @param array $nodes an array of current nodes
 */
function nodes_set($nodes_local)
{

    file_put_contents(
        'nodes.json',
        json_encode($nodes_local)
    );

}

/**
 * This function checks a URL to see if the node exists in the local
 * list of nodes.
 * @param string $url of the new node to check
 * @param array $nodes an array of current nodes
 * @return boolean A true or false based on node existing
 */
function nodes_exist($url, $nodes_local = false)
{

    if(!$nodes_local) $nodes_local = nodes_fetch();

    // Loop through local nodes
    foreach($nodes_local as $key => $node)
    {

        // Check if node is in the local list
        if($node['url'] == $url)
        {
            return true;
        }

    }

    return false;

}

/**
 * Merges two arrays of nodes into a single array with no duplicates. 
 * @param array $nodes_local array of nodes from the local node
 * @param array $nodes_remote array of nodes from a remote node
 * @return array An array of merged nodes
 */
function nodes_add($nodes_remote, $nodes_local = false)
{

    // Check if remote nodes are provided as an array
    if(!is_array($nodes_remote))
    {
        $nodes_remote[] = array(
            'url' => $nodes_remote,
            'responded_at' => null,
            'attempts' => 0
        );
    }
    
    // Create an array to store new nodes
    if(!$nodes_local) $nodes_local = nodes_fetch();

    // Loop through the remote nodes list
    foreach($nodes_remote as $key => $node_new)
    {

        if(!nodes_exist($node_new['url'], $nodes_local))
        {
            
            $nodes_local[] = array(
                'url' => $node_new['url'],
                'responded_at' => null,
                'attempts' => 0
            );

        }

    }

    nodes_set($nodes_local);

}

/**
 * Opens nodes.json and removes inactive nodes. 
 */
function nodes_check_missing()
{

    // Get a list of local nodes
    $nodes_local = nodes_fetch();

    // Define an aray of nodes to keep on the local list
    $nodes_keep = array();

    // Define a variable to store the current time
    $time = time();

    // Loop through the list of local nodes
    foreach($nodes_local as $key => $node)
    {

        // If node has not responded over 50 timers, or has not responded in over 
        // 24 hours, remove the node from the list
        if(($node['responded_at'] == 0 || $time - $node['responded_at'] > 60 * 60 * 12) && $node['attempts'] >= 50)
        {

            // Remove node
            
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
