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
        $habersay = 0;
        $sorgu = mysqli_query($baglan,"select id from haber where (kat_id='$kayitid')");
        $habersay = mysqli_num_rows($sorgu);

        if ($habersay>0) {
            echo "<script> alert('Kategori Silinemedi!'); window.location.href='kategori.php'; </script>";
            die();
        } else {
            $sorgu = mysqli_query($baglan,"delete from kategori where (id='$kayitid')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('$sonuc Kategori Silindi!'); window.location.href='kategori.php'; </script>";
                die();
            } else {
                echo "<script> alert('Kategori Silinemedi!'); window.location.href='kategori.php'; </script>";
                die();
            }
        }
    }

    if ($islem=="duzenle") {
        $sorgu = mysqli_query($baglan,"select * from kategori where (id='$kayitid')");
        $satir = mysqli_fetch_object($sorgu);
    }

    if ($islem == "kaydet") {
        $duzenleid = $_POST["duzenleid"];
        $baslik = $_POST["baslik"];
        $order_id = $_POST["order_id"];
        $durum = $_POST["durum"];

        if ($duzenleid<>"") {
            $sorgu = mysqli_query($baglan,"update kategori set baslik='$baslik',order_id='$order_id',durum='$durum' where (id='$duzenleid')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Kategori Düzenlendi!'); window.location.href='kategori.php'; </script>";
                die();
            } else{
                echo "<script> alert('Kategori Düzenlenemedi!'); window.location.href='kategori.php'; </script>";
                die();
            }
        } else {
            $sorgu = mysqli_query($baglan,"insert into kategori values (NULL,'$baslik','$order_id','$durum')");
            $sonuc = mysqli_affected_rows($baglan);
            if ($sonuc>0) {
                echo "<script> alert('Kategori Eklendi!'); window.location.href='kategori.php'; </script>";
                die();
            } else{
                echo "<script> alert('Kategori Eklenemedi!'); window.location.href='kategori.php'; </script>";
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
    <title>Kategoriler</title>
</head>
<body style="text-align:center">
    <p><a href="anasayfa.php">Ana Sayfa</a> | <a href="haber.php">Haberler</a> | <a href="kategori.php">Kategoriler</a> | <a href="yonetici.php">Yöneticiler</a> | <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a></p>

    <hr><br><br>

    <form action="ktrislem.php?islem=kaydet" method="post">

        <b>Kategori Başlığı:</b><br>
        <input type="text" name="baslik" value="<?php echo @$satir->baslik; ?>"><br><br>

        <b>Görünüm Sırası:</b><br>
        <input type="text" name="order_id" value="<?php echo @$satir->order_id; ?>"><br><br>

        <b>Kategori Durumu:</b><br>
        <select name="durum">
            <option value="">Seçiniz...</option>
            <option value="aktif" <?php if (@$satir->durum=="aktif") {echo "selected";} ?>>Aktif</option>
            <option value="pasif"<?php if (@$satir->durum=="pasif") {echo "selected";} ?>>Pasif</option>
        </select><br><br>

        <input type="hidden" name="duzenleid" value="<?php echo @$satir->id; ?>">
        <input type="submit" value="Kaydet">
    </form>
</body>
</html>