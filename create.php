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
    <style media="screen">

    * {
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      width: 100%;
    }

    #mapContainer {
      /* position: relative; */
    }

    #map {
      position: absolute;

      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    #editorContainer {
      height: 100%;
    }

    #editorTop {
      background-color: #FFF;
    }

    #editorBottom {
      overflow-y: auto
    }

    .huntLocationsTable tr th:nth-child(1),
    .huntLocationsTable tr td:nth-child(1) {
      width:10%
    }

    .huntLocationsTable tr th:nth-child(2),
    .huntLocationsTable tr td:nth-child(2) {
      width:20%
    }

    .huntLocationsTable tr th:nth-child(3),
    .huntLocationsTable tr td:nth-child(3) {
      width:20%
    }

    .huntLocationsTable tr th:nth-child(4),
    .huntLocationsTable tr td:nth-child(4) {
      width:50%
    }

    #huntLocationsHeadingsTable {
      margin:0;
    }

    #huntLocationsValuesContainer {
    }

    #huntLocationsValues {
      overflow: scroll;
    }

    #huntLocationsValues tr:first-child th,
    #huntLocationsValues tr:first-child td {
      border-top:0;
    }

    td.addHuntLocationRow {
      text-align: center;
    }

    /* .no-gutters {
      margin-right: 0;
      margin-left: 0;

      > .col,
      > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
      }
    } */

    </style>
  </head>
  <body>

    <div class="row no-gutters h-100">
      <div id="mapContainer" class="col-6">
        <div id = "map" class=""></div>
      </div>
      <div class="col-6">
        <div class="d-flex flex-column h-100">
          <div class="bd-highlight">
            <h1>Creator</h1>
            <form id="huntForm" action="createSubmit.php" method="post">
              <div class="form-group row no-gutters">
                <div class="col-10">
                  <input name="huntTitle" class="form-control" type="text" placeholder="Scavenger Hunt Title ">
                  <input id="huntLocations" name="huntLocations" type="hidden" value="">
                  <input id="huntClues" name="huntClues" type="hidden" value="">
                </div>
                <div class="col-2">
                    <input id="finalizeButton" class="btn btn-primary" type="button" value="Finalize">
                </div>
              </div>
            </form>
            <table id="huntLocationsHeadingsTable" class="table huntLocationsTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Latitude</th>
                  <th scope="col">Longitude</th>
                  <th scope="col">Clue</th>
                </tr>
              </thead>
            </table>
          </div>
          <div id="editorBottom" class="">
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
        zoom: 8
      });

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
          <td class="lat">${ position.lat.toFixed(decimalCount) }</td>
          <td class="lng">${ position.lng.toFixed(decimalCount) }</td>
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
        $huntForm.submit()
      })
    })
    </script>
		<!-- API KEY = AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQKy3AQgRYQvYAsP0cE3B9-_biNhSvXQ&callback=initMap" async defer></script>
  </body>
</html>
