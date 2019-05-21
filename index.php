<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title>Prihlasenie</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
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
<div class="wrapper">
    <h2>Prihlasenie</h2>
    <p></p>
    <form action="index.php" method="POST">
        <div class="form-group">
            <label>Login</label>
            <input type="text" name="login" class="form-control">
            <span class="help-block"></span>
        </div>
 
        <div class="form-group">
            <label>Heslo</label>
            <input type="password" name="password" id="password" class="form-control">
            <span class="help-block"></span>
        </div>
 
        <div class="form-group">
            <input type="submit" name="AIS" class="btn btn-primary" value="Prihlasit">
        </div>
    </form>
</div>
</body>
</html>
 
 
<?php
 
if (isset($_POST['AIS'])) {
 
    if(isset($_POST['login']) && isset($_POST['password'])){
        $adServer = "ldap.stuba.sk";
 
        $dn  = 'ou=People, DC=stuba, DC=sk';
        $username = $_POST['login'];
        $password = $_POST['password'];
        $ldaprdn  = "uid=$username, $dn";
 
        $ldapconn = ldap_connect("ldap.stuba.sk", 389);
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
 
        $bind = ldap_bind($ldapconn, $ldaprdn, $password);
        if ($bind){
 
            $results=ldap_search($ldapconn,$dn,"uid=$username",array("givenname","sn","mail","cn","uisid","uid"));
            $info=ldap_get_entries($ldapconn,$results);
            $i=0;
            $aisUdaje = array("Meno"=>$info[$i]['givenname'][0],
                "Priezvisko"=>$info[$i]['sn'][0],
                "Používateľské meno"=>$info[$i]['uid'][0],
                "Id"=>$info[$i]['uisid'][0],
                "Email"=>$info[$i]['mail'][0]);
 
            $meno=$info[$i]['givenname'][0];
            $priezvisko=$info[$i]['sn'][0];
            $email=$info[$i]['mail'][0];
            $login=$info[$i]['uid'][0];
 
            session_start();
            $_SESSION['meno']=$info[$i]['uisid'][0];
            $_SESSION['email']=$info[$i]['mail'][0];

            header("Location: welcomePage.php");
            echo "success";
            
        } else {
            echo "Chyba pripojenia na server!";
        }
    }
 
 
}
 
?>