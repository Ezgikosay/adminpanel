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

    <hr>

    <p align="right"><button onclick="window.top.location='ktrislem.php?islem=yeni';">Yeni Kategori Ekle</button></p>

    <table border="1" width="100%">
        <tr>
            <td width="5%"><b>ID</b></td>
            <td width="35%"><b>Kategori</b></td>
            <td width="20%"><b>Haber</b></td>
            <td width="20%"><b>Durum</b></td>
            <td width="20%"><b>İşlem</b></td>
        </tr>
        <?php
            $sorgu = mysqli_query($baglan,"select * from kategori order by order_id asc");
            $kategorisay = mysqli_num_rows($sorgu);
            while ($satir = mysqli_fetch_object($sorgu)) {
                $sorgux = mysqli_query($baglan,"select id from haber where (kat_id='$satir->id')");
                $habersay = mysqli_num_rows($sorgux);
                echo "<tr>
                <td>$satir->id</td>
                <td>$satir->baslik</td>
                <td>$habersay ad</td>
                <td>".ucwords($satir->durum)."</td>
                <td><a href='ktrislem.php?islem=duzenle&id=$satir->id'>Düzenle</a> | <a href='ktrislem.php?islem=sil&id=$satir->id' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a></td>
                </tr>";
            }
        ?>
    </table>
    <p>Toplam <?php echo $kategorisay; ?> Kategori Listeleniyor...</p>
</body>
</html>