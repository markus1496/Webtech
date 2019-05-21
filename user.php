<?php
require 'config.php';
session_start();
?>

<?php
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
$conn->set_charset("utf8");
////////////////////////////////////////////////////////////////Check connection
if (!$conn) {
    die("Connection failed:".mysqli_connect_error());
}
else{
    // echo "Som v databaze!";
}
$aisid = $_SESSION['meno'];
$result = $conn->query("SELECT obdobie FROM zaznam WHERE id_uzivatel = '$aisid' GROUP BY obdobie");


?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Main Page</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .hlavicka{
            width: 100%;
            height: 50px;
            background-color: black;
            font-size: large;
            padding-top: 7px;
        }
    </style>
</head>
<body>
<div class="hlavicka">
    <a href="logout.php" role="button">Odhlasit</a>
</div>
<strong>Vyber si rok studia</strong>
<form method="post" action="user.php">
    <select name="roky" id="roky">
        <?php
        while($rows = $result->fetch_assoc()){
            $rok = $rows['obdobie'];
            echo "<option value='$rok'>$rok</option>";

        }
        ?>
    </select>
    <input type="submit" value="Zvol" name="zvol">
</form>
<script>
    $(document).ready(function () {
        $('#tabulka').DataTable({
            "searching":false,
            "info":false,
            "bSort":false
        });
    });
</script>

<?php



if (isset($_POST["zvol"])) {
    $aisid = $_SESSION['meno'];

    $rok = htmlspecialchars($_POST["roky"]);
    $result5 = $conn->query("SELECT p.id_predmet,p.nazov FROM zaznam z JOIN predmet p ON z.id_predmet = p.id_predmet WHERE obdobie = '$rok' AND id_uzivatel = '$aisid' GROUP BY p.id_predmet,p.nazov");
  

    while ($row = $result5->fetch_assoc()) {

        $id = $row["id_predmet"];
        $predmet = $row["nazov"];

        $result4 = $conn->query($sql2);

        echo "<h2>$predmet</h2>";



        echo "<table class='table table-bordered' id='tabulka'>";
        echo "<tr><th>Nazov</th>";
        $result4 = $conn->query("SELECT * FROM zaznam z JOIN vysledky v ON z.id_predmet = v.id_predmet AND z.id_uzivatel = v.id_uzivatel WHERE z.obdobie = '$rok' AND v.id_uzivatel = '$aisid' AND z.id_predmet = '$id'");
        while ($rows4 = $result4->fetch_assoc()) {
            echo "<td>" . $rows4['nazov'] . "</td>";
        }
        echo "<td>Spolu</td>";
        echo "<td>Znamka</td>";


        $result4 = $conn->query("SELECT * FROM zaznam z JOIN vysledky v ON z.id_predmet = v.id_predmet AND z.id_uzivatel = v.id_uzivatel WHERE z.obdobie = '$rok' AND v.id_uzivatel = '$aisid' AND z.id_predmet = '$id'");
        echo "</tr><tr><th>Hodnota</th>";
        if ($result4->num_rows > 0) {
            while ($rows5 = $result4->fetch_assoc()) {

                echo "<td>" . $rows5['hodnota'] . "</td>";
            }
        }
        else{
            echo "0 results";
        }
        $sql3 ="SELECT spolu,znamka FROM vysledky WHERE id_predmet = '$id' AND id_uzivatel = '$aisid' AND obdobie = '$rok'";

        $result6 = $conn->query($sql3);
        $rows5 = $result6->fetch_assoc();
        echo "<td>" . $rows5['spolu'] . "</td>";
        echo "<td>" . $rows5['znamka'] . "</td>";

        echo "</tr></table>";
    }
}


?>

<div id="container">
</div>
</body>
</html>