<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="css/style.css">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body class="jumbotron text-center">
<h1>Status pre nahrávanie a odsúhlasenie bodov</h1>
<div class="box">

    <?php
    session_start();

    include "connect.php";

    $id = unserialize($_POST['input_name']);
    $tim = unserialize($_POST['input_name2']);
    $typ = unserialize($_POST['input_name3']);
    $body = $_POST['body'];
    $checked = $_POST['checkbox'];
    $checked2 = $_POST['checkbox2'];
    $userN = $_SESSION['user'];

    /*echo $userN;
    echo $checked;
    echo $tim;
    print_r($id);
    print_r($body);*/

    if($typ == 1) {
        $sql = "SELECT body FROM tim WHERE id = '" . $tim . "'";
        $result = $conn->query($sql);

        while ($row = $result->fetch()) {
            if ($row[0] == array_sum($body)) {
                for ($i = 0; $i < count($id); $i++) {
                    $conn->beginTransaction();
                    $conn->exec("UPDATE student SET body='" . $body[$i] . "' WHERE id = '" . $id[$i] . "'");
                    $conn->commit();
                    echo "<p>New records created successfully</p>";
                    //$conn = null;
                }
            } else {
                echo "<p>Nesedí počet bodov</p>";
            }
        }
    }
    else{
        echo '<script>console.log("nie kapitan2")</script>';



        if($checked == 'suhlasim'){
            try {
                $conn->beginTransaction();
                $conn->exec("UPDATE student SET agree = 1 WHERE email = '" . $userN . "@stuba.sk'");
                $conn->commit();
                echo '<p>Súhlasil si s bodmy</p>';
            }catch(PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

        }
        else{
            try {
                $conn->beginTransaction();
                $conn->exec("UPDATE student SET agree = -1 WHERE email = '" . $userN . "@stuba.sk'");
                $conn->commit();
                echo '<p>Nesúhlasil si s bodmy</p>';
            }catch(PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }
        }

    }

    echo '<a class="btn btn-success" href="user.php">Naspäť</a>';
    ?>
</div>
</body>
</html>