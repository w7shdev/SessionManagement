<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sabre Session Management</title>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
      integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I"
      crossorigin="anonymous"
    />

    <style type="text/css">
    .hotel-block{ 

      background:  #f8f9fa;

      border-radius: 4px;
      padding: 9px; 
      box-sizing: border-box;

    }
    </style> 

  </head>
  <body>
    <div class="container">
      <h1>
        Session Management
      </h1>
      <h3>Sample of request</h3>
      <hr />
      <div class="overflow-auto bg-light mb-2" style="height: 300px;">
        <code>
          <pre>  
                    <?php print_r($request_json); ?> 
        </pre>  
        </code>
      </div>

      <button type="button" class="btn btn-primary btn-large" id="getHotelBtn">
        Get the response
      </button>

      <div id="response_block" class="d-none" >
        <div class="hotelList">
        <div class="row  my-2 rounded  shadow-sm hotel-block">
          <div class="col-12">
            <div class="row">
              <div class="col-9">
                <h2 id="hotel_title">Holiday Inn</h2>
              </div>
              <div class="col-3">
                <span id="sabre_rate">Rate</span>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              <div class="col-4">
                  <img src="" alt="LOGO" id="hotel_logo" />
              </div>
              <div class="col-4">
                <p class="mb-1">
                  <span id="address_line">address_line</span>
                  <span class="d-block text-muted" id="origin">Muscat , oman</span>
                </p>
                <ul class="mt-0 mb-0 text-muted font-italic" id="contact_info">
                </ul>
              </div>
              <div class="col-4">2
                  display map
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>

    <!-- javascript -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="script.js"></script>
    <script type="text/javascript">
      var conn = new WebSocket("ws://localhost:8080");
      conn.onopen = function (e) {
        // console.log("Connection established!");
        console.log(e);
      };

      conn.onmessage = function (e) {
        console.log(e.data);
      };
    </script>
  </body>
</html>
