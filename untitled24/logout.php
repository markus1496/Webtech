<?php


session_start();

session_unset();

session_destroy();

unset($_SESSION['meno']);

header('Location: index.php');

?>