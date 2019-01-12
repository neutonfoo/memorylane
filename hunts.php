<!DOCTYPE html>
<html>
  <head>
    <title>Hunts</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
  </head>
  <body>
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

      $stmt = $conn->prepare("SELECT huntId, huntTitle, huntClues FROM mlane_hunts");
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
  </body>
</html>
