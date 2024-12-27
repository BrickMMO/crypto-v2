<?php

/**
 * This API call returns a list of local nodes. This file is called each minute
 * by each other node to ensure the nodes.json file is synchronized.
 */

 // Include config and function files
include('includes/config.php');
include('includes/functions.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?=NAME?></title>
    <style>
      html,
      body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
      }

      body {
        font-family: "Courier New", Courier, monospace;
        text-align: center;
        background-color: #e4ede9;
      }

      h2 {
        color: #eb062c;
      }

      h3 {
        color: #eb741d;
      }

      img {
        margin-right: 10px;
      }
    </style>

    <link rel="stylesheet" href="https://cdn.brickmmo.com/exceptions@1.0.0/fontawesome.css" />
  </head>
  <body>
    <main>
      <a href="https://loot.brickmmo.com">
        <img src="loot-logo.png" width="300">
      </a>
      <h1><?=NAME?></h1>
      <p>
        Domain:
        <strong><?=DOMAIN?></strong>
        <br />
        Genesis Node: 
        <strong><?=(GENESIS ? 'YES' : 'NO')?></strong>
      </p>
      <a href="https://briockmmo.com">
        <img
            src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png"
            width="100"
        />
      </a>
    </main>

    <script src="https://kit.fontawesome.com/a74f41de6e.js" crossorigin="anonymous"></script>
  </body>
</html>
