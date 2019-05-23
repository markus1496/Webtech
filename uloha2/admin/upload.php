<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin panel - Nahrávanie</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
</head>
<body>
<?php
  include "template/nav.php";
?>
<div class="container" style="margin-top:50px;">
  <h2 class="text-center">Študenti predmetu</h2>
  <?php 
    include "../connect.php";
    

    $csv = array();
    $teams = array();
    $skrok=$_POST["skolsky-rok"];
    $predmet=$_POST["nazov-predmetu"];
    $oddelovac=$_POST["oddelovac"];

    try {
      $conn->beginTransaction();
      
      $conn->exec("INSERT INTO rocnik (id_tim, skolskyrok, predmet)
      VALUES ('1', '".$skrok."', '".$predmet."')"); 
     
      $conn->commit();

    } catch (PDOException $e) {
        echo $e;
    }
    

    // check there are no errors
    if($_FILES['csv']['error'] == 0){
        $name = $_FILES['csv']['name'];
        $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
        $type = $_FILES['csv']['type'];
        $tmpName = $_FILES['csv']['tmp_name'];
    
        // check the file is a csv
        if($ext === 'csv'){
            if(($handle = fopen($tmpName, 'r')) !== FALSE) {
                // necessary if a large csv file
                set_time_limit(0);
    
                $row = 0;
    
                while(($data = fgetcsv($handle, 1000, $oddelovac)) !== FALSE) {
                    // number of fields in the csv
                    $col_count = count($data);
    
                    // get the values from the csv
                    $csv[$row]['col1'] = $data[0];
                    $csv[$row]['col2'] = $data[1];
                    $csv[$row]['col3'] = $data[2];
                    $csv[$row]['col4'] = $data[3];
                    $csv[$row]['col5'] = $data[4];
                    
                   
                    
                    // inc the row
                    
                    $row++;
                    
                }
                //var_dump($csv);
                fclose($handle);
            }
        }
        //echo $row;
        
        
          
          try {
            $conn->beginTransaction();
            for ($i = 0; $i < $row; $i++) {
              if (in_array($csv[$i]['col4'], $teams) == FALSE) {
                array_push($teams, $csv[$i]['col4']);
              }
              
              $conn->exec("INSERT INTO student (id, meno, email, tim, agree)
              VALUES ('".$csv[$i]['col1']."', '".$csv[$i]['col2']."' , '".$csv[$i]['col3']."' , '".intval($csv[$i]['col4'])."' , '".intval($csv[$i]['col5'])."' )"); 
            }
                    
            
            $conn->commit();
            
  
        } catch (PDOException $e) {
            echo $e;
        }
       
        
        try {
          $conn->beginTransaction();
          foreach ($teams as $tim) {
            $conn->exec("INSERT INTO tim (id) VALUES ('".intval($tim)."')"); 
          }
         
          $conn->commit();
    
        } catch (PDOException $e) {
            echo $e;
        }
        $conn = null;
        //var_dump($teams);
        

    }
  ?>

<table class="table">
    <thead>
      <tr>
        <th>TIM</th>
        <th>EMAIL</th>        
        <th>MENO</th>
        <th>AGREE </th>
      </tr>
    </thead>
    <tbody>
      <?php 
        include "../connect.php";

        //$sql = "SELECT * FROM tim";
        $sql1 = "SELECT tim, email, meno, agree  FROM student JOIN tim ON student.tim = tim.id ORDER BY tim.id";
        $result = $conn->query($sql1);

        while($row = $result->fetch()) {
            echo '<tr>';
            for($i = 0; $i < count($row); $i++) {


                echo '<td>'.$row[$i].'</td>';
                

            }
            echo '</tr>';
            
        }
        //var_dump($teams);
        echo '<td></td><td></td><td></td><td><b>(Súhlas = 1, Nesúhlas = -1, Nevyjadril sa = 0)</b></td>';
      ?>
    </tbody>
  </table>
  <div id="addPts">
  <h2 class="text-center">Pridať body tímom</h2>
  <form method="post">
  <table class="table">
    <thead>
      <tr>
        <th>TIM</th>
        <th>BODY</th>        
      </tr>
    </thead>
    <tbody>
      <?php 
        for ($i = 0; $i < sizeof($teams);$i++) {
          echo '<tr>';
          echo '<td>'.$teams[$i].'</td>';
          echo '<td>body: <input type="text" name="'.$teams[$i].'"></td>';
          echo '</tr>';
        }
      ?>
    </tbody>
  </table>
 
  <input type="submit" value="Ukázať body" name="submitted" id="submitBody">
  </form>
  </div>
  <?php 
    if(isset($_POST['submitted'])) {
      //var_dump($_POST['submitted']);
      try {
        $conn->beginTransaction();        
        for ($i = 1; isset($_POST[strval($i)]); $i++) {          
          $conn->exec("UPDATE tim SET body = ".intval($_POST[strval($i)])." WHERE id = ".$i."");
        }       
        $conn->commit();
  
      } catch (PDOException $e) {
          echo $e;
      }
      //echo '<span id="hid"></span>';
      echo "
      <h2 class='text-center'>Hodnotenie tímov</h2>
      <table class='table' id='teamPointsTables' style='display:block;'>
      <thead>
        <tr>
          <th>TIM</th>
          <th>BODY</th>        
        </tr>
      </thead>
      <tbody>";
      $sql1 = "SELECT id, body  FROM tim ORDER BY tim.id";
                  $result = $conn->query($sql1);
            
                  while($row = $result->fetch()) {
                      echo '<tr>';
                      for($i = 0; $i < count($row); $i++) {
            
            
                          echo '<td>'.$row[$i].'</td>';
                          //echo '<span id="hid"></span>';
                          
            
                      }
                      echo '</tr>';
                  }
      echo "</tbody> </table>
      </div>";
      
    } else {
     // echo '<span id="hid"></span>';
    }

    
?>             
 
  
</body>
</html>