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

      table {
        border: 1px solid black;
        border-collapse: collapse;
        margin-bottom: 20px;
      }
      table td {
        border: 1px solid black;
        padding: 5px 10px;
        text-align: left;
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
      <table id="table-list"></table>
      <a href="https://briockmmo.com">
        <img
            src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png"
            width="100"
        />
      </a>
    </main>

    <script>

      setInterval(function(){

        let url = '/api/nodes';

        fetch(url)
        .then((response) => {
          return response.json();
        })
        .then((nodes) => {
          
          let table = document.getElementById('table-list');
          table.innerHTML = "";

          for(let node of nodes)
          {
            let respondedAt = new Date(node.responded_at);
            table.innerHTML += "<tr>" + 
              "<td>" + node.url + "</td>" + 
              "<td>" + timeAgo(node.responded_at) + "</td>" + 
              "<td>" + 
              (node.attempts == 0 ? "<i class=\"fa-solid fa-toggle-on\"></i>" : "<i class=\"fa-solid fa-toggle-off\"></i>") + 
              "</td>" + 
              "</tr>";
          }
          
        })
        .catch((error) => {
          console.log(error);
        });
      }, 5000);

      function timeAgo(date) {

        let now = Math.round(new Date().getTime() / 1000);
        let seconds = Math.floor(now - date);
        let interval = seconds / 31536000;

        if (interval > 1) return Math.floor(interval) + " years";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes";

        return Math.floor(seconds) + " seconds";

      }

    </script>

    <script src="https://kit.fontawesome.com/a74f41de6e.js" crossorigin="anonymous"></script>
  </body>
</html>
