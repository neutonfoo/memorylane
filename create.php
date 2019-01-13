<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Kodchasan|Permanent+Marker|Shadows+Into+Light|Special+Elite" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/style_create.css"/>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
  </head>
  <body>
    <div id="header">
      <div id="titleSmall">
        <a href="index.php">Memory Lane</a>
      </div>
    </div>
    <div class="row no-gutters h-100">
      <div id="mapContainer" class="col-md-6 col-sm-12">
        <div id="map" class=""></div>
        <input id="pac-input" class="controls form-control" type="text" style="position: absolute;" placeholder="Search Box">
      </div>
      <div id="createPanel" class="col-md-6 col-sm-12">
        <div class="d-flex flex-column h-100">
          <div class="bd-highlight">
            <h1>Create a hunt</h1>
            <div class="instructions">
              Click on the map to lay down the location of each clue.  <br/>
              First enter the start location and its corresponding clue. <br/>
              Finish by clicking the Finalize button to create your hunt.
            </div>
            <form id="huntForm" action="createSubmit.php" method="post">
              <div class="form-group row no-gutters">
                <div class="col-10">
                  <input id="huntTitle" name="huntTitle" class="form-control" type="text" placeholder="Scavenger Hunt Title" required>
                  <!-- first clue -->
                  <input id="huntLocations" name="huntLocations" type="hidden" value="">
                  <input id="huntClues" name="huntClues" type="hidden" value="">
                  <!-- final destination here? -->
                </div>
                <div class="col-2">
                    <input id="finalizeButton" class="btn btn-celadon" type="button" value="Finalize">
                </div>
              </div>
            </form>
            <table id="huntLocationsHeadingsTable" class="table huntLocationsTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Clue</th>
                </tr>
              </thead>
            </table>
          </div>
          <div id="editorBottom">
            <table class="table huntLocationsTable">
              <tbody id="huntLocationsValues"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">

    var markerId = 0

    var decimalCount = 5
    var huntMarkers = []
    var huntLocations = []
    var huntPath;

    var $huntLocationsValues = $('#huntLocationsValues')

    var poly;

    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 46.358168, lng: 6.13604 },
        zoom: 8,
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false
      });


      var input = document.getElementById('pac-input');
      var searchBox = new google.maps.places.SearchBox(input);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
      });

      var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
         var bounds = new google.maps.LatLngBounds();
         places.forEach(function(place) {
           if (!place.geometry) {
             console.log("Returned place contains no geometry");
             return;
           }
           var icon = {
             url: place.icon,
             size: new google.maps.Size(71, 71),
             origin: new google.maps.Point(0, 0),
             anchor: new google.maps.Point(17, 34),
             scaledSize: new google.maps.Size(25, 25)
           };

           // Create a marker for each place.
           markers.push(new google.maps.Marker({
             map: map,
             icon: icon,
             title: place.name,
             position: place.geometry.location
           }));

           if (place.geometry.viewport) {
             // Only geocodes have viewport.
             bounds.union(place.geometry.viewport);
           } else {
             bounds.extend(place.geometry.location);
           }
         });
         map.fitBounds(bounds);



        })

      poly = new google.maps.Polyline({
        editable: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 3
      });
      poly.setMap(map);

      // Inserting new vertex in between existing edge
      google.maps.event.addListener(poly, 'mouseup', function(event) {
        // console.log('I want to die.')
      });

      // Inserting new vertext after last edge
      google.maps.event.addListener(map, 'click', function(event) {
        // console.log('I want to die2.')

        var path = poly.getPath()
        path.push(event.latLng)

        var position = { lat: event.latLng.lat(), lng: event.latLng.lng() }
        huntLocations.push(position)
        // var markerId = huntMarkers.length

        // var marker = new google.maps.Marker({
        //   id: markerId,
        //   position: position,
        //   map: map,
        //   draggable: true,
        //   animation: google.maps.Animation.DROP,
        //   title: 'Hello World!'
        // });
        //
        // huntMarkers.push(marker)

        // markerId++;

        var locId = huntLocations.length

        var row = `
        <tr id="huntLoc${ locId }" date-locid="${ locId }">
          <th scope="row">${ locId }</th>
          <td>
            <textarea class="form-control huntClue" rows="3"></textarea>
          </td>
        </tr>
        `
        $huntLocationsValues.append(row)

        // google.maps.event.addListener(marker, 'dragend', function() {
        //   var newPosition = { lat : marker.getPosition().lat(), lng : marker.getPosition().lng() }
        //   console.log(`Marker ${ marker.id } moved to lat : ${ newPosition.lat } and lng : ${ newPosition.lng }`)
        //
        //   var $huntLocRow = $(`tr#huntLoc${ marker.id }`)
        //   $huntLocRow.find('.lat').html(newPosition.lat.toFixed(decimalCount))
        //   $huntLocRow.find('.lng').html(newPosition.lng.toFixed(decimalCount))
        //
        // });
        //
        // console.log('Updated hunt = ')
        // console.log(huntLocs)
        //
        // huntPath = new google.maps.Polyline({
        //   path: huntLocs,
        //   geodesic: true,
        //   strokeColor: '#FF0000',
        //   strokeOpacity: 1.0,
        //   strokeWeight: 2
        // });
        //
        // huntPath.setMap(map);

      });
    }

    $(document).ready(function() {
      $huntForm = $('#huntForm')
      $huntLocations = $('#huntLocations')
      $huntClues = $('#huntClues')

      $finalizeButton = $('#finalizeButton')

      $finalizeButton.on('click', function() {
        var huntClues = []
        $huntLocations.val(JSON.stringify(huntLocations))

        $('.huntClue').each(function(huntClueIndex) {
          huntClues.push($(this).val())
        })

        $huntClues.val(JSON.stringify(huntClues))

        if($("#huntTitle").val().trim() == "") {
          alert("Please enter a title for the hunt.")
        }
        else {
          $huntForm.submit()
        }
      })
    })
    </script>
		<!-- API KEY = AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ&libraries=places&callback=initMap" async defer></script>
  </body>
</html>
