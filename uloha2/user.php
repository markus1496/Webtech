<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta name="author" content="Marek Janik">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>




<body class="bodytable">

<div class="jumbotron text-center">
    <h1>Webové technológie 2</h1>
    <p>Pohľad študenta</p>
    <div>
        <a href="https://147.175.121.210.nip.io:4115/ZaverecneZadanieCelok/logout.php" class="btn btn-success" role="button">Odhlásiť sa</a>
    </div>
</div>
<div class="box">
    <div class="container">
        <table class="table">
            <thead class="thead-dark">
            <th>Ais ID</th>
            <th>Meno Priezvisko</th>
            <th>E-mail</th>
            <th>Tím</th>
            <th>Body</th>
            <th>Súhlasí</th>
            </thead>
        <form method="POST" action="kapitan.php">
            <?php
            session_start();
            include "connect.php";
            $arr = array();
            $arr2 = array();
            $userN = $_SESSION['user'];
            //echo $userN;

            $sql = "SELECT id FROM kapitan WHERE id = '" . $userN . "@stuba.sk'";
            $result = $conn->query($sql)->fetch();

                if($result != false) {
                    echo '<script>console.log("kapitan")</script>';
                    $sql = "SELECT tim FROM student WHERE email = '" . $userN . "@stuba.sk'";
                    $result = $conn->query($sql);
                    //var_dump($result);
                    $typ = 1;
                    while ($row = $result->fetch()) {
                        $tim = $row[0];
                        $sql = "SELECT id, meno, email, tim, body, agree FROM student WHERE tim = '" . $tim . "'";
                        $result = $conn->query($sql);
                        while ($row1 = $result->fetch()) {
                            for ($i = 0; $i < count($row1); $i++) {
                                if($i == 5){
                                    if($row1[$i] == 0){
                                        echo '<td>&#10068;</td>';
                                    }elseif ($row1[$i] == 1){
                                        echo '<td>&#9989;</td>';
                                    }elseif ($row1[$i] == -1){
                                        echo '<td>&#10060;</td>';
                                    }
                                    $arr[$i] = $row1[$i];

                                }else{
                                    echo '<td>' . $row1[$i] . '</td>';
                                    $arr[$i] = $row1[$i];
                                }
                            }
                            echo '
                        <td><input type="number" name="body[]"  required></td>';
                            array_push($arr2, $arr[0]);


                            echo '<tr>';
                        }
                        //print_r($arr2);
                        echo '<button type="submit" class="btn btn-success" value="Submit">Odošli</button>';
                    }
                }
                else {
                    echo '<script>console.log("nie kapitan")</script>';
                    $sql = "SELECT tim FROM student WHERE email = '" . $userN . "@stuba.sk'";
                    $result = $conn->query($sql);
                    $typ = 0;

                    while ($row = $result->fetch()) {
                        $tim = $row[0];
                        $sql = "SELECT id, meno, email, tim, body, agree FROM student WHERE tim = '" . $tim . "'";
                        $result = $conn->query($sql);
                        //var_dump($result);
                        while ($row1 = $result->fetch()) {
                            for ($i = 0; $i < count($row1); $i++) {
                                if($i == 5){
                                    if($row1[$i] == 0){
                                        echo '<td>&#10068;</td>';
                                    }elseif ($row1[$i] == 1){
                                        echo '<td>&#9989;</td>';
                                    }elseif ($row1[$i] == -1){
                                        echo '<td>&#10060;</td>';
                                    }
                                    $arr[$i] = $row1[$i];

                                }else{
                                    echo '<td>' . $row1[$i] . '</td>';
                                    $arr[$i] = $row1[$i];
                                }
                            }


                            if($row1[2] == $userN . "@stuba.sk"){
                                //echo $row1[5];
                                if($row1[5] == 0) {
                                    echo '
                                    <td><input type="radio" name="checkbox" value="suhlasim"> Súhlasím</td>
                                    <td><input type="radio" name="checkbox" value="nesuhlasim">Nesúhlasím</td>
                                    <td><button type="submit" value="Submit">Odošli</button></td>
                                    <!--<td><input type="button" name="button" value="Suhlasim"></td>                        
                                    <td><input type="button" name="button" value="Nesuhlasim"></td>-->
                                ';
                                }
                            }
                            echo '<tr>';
                        }
                    }
                }
            $sql = "SELECT body FROM tim WHERE id = '" . $tim . "'";
            $result = $conn->query($sql);
            while ($row = $result->fetch()){
                echo "<h2>Celkové body vášho tímu:".$row[0]."</h2>";
            }
            ?>
            <input type='hidden' name='input_name' value="<?php echo htmlentities(serialize($arr2)); ?>">
            <input type='hidden' name='input_name2' value="<?php echo htmlentities(serialize($tim)); ?>">
            <input type='hidden' name='input_name3' value="<?php echo htmlentities(serialize($typ)); ?>">
            <!--<button type="submit" value="Submit">dokonci</button>-->
        </form>
        </table>

    </div>

</div>
</body>
</html>