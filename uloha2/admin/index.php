<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin panel</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<?php
  include "template/nav.php";
?>
<div class="container">
  <form action="upload.php" method="post" enctype="multipart/form-data" style="margin-top: 50px;">
    <h2 class="text-center">Načítanie výsledkov študentov</h2>
    <div class="form-group">
    <label for="skrok">Školský rok:</label>
    <select name="skolsky-rok" id="skrok">
      <option value="18-19">2018-2019</option>
      <option value="19-20">2019-2020</option>
      <option value="20-21">2020-2021</option>
    </select><br>
    <label for="predmet">Predmet:</label>
    <input type="text" id="predmet" placeholder="Názov predmetu" name="nazov-predmetu"><br>
    <label for="csvfile">CSV súbor s výsledkami:</label>
    <input type="file" name="csv" id="csvfile"><br>
    <label for="oddelovac">Oddeľovač použitý v CSV súbore:</label>
    <input type="text" value="," id="oddelovac" name="oddelovac"><br>
    <input type="submit" value="Pridať záznam" name="submit">
    </div>
  </form>

  <table class="table" style="display:none;">
    <thead>
      <tr>
        <th>ID</th>
        <th>MENO</th>
        <th>EMAIL</th>
        <th>TÍM č.</th>
      </tr>
    </thead>
    <tbody>
    <?php 
        include "../connect.php";

        $sql = "SELECT * FROM student";

        $result = $conn->query($sql);

        while($row = $result->fetch()) {
            echo '<tr>';
            for($i = 0; $i < count($row); $i++) {
                echo '<td>'.$row[$i].'</td>';
                

            }
            echo '</tr>';
        } 
      ?> 
    </tbody>
  </table>
  </div>
</body>
</html>