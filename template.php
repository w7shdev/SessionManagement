<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome</h1>


    <script>
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            // console.log("Connection established!");
            console.log(e);
        };

        conn.onmessage = function(e) {
            console.log(e.data);
        };
    </script>
</body>
</html>
