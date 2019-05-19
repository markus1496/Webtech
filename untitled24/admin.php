<?php
session_start();
include 'config.php';
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name );
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
    <html lang="sk">
<html>
<head>
    <title>admin</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href=https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div>
    <div>
        <div>
        <h1>Vitajte </h1>
        </div>
        <div class="head">
        <a href="logout.php" class="btn btn-primary" role="button">Odhlásiť sa</a>
        </div>
    </div>
    <div class="form-group">
    <h2>Nahravanie výsledkov</h2>
     <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
         <div>
            <label>Vyber súbor:</label>
            <label class="btn btn-primary ">Browse<input type="file" name="file" id="file" hidden required></label>
        </div>
            <div>
                <br>
                <label>Vyber rok:</label>
                <select name="years" class="form-control" required>
                    <option value="2015/2016">2015/2016</option>
                    <option value="2016/2017">2016/2017</option>
                    <option value="2017/2018">2017/2018</option>
                    <option value="2018/2019">2018/2019</option>
                </select>
            </div>
            <br>
            <div>
                <label>Zadaj predmet:</label>
                 <input type="text" name="predmety" class="form-control" required>
            </div>
            <br>
            <div>
                <input type="submit" class="btn btn-primary" name="submit" />
            </div>
        </form>
    </div>
    <div class="medzera">
        <label></label>
    </div>
    <div class = "form-group">
    <h2>Výpis výsledkov</h2>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <br>
            <div>
            <label>Vyber rok:</label>
            <select name="years" class="form-control" required>
                <option value="2015/2016">2015/2016</option>
                <option value="2016/2017">2016/2017</option>
                <option value="2017/2018">2017/2018</option>
                <option value="2018/2019">2018/2019</option>
            </select>
        </div>
            <div>
                <br>
                <label>Vyber predmet:</label>
            <?php

            $sql2 = "SELECT nazov FROM predmet";

            $result2 = $conn->query($sql2);

            echo "<select name='predmety' class='form-control' required>";
            if ($result2->num_rows > 0) {
                while($row = $result2->fetch_assoc()) {
                    echo "<option>".$row["nazov"]."</option>";
                }
            } else {
                echo "0 results";
            }
            echo "</select>";
            ?>
            </div>
            <br>
            <div>
                <input type="submit" name="zobraz" class="btn btn-primary"/>
            </div>
        </form>
    </div>
    <div class="medzera">
    <label></label>
    </div>
        <div class = "form-group">
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <h2>Vymazanie predmetu</h2>
            <br>
            <div>
                <label>Vyber predmet:</label>
            <?php

            $sql2 = "SELECT nazov FROM predmet";

            $result2 = $conn->query($sql2);

            echo "<select name='predmety' class='form-control' required>";
            if ($result2->num_rows > 0) {
                while($row = $result2->fetch_assoc()) {
                    echo "<option>".$row["nazov"]."</option>";
                }
            } else {
                echo "0 results";
            }
            echo "</select>";
            ?>
        </div>
        <br>
        <div>
            <input type="submit" name="delete" class="btn btn-primary" />
        </div>
    </form>
    </div>
    <script>

        $(document).ready(function () {
            $('#tabulka').DataTable({
                "bSort" : false,
                "searching":false,
                "info":false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ]
            });
        });
    </script>



</body>

</html>

<?php
if ( isset($_POST["submit"]) ) {

    $year = htmlspecialchars($_POST["years"]);
    $predmet = htmlspecialchars($_POST["predmety"]);
    $id = 0;

    $sql3 = "SELECT id_predmet FROM predmet WHERE nazov ='$predmet'";
    $sql12 = "INSERT INTO predmet(nazov) VALUES('$predmet') ";
    $result = $conn->query($sql3);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id_predmet"];
        }
    } else {
        if (mysqli_query($conn, $sql12)) {
            echo "Pridal predmet";
            $result = $conn->query($sql3);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id_predmet"];
                }

            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }

        }
    }
    if (isset($_FILES["file"])) {

        //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        } else {

            //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {
                //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "file.txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
                echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
            }
        }
    } else {
        echo "No file selected <br />";
    }

