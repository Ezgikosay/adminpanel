<?php
    session_start();
    setcookie("giris","",time()-1);
    $_SESSION["kontrol"] = "";
    $_SESSION["kullanici"] = "";
    session_destroy();
    echo "<script> window.top.location='index.php'; </script>";
    die();
?>