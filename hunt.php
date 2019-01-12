<!DOCTYPE html>
<html>
  <head>
    <title>Hunts</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Kodchasan|Permanent+Marker|Shadows+Into+Light|Special+Elite" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/style_hunt.css"/>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
  </head>
  <body>
<?php

  $huntId = $_GET['huntId'];
  $huntTitle;
  $huntLocations;
  $huntClues;

  $servername = "localhost";
  $dbname = "salhacks_main";
  $username = "salhacks_main";
  $password = "eTZJ]5#riQ.P5kHH";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=3306", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare("SELECT huntTitle, huntLocations, huntClues FROM mlane_hunts WHERE huntId = ? LIMIT 1");
      $stmt->execute([$huntId]);
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $huntTitle = $row['huntTitle'];
        $huntLocations = json_decode($row['huntLocations']);
        $huntClues = json_decode($row['huntClues']);

      }
  } catch(PDOException $e) {
    echo $sql . '<br>' . $e->getMessage();
  }

  $conn = null;
?>
    <div id="header">
      <div id="titleSmall">
        <a href="index.php">Memory Lane</a>
      </div>
    </div>
    <div class="mainContainer">
      <h1><?=$huntTitle; ?></h1>
      <div class="instructions">
        Go to the place where the clue indicates to get your next clue.
      </div>
      <table id="huntTable" class="table">
        <thead>
          <tr>
            <th width="10%">#</th>
            <th width="40%">Clue</th>
            <th width="40%">Check Location</th>
            <th width="10%">Solved</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $lastIndex = 0;

            foreach($huntLocations as $huntLocationIndex => $huntLocation) {
              $lastIndex = $huntLocationIndex;
              ?>
              <tr id="loc<?=$huntLocationIndex; ?>Row">
                <td><?=$huntLocationIndex; ?></td>
                <td class="clue"><?=$huntClues[$huntLocationIndex]; ?></td>
                <td id="loc<?=$huntLocationIndex; ?>Info">
                  <button type="button" class="btn btn-celadon checkLocation hereBtn" data-locindex="<?=$huntLocationIndex; ?>" data-lat="<?=$huntLocation->lat; ?>" data-lng="<?=$huntLocation->lng; ?>">I'm here!</button>
                </td>
                <td>
                  <?php
                      // if(solved) {
                      //   <img src="checkmark.png" alt="Checked!">
                      // }
                   ?>
                </td>
              </tr>
              <?php
            }
          ?>
        </tbody>
      </table>
    </div>

    <script type="text/javascript">
      $(document).ready(function() {
        var lastIndex = <?=$lastIndex; ?>

        var userLatitude = 0;
        var userLongitude = 0;

        var locIndex = 0
        var locLatitude = 0
        var locLongitude = 0

        var $checkLocation = $('.checkLocation')

        function getLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
          } else {
            console.log('Geolocation is not supported by this browser.');
          }


        }

        function showPosition(position) {
          userLatitude = position.coords.latitude;
          userLongitude = position.coords.longitude;

          var distance = getDistanceFromLatLonInM(userLatitude, userLongitude, locLatitude, locLongitude)

          var $locInfo = $('#loc' + locIndex + 'Info').html('Distance = ' + distance.toFixed(0) + ' meters away')

          // Within 50m
          if(distance <= 500) {
            var nextLocRowIndex = locIndex + 1
            var $nextLocRow = $('#loc' + nextLocRowIndex + 'Row')

            $nextLocRow.show()

          }

          console.log('Distance = ' + distance + 'm')
        }

        $checkLocation.on('click', function() {

          locIndex = $(this).data('locindex')

          locLatitude = $(this).data('lat')
          locLongitude = $(this).data('lng')

          getLocation()


        })

        function getDistanceFromLatLonInM(lat1,lon1,lat2,lon2) {

          var R = 6371; // Radius of the earth in km
          var dLat = deg2rad(lat2-lat1);  // deg2rad below
          var dLon = deg2rad(lon2-lon1);
          var a =
          Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
          Math.sin(dLon/2) * Math.sin(dLon/2);
          var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
          var d = R * c * 1000; // Distance in m

          return d;
        }

        function deg2rad(deg) {
          return deg * (Math.PI/180)
        }

      })
    </script>
  </body>
</html>
