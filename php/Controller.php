<?php
    //session_start();
    require "GeoCode.php";
    $code = new GeoCode();
    $result = $code->getaddress($_POST['address']);
    $_SESSION['lat'] = $result['lat'];
    $_SESSION['long'] = $result['long'];
    header('Location: http://localhost/UserChase');
?>