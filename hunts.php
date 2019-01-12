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
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
  </head>
  <body>
    <div id="header">
      <div id="titleSmall">
        <a href="index.php">Memory Lane</a>
      </div>
    </div>
    <div class="mainContainer">
      <div class="instructions">
        Select the hunt you want to do.
      </div>
      <!-- sth to find hunts near you, maybe rank from how close they are from start distance -->
      <table class="table">
        <thead>
          <tr>
            <th>Hunt Title</th>
            <th>First Hunt Clue</th>
          </tr>
        </thead>
        <tbody>
  <?php
    $servername = "localhost";
    $dbname = "fun";
    $username = "root";
    $password = "alpine";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT huntId, huntTitle, huntClues FROM mlane_hunts ORDER BY ");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <tr>
            <td>
              <a href="hunt.php?huntId=<?=$row['huntId']; ?>">
                <?=$row['huntTitle']; ?>
              </a>
            </td>
            <td><?=json_decode($row['huntClues'])[0]; ?></td>
          </tr>
          <?php
        }
    } catch(PDOException $e) {
      echo $sql . '<br>' . $e->getMessage();
    }

    $conn = null;
  ?>
        </tbody>
      </table>
    </div>

  </body>
</html>
