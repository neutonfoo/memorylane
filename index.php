<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
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
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 46.358168, lng: 6.13604},
          zoom: 8
        });
      }
    </script>
		<!-- API KEY = AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ&callback=initMap" async defer></script>
  </body>
</html>
