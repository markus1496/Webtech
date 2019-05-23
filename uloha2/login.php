<?php

session_start();
include "connect.php";

if(isset($_POST['username']) && isset($_POST['password'])) {

    $adServer = "ldap.stuba.sk";

    $dn = 'ou=People, DC=stuba, DC=sk';
    $userN = $_POST['username'];
    $passW = $_POST['password'];
    $ldaprdn = "uid=$userN, $dn";

    $_SESSION['user'] = $userN;

    //echo $userN;
    //echo $passW;
    $ldapconn = ldap_connect($adServer);

    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

    if (ldap_bind($ldapconn, $ldaprdn, $passW)) {
        $results = ldap_search($ldapconn, $dn, "uid=$userN", array("givenname", "sn", "mail", "cn", "uisid", "uid"));
        $info = ldap_get_entries($ldapconn, $results);

        $user = array(
            "meno" => $info[0]['givenname'][0],
            "priezvisko" => $info[0]['sn'][0],
            "username" => $info[0]['uid'][0],
            "uid" => $info[0]['uisid'][0],
            "email" => $info[0]['mail'][0],
            "type" => 'ldap'
        );

        try {
            $sql = "SELECT id FROM admin WHERE id='" . $userN . "@stuba.sk'";
            //$result = $conn->query($sql)->fetch()[0];
            $result = $conn->query($sql)->fetch();
            if ($result != false) {
              header('Location: admin/');
            } else {
              header('Location: user.php');
            }
            var_dump($result);

        } catch (PDOException $e) {
            echo $e;
        }
        
        
//la
      //  echo "<a href='profile.php?username=".$user['username']."'>table</a>";
        //$conn->close();

       // header("Location: profile.php?username=".$user["username"]);
    }else{
        header('Location: index.php');
    }
} else{
    header('Location: index.php');
}

?>