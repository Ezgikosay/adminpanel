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

        $sorgu = mysqli_query($baglan,"select resim from haber where (id='$kayitid')");
        $satir = mysqli_fetch_object($sorgu);
        @unlink($satir->resim);

        $sorgu = mysqli_query($baglan,"delete from haber where (id='$kayitid')");
        $sonuc = mysqli_affected_rows($baglan);
        if ($sonuc>0) {
            echo "<script> alert('$sonuc Haber Silindi!'); window.location.href='haber.php'; </script>";
            die();
        } else {
            echo "<script> alert('Haber Silinemedi!'); window.location.href='haber.php'; </script>";
            die();
        }
    }

    if ($islem=="duzenle") {
        $sorgu = mysqli_query($baglan,"select * from haber where (id='$kayitid')");
        $satir = mysqli_fetch_object($sorgu);
    }

    if ($islem == "kaydet") {
        $duzenleid = $_POST["duzenleid"];
        $kat_id = $_POST["kat_id"];
        $baslik = $_POST["baslik"];
        $icerik = $_POST["icerik"];
        $durum = $_POST["durum"];

        $_FILES["resim"]["name"] = isimlendir($_FILES["resim"]["name"]);

        if ($_FILES["resim"]["name"]=="") {
            $resim = $_POST["eskiresim"];
        } else {

            if (move_uploaded_file($_FILES["resim"]["tmp_name"],$_FILES["resim"]["name"])) {
                $resim = $_FILES["resim"]["name"];
                if ($resim <> $_POST["eskiresim"]) {
                    @unlink($_POST["eskiresim"]);
                }
            } else {
                $resim = $_POST["eskiresim"];
            }
        }

        if ($duzenleid<>"") {
            $sorgu = mysqli_query($baglan,"update haber set kat_id='$kat_id',baslik='$baslik',icerik='$icerik',resim='$resim',durum='$durum' where (id='$duzenleid')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Haber Düzenlendi!'); window.location.href='haber.php'; </script>";
                die();
            } else{
                echo "<script> alert('Haber Düzenlenemedi!'); window.location.href='haber.php'; </script>";
                die();
            }
        } else {
            $sorgu = mysqli_query($baglan,"insert into haber values (NULL,'$kat_id','$baslik','$icerik','$resim','0','$durum')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Haber Eklendi!'); window.location.href='haber.php'; </script>";
                die();
            } else{
                echo "<script> alert('Haber Eklenemedi!'); window.location.href='haber.php'; </script>";
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
    <title>Haberler</title>
</head>
<body style="text-align:center">
    <p><a href="anasayfa.php">Ana Sayfa</a> | <a href="haber.php">Haberler</a> | <a href="kategori.php">Kategoriler</a> | <a href="yonetici.php">Yöneticiler</a> | <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a></p>

    <hr><br><br>

    <form action="hbrislem.php?islem=kaydet" method="post" enctype="multipart/form-data">

        <b>Haber Kategorisi:</b><br>
        <select name="kat_id">
            <option value="">Seçiniz...</option>
            <?php
                 $sorgux = mysqli_query($baglan,"select * from kategori order by order_id asc");
                 while ($satirx = mysqli_fetch_object($sorgux)) {
                     if ($satir->kat_id == $satirx->id) {$secim = "selected";} else {$secim = "";}
                     echo "<option value='$satirx->id' $secim>$satirx->baslik</option>";
                 }
            ?>
        </select><br><br>

        <b>Haber Başlığı:</b><br>
        <input type="text" name="baslik" value="<?php echo @$satir->baslik; ?>"><br><br>

        <b>Haber Resmi:</b><br>
        <input type="file" name="resim"><br><br>

        <b>Haber İçeriği:</b><br>
        <textarea name="icerik" rows="5"><?php echo @$satir->icerik; ?></textarea><br><br>

        <b>Haber Durumu:</b><br>
        <select name="durum">
            <option value="">Seçiniz...</option>
            <option value="aktif" <?php if (@$satir->durum=="aktif") {echo "selected";} ?>>Aktif</option>
            <option value="pasif"<?php if (@$satir->durum=="pasif") {echo "selected";} ?>>Pasif</option>
        </select><br><br>

        <input type="hidden" name="duzenleid" value="<?php echo @$satir->id; ?>">
        <input type="hidden" name="eskiresim" value="<?php echo @$satir->resim; ?>">
        <input type="submit" value="Kaydet">
    </form>
</body>
</html>