<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id = "map"></div>
    <script type="text/javascript">

    $(document).ready(function() {

      var huntLocs = []
      var huntPath;

      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.358168, lng: 6.13604},
        zoom: 8
      });

      google.maps.event.addListener(map, 'click', function(event) {
        var position = { lat: event.latLng.lat(), lng: event.latLng.lng() }

        huntLocs.push(position)

        var marker = new google.maps.Marker({
          position: position,
          map: map,
          draggable: true,
          animation: google.maps.Animation.BOUNCE,
          title: 'Hello World!'
        });

        console.log('Updated hunt = ')
        console.log(huntLocs)

        huntPath = new google.maps.Polyline({
          path: huntLocs,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        huntPath.setMap(map);

      });

    });
    </script>
		<!-- API KEY = AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ" async defer></script>
  </body>
</html>
