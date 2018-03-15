<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  setInterval(function() {
    <?php
      $bus_url = 'http://www-devl.bu.edu/nisdev/php5/bu-mobile-backend-demo/bu-mobile-backend/rpc/bus/stops.json.php';
      $bus_json = file_get_contents($bus_url);
      $bus_array = json_decode($bus_json, true);
      $stops_url = 'http://www-devl.bu.edu/nisdev/php5/bu-mobile-backend-demo/bu-mobile-backend/rpc/bus/shapes.json.php';
      $stops_json = file_get_contents($stops_url);
      $stops_array = json_decode($stops_json, true);
      $live_url = 'http://www-devl.bu.edu/nisdev/php5/bu-mobile-backend-demo/bu-mobile-backend/rpc/bus/livebus.json.php';
      $live_json = file_get_contents($live_url);
      $live_array = json_decode($live_json, true);
      // print_r($live_array['ResultSet']['Result'][0]['lng']);
      // print_r($live_array['ResultSet']['Result'][0]['lat']);
      // print_r($live_array['totalResultsAvailable']);
    ?>
  },5000);
</script>

<!DOCTYPE html>
<html>
  <head>
    <style>
      #map {
        height: 600px;
        width: 100%;
       }
    </style>
  </head>
  <body>
    <h3>BU Shuttle</h3>
    <div id="map"></div>
    <script>
      function initMap() {

        // Render map of BU campus.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: 42.343893, lng: -71.098402},
          styles: [
            {
              "featureType": "administrative.land_parcel",
              "elementType": "labels",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "poi.business",
              "elementType": "labels.text",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "labels.text",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "poi.place_of_worship",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "poi.sports_complex",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "road.local",
              "elementType": "labels",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "featureType": "transit",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            }
          ]
        });

        var stopCoordinates = [
          <?php
          foreach($stops_array['ResultSet']['Result'] as $key => $item) {
            if ($item['active'] == 1) {
              foreach($item['coords'] as $stops) {
                echo '{lat: ' . $stops['lat'] . ', lng: ' . $stops['lng'] . '},';
              }
            }
          }
          ?>
        ];

        // Array of BU bus stops.
        var stops = [
          // {lat: 42.341337, lng: -71.082923},
          // {lat: 42.352514, lng: -71.118344}
          // ["M1",42.353151,-71.11815],["M2",42.35067231,-71.11343645],
          <?php
          // $array = array();
          foreach($bus_array['ResultSet']['Result'] as $key => $item) {
            echo '[' . '"' . $item['stop_id'] . '",' . $item['stop_lat'] . ',' . $item['stop_lon'] . '],';
            // echo $stop['stop_id'];
            // array_push($array, $stop['stop_id'], $stop['stop_lat']);
          }
          // print_r($array);
          ?>

        ];

        var livebus = [
          <?php
          if ($live_array['totalResultsAvailable'] > 0) {
            foreach($live_array['ResultSet']['Result'] as $bus) {
              // echo '[' .  $bus['coordinates'] . '],';
              echo '[' . $bus['lat'] . ',' . $bus['lng'] . '],';
            }
          }
          ?>
        ];

        // stops.toString();
        // console.log(stops);

        var image = 'https://www.bu.edu/thebus/wp-content/themes/flexi-transportation/images/map/bus-stop.png';
        // var image = 'bus_stop.png';
        var marker, i;
        for (i = 0; i < stops.length; i++) {
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(stops[i][1],stops[i][2]),
            map: map,
            icon: image
          })
        }

        var bus, j;
        for (j = 0; j < livebus.length; j++) {
          bus = new google.maps.Marker({
            position: new google.maps.LatLng(livebus[j][0],livebus[j][1]),
            // position: new google.maps.LatLng(42.350435023404,-71.089279744967),
            map: map,
          })
        }

        var busPath = new google.maps.Polyline({
          path: stopCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 3
        });

        busPath.setMap(map);

//        var markers = stops.map(function(stop, i) {
//          return new google.maps.Marker({
//            position: stop,
//          });
//        });

      }

    // setInterval(dataPull, 5000);
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4aoUYJDMxDfiMOOz7iqoeslQ4E9z9FHQ&callback=initMap">
    </script>
  </body>
</html>
