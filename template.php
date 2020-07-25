<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabre Session Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <h1>
            Session Management
        </h1>
        <h3>Sample of request </h3>
        <hr />
        <div class="overflow-auto bg-light mb-2" style="height: 300px;">
            <code>
              <pre>  
                    <?php print_r($request_json); ?> 
              </pre>
            </code>
        </div>


        <a class="btn btn-primary btn-large" href="index.php?response=do">get the response  </a>

        <?php if($_GET['response'] ?? false): ?> 
        <h3>Sample of Response </h3>
        <hr />
        <div class="overflow-auto bg-light" style="height: 300px;">
            <code>
              <pre>  
                    <?php print_r(json_decode($request->getBody()->getContents(),true)); ?> 
              </pre>
            </code>
        </div>
        <?php endif; ?> 

        
    </div>

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
