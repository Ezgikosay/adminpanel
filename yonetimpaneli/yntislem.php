<?php
    session_start();
    require_once("baglan.php");
    if ($_COOKIE["giris"]<>"var" || intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
        @header("Location:cikis.php");
        die();
    }
    $sorgu = mysqli_query($baglan,"select * from yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
    if (mysqli_num_rows($sorgu)<=0) {
        @header("Location:cikis.php");
        die();
    }

    $islem = @$_GET["islem"];
    $kayitid = @$_GET["id"];

    if ($islem=="sil") {
        $sorgu = mysqli_query($baglan,"delete from yonetici where (id='$kayitid')");
        $sonuc = mysqli_affected_rows($baglan);
        if ($sonuc>0) {
            echo "<script> alert('$sonuc Yönetici Silindi!'); window.location.href='yonetici.php'; </script>";
            die();
        } else {
            echo "<script> alert('Yönetici Silinemedi!'); window.location.href='yonetici.php'; </script>";
            die();
        }
    }

    if ($islem=="duzenle") {
        $sorgu = mysqli_query($baglan,"select * from yonetici where (id='$kayitid')");
        $satir = mysqli_fetch_object($sorgu);
    }

    if ($islem == "kaydet") {
        $duzenleid = $_POST["duzenleid"];
        $kullanici = $_POST["kullanici"];
        $yetki = $_POST["yetki"];
        $parola1 = $_POST["parola1"];
        $parola2 = $_POST["parola2"];
        $parola = sha1(md5($_POST["parola1"]));

        if ($duzenleid<>"") {
            if ($parola1 <> $parola2) {
                echo "<script> alert('Girdiğiniz Parolalar Eşleşmiyor!'); window.location.href='yntislem.php?islem=duzenle&id=$duzenleid'; </script>";
                die();
            }

            $sorgu = mysqli_query($baglan,"update yonetici set kullanici='$kullanici',parola='$parola',yetki='$yetki' where (id='$duzenleid')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Yönetici Düzenlendi!'); window.location.href='yonetici.php'; </script>";
                die();
            } else{
                echo "<script> alert('Yönetici Düzenlenemedi!'); window.location.href='yonetici.php'; </script>";
                die();
            }
        } else {
            if ($parola1 <> $parola2) {
                echo "<script> alert('Girdiğiniz Parolalar Eşleşmiyor!'); window.location.href='yntislem.php?islem=yeni'; </script>";
                die();
            }

            $sorgu = mysqli_query($baglan,"insert into yonetici values (NULL,'$kullanici','$parola','$yetki')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Yönetici Eklendi!'); window.location.href='yonetici.php'; </script>";
                die();
            } else{
                echo "<script> alert('Yönetici Eklenemedi!'); window.location.href='yonetici.php'; </script>";
                die();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yöneticiler</title>
</head>
<body style="text-align:center">
    <p><a href="anasayfa.php">Ana Sayfa</a> | <a href="haber.php">Haberler</a> | <a href="kategori.php">Kategoriler</a> | <a href="yonetici.php">Yöneticiler</a> | <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a></p>

    <hr><br><br>

    <form action="yntislem.php?islem=kaydet" method="post">

        <b>Kullanıcı Adı:</b><br>
        <input type="text" name="kullanici" value="<?php echo @$satir->kullanici; ?>"><br><br>

        <b>Giriş Parolası:</b><br>
        <input type="password" name="parola1" value=""><br><br>

        <b>Giriş Parolası (Tekrar):</b><br>
        <input type="password" name="parola2" value=""><br><br>

        <b>Kullanıcı Yetkisi:</b><br>
        <select name="yetki">
            <option value="">Seçiniz...</option>
            <option value="admin" <?php if (@$satir->yetki=="admin") {echo "selected";} ?>>Admin</option>
            <option value="editor"<?php if (@$satir->yetki=="editor") {echo "selected";} ?>>Editör</option>
        </select><br><br>

        <input type="hidden" name="duzenleid" value="<?php echo @$satir->id; ?>">
        <input type="submit" value="Kaydet">
    </form>
</body>
</html>