<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Cron Jobs</title>
</head>
<body>

    <h1>Testing Cron Jobs</h1>

    <iframe border="1" width="100%" height="400" id="iframe"></iframe>

    <script>

        setInterval(function(){

            const iframe = document.getElementById('iframe');
            iframe.src = "/cron-find-nodes.php";

        }, 10000);

    </script>
    
</body>
</html>