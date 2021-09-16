<?php
    session_start();
    require_once("baglan.php");

    if (isset($_POST["giris"])) {
        $kullanici = $_POST["kullanici"];
        $parola = sha1(md5($_POST["parola"]));

        $sorgu = mysqli_query($baglan,"select * from yonetici where (kullanici='$kullanici' && parola='$parola')");
        $satir = mysqli_fetch_object($sorgu);

        if ($satir->kullanici=="") {
            echo "<script> alert('HATALI KULLANICI BİLGİSİ!'); window.top.location='giris.php'; </script>";
            die();
        }

        setcookie("giris","var",time()+60*60);
        $_SESSION["kontrol"] = $satir->id;
        $_SESSION["kullanici"] = $satir->kullanici;
        echo "<script> window.top.location='anasayfa.php'; </script>";
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş</title>
</head>
<body style="text-align:center">
    <form method="post" action="giris.php">
        <b>Kullanıcı Adı:</b><br>
        <input type="text" name="kullanici"><br><br>
        <b>Parola:</b><br>
        <input type="password" name="parola"><br><br>
        <input type="submit" name="giris" value="Giriş">
    </form>
</body>
</html>