<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
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

    <style type="text/css">
        body {
            font: 14px sans-serif;
        }
        .wrapper{
            width: 350px; padding: 20px;
        }
    </style>
</head>
<body>
<div>
    <div>
        <h1>Vitajte </h1>
    </div>
    <div class="head">
        <a href="logout.php" class="btn btn-primary" role="button">Odhlásiť sa</a>
    </div>
</div>
<div class="form-group3">
    <form action="" method="post" enctype="multipart/form-data">

        <h2>Načítaj súbor</h2>
        <div>
            <label>Vyber súbor :</label>
            <label class="btn btn-primary">Browse<input type="file" name="file" id="file" hidden required></label>
        </div>
        <div>
            <br>
            <label>Zadaj oddeľovač:</label>
            <input type="text" name="oddelovac" class="form-control" required>
            <br>
        </div>
        <div>
            <label>Potvrď :</label>
            <input type="submit" class="btn btn-primary" name="submit" />
        </div>

    </form>
</div>

</body>
</html>

<?php
include_once 'conf.php';
//echo '<pre>';
//print_r($_POST);
if (isset($_POST['submit'])) {
    /*   echo '<pre>';
       print_r($_FILES);*/
    $storagename = $_FILES["file"]["name"];


    echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";

    if (($h = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {

       // $arr = array();
        while (($data = fgetcsv($h, 0, ';')) !== FALSE) {
            if (count($data) == 4) {
                $arr[] = array($data[0], $data[1], $data[2], $data[3], random_password());
            } else {
                $arr[] = $data;
            }
        }
    }
    fclose($h);

    if (file_exists("upload/" . $_FILES["file"]["name"])) {
        echo $_FILES["file"]["name"] . " already exists. ";
    } else {
        //Store file in directory "upload" with the name of "uploaded_file.txt"
        $storagename = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
        echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
    }
    /*
     * tato cast sa spusti len ak ma tabulka 4 stlpce to je ta prva cast zadania,
     * ak ma viacej tak ide do elsu
     */
    //print_r($arr);
    //print_r($arr);
    if (isset($arr[0]) && count($arr[0]) == 5) {
        $arr[0][4] = 'pass';
        $name_new_file = strstr($_FILES['file']['name'], '.', true) . '.csv';
        if (!file_exists($name_new_file)) {
            $handle = fopen($name_new_file, 'w');
            foreach ($arr as $fields) {
                fputcsv($handle, $fields, ';');
            }

            echo '<br>';
            echo '<div class="form-group3"><a href="upload/'  . $name_new_file . '">Subor na stiahnutie ' . $name_new_file . '</a></div>';
            fclose($handle);
        } else {
            echo '<br>';
            echo '<div class="form-group3"><a href="upload/' . $name_new_file . '">Subor na stiahnutie ' . $name_new_file . '</a></div>';
        }
    } elseif(count($arr[0]) > 4) {

        $indexes = get_header_index($arr[0]);
        unset($arr[0]);
        /* print_r($arr);*/
       // print_r($indexes);
        foreach ($arr as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($indexes as $items => $item) {
                    if ($item == $key1) {
                        $data[$key][$items] = $value[$item];
                    }
                }
            }
        }
        //print_r($data);
        $result = db_conn();
        render_form($result);
        //print_r($result);


    }

} elseif (isset($_POST['sablona_submit'])) {

    $result = db_conn($_POST['sablona']);
   print_r($result);
}
function render_form($result)
{
    echo '<br><div  class="form-group3"><form action="" method="post">
    <h2>Vyber si šablónu</h2>';

    foreach ($result as $key => $value) {
        echo '<input type="radio" name="sablona" value="' . $value['id'] . '"/><textarea rows="12" cols="40"><div>' . $value['text'] . '<br>' . $value['ip_adresa'] . '<br>' . $value['login'] . '<br>' . $value['heslo'] . '<br>' . $value['dostupne'] . '<br>' . $value['sender'] .
            '</div><br></textarea>';
    }
    echo '<input type="submit" class="btn btn-primary" name="sablona_submit" />
 
</form></div>';


}

function mail_sender($data, $db_result)
{
    foreach ($data as $key => $value) {

        $to = $value['email'];
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        //  mail($to, $subject, $message, $headers);
    }
}


function get_header_index($data)
{
    $arr = array();
    foreach ($data as $key => $value) {
        $value=strtolower($value);
        if ($value == 'email') {
            $arr['email'] = $key;
        } elseif ($value == 'verejnaIP') {
            $arr['verejnaIP'] = $key;
        } elseif ($value == 'login') {
            $arr['login'] = $key;
        } elseif ($value == 'heslo') {
            $arr['heslo'] = $key;
        } elseif ($value == 'http') {
            $arr['http'] = $key;
        } elseif ($value == 'sender') {
            $arr['sender'] = $key;
        }
    }
    return $arr;
}

function db_conn($id = '')
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        return mysqli_connect_error();
    }
    $conn->set_charset("utf8");

    $where =(!empty($id)) ?     " where `id`= " . $id: '';

    $sql = "SELECT * FROM `mail_sablona`" . $where;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $arr[] = $row;
    }
    return $arr;

}


function random_password()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 15; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}


?>