// The nested array to hold all the arrays
    $result = [];

    if (isset($storagename) && $file = fopen("upload/" . $storagename, r)) {

        echo "File opened.<br />";

        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            // Each individual array is being pushed into the nested array
            $result[] = $data;

        }
        fclose($file);
    }
    $i = 0;
    $info = [];
    foreach ($result as $index => $item) {
        for ($j = 0; $j < count($item); $j++) {
            if ($i == 0) {
                $info = $item;
                $i = 1;
            } else {
                if ($j == 0) {
                    $sql4 = "SELECT * FROM uzivatel WHERE id_uzivatel ='$item[0]'";
                    $result = $conn->query($sql4);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                        }
                    } else {
                        $sql5 = "INSERT INTO  uzivatel(id_uzivatel,Meno)
                    VALUES ('$item[0]','$item[1]')";
                        if (mysqli_query($conn, $sql5)) {
                            echo "Pridal uzivatela";
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                }
                if ($index > 0 && $j > 1 && strcmp($info[$j], 'Spolu') != 0 && strcmp($info[$j], 'Znamka') != 0) {

                    $sql1 = "INSERT INTO  zaznam(id_uzivatel,id_predmet,nazov,hodnota,obdobie)
                    VALUES ('$item[0]',$id,' $info[$j] ',' $item[$j]','$year')";
                    if (mysqli_query($conn, $sql1)) {
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }

                }
                if ($index > 0 && $j > 1) {
                    if (strcmp($info[$j], 'Spolu') == 0) {
                        $cislo = $j + 1;
                        $sql6 = "INSERT INTO  vysledky(id_uzivatel,id_predmet,znamka,spolu,obdobie)
                    VALUES ('$item[0]',$id,' $item[$cislo] ',' $item[$j]','$year')";
                        if (mysqli_query($conn, $sql6)) {
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }
                }
            }
        }
    }
    header("Refresh:0");

}

    if (isset($_POST["zobraz"])) {

        $year = htmlspecialchars($_POST["years"]);
        $predmet = htmlspecialchars($_POST["predmety"]);

        $id = 0;

        $sql3 = "SELECT id_predmet FROM predmet WHERE nazov ='$predmet'";
        $result = $conn->query($sql3);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id_predmet"];
            }
        } else {
            echo "0 results";
        }


        $sql3 = "SELECT nazov,hodnota,id_uzivatel,obdobie FROM zaznam WHERE obdobie = '$year' AND id_predmet = '$id'";
        $result = $conn->query($sql3);
        $temp = 0;

        echo "<table class = 'table table-bordered' id='tabulka'>";
        echo "<thead><tr>";
        echo "<th>AIS ID</th>";
        echo "<th>Meno</th>";
        echo "<th>Spolu</th>";
        echo "<th>Známka</th>";
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                if($temp == 0){
                    $uzivatel = $row["id_uzivatel"];
                    $temp = 1;
                }
                if($uzivatel == $row["id_uzivatel"]) {
                    echo "<th>" . $row['nazov'] . "</th>";
                }
            }
        }else{
           echo"niesu vysledky" ;
        }

        echo "</tr></thead>";

        $temp = 0;
        $temp2 = 0;
        $sql7 = "SELECT z.nazov,z.hodnota,z.id_uzivatel,u.Meno,v.znamka,v.spolu,z.id_zaznam FROM zaznam z JOIN uzivatel u ON u.id_uzivatel = z.id_uzivatel JOIN vysledky v ON z.id_uzivatel = v.id_uzivatel WHERE z.obdobie = '$year' AND z.id_predmet = '$id' AND v.id_predmet = '$id' AND v.obdobie = '$year' GROUP BY z.nazov,z.hodnota,z.id_uzivatel,u.Meno,v.znamka,v.spolu,z.id_zaznam ORDER BY z.id_zaznam";
        $result = $conn->query($sql7);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if($uzivatel != $row["id_uzivatel"] && $temp2 ==1) {
                    $temp =0;
                }
                if($temp == 0){
                    if($temp2 ==1){
                        echo"</tr>";
                    }
                    $uzivatel = $row["id_uzivatel"];
                    $temp = 1;
                    echo"<tr><td>".$row['id_uzivatel']."</td><td>".$row['Meno']."</td><td>".$row['spolu']."</td><td>".$row['znamka']."</td>";
                    
                }
                if($uzivatel == $row["id_uzivatel"]) {
                    echo "<td>" . $row['hodnota'] . "</td>";
                    $temp2 =1;
                }

            }
        }else{
            echo"niesu vysledky" ;
        }

        echo "</tr></table>";

}
if (isset($_POST["delete"])) {
    $predmet = htmlspecialchars($_POST["predmety"]);
    $id = 0;

    $sql8 = "SELECT id_predmet FROM predmet WHERE nazov = '$predmet'";
    $result = $conn->query($sql8);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id_predmet"];
        }
    } else {
        echo "0 results";
    }
    echo "$id";
    $sql9 = "DELETE FROM zaznam WHERE id_predmet ='$id'";
    $sql10 = "DELETE FROM predmet WHERE id_predmet='$id'";
    $sql11 = "DELETE FROM vysledky WHERE id_predmet='$id'";

    if (mysqli_query($conn, $sql9)) {
        if (mysqli_query($conn, $sql10)) {
            if (mysqli_query($conn, $sql11)) {
            echo "delete success";
        } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    header("Refresh:0");
}
?>