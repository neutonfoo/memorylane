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
      <table class="table">
        <thead>
          <tr>
            <th>Hunt Title</th>
            <th>First Clue</th>
          </tr>
        </thead>
        <tbody>
  <?php
    $servername = "127.0.0.1";
    $dbname = "memoryLane";
    $username = "root";
    $password = "root";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=3306", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT huntId, huntTitle, huntClues FROM mlane_hunts");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <tr>
            <td>
              <a class="btn btn-celadon" href="hunt.php?huntId=<?=$row['huntId']; ?>">
                Begin
              </a>
              <span style="padding-left: 20px;"><?=$row['huntTitle']; ?></span>
            </td>
            <td class="clue"><?=json_decode($row['huntClues'])[0]; ?></td>
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
