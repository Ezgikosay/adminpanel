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
    <title>Yöneticiler</title>
</head>
<body style="text-align:center">
    <p><a href="anasayfa.php">Ana Sayfa</a> | <a href="haber.php">Haberler</a> | <a href="kategori.php">Kategoriler</a> | <a href="yonetici.php">Yöneticiler</a> | <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a></p>

    <hr>

    <p align="right"><button onclick="window.top.location='yntislem.php?islem=yeni';">Yeni Yönetici Ekle</button></p>

    <table border="1" width="100%">
        <tr>
            <td width="5%"><b>ID</b></td>
            <td width="50%"><b>Kullanıcı Adı</b></td>
            <td width="25%"><b>Yetki</b></td>
            <td width="20%"><b>İşlem</b></td>
        </tr>
        <?php
            $sorgu = mysqli_query($baglan,"select * from yonetici order by id asc");
            $yoneticisay = mysqli_num_rows($sorgu);
            while ($satir = mysqli_fetch_object($sorgu)) {
                echo "<tr>
                <td>$satir->id</td>
                <td>$satir->kullanici</td>
                <td>".ucwords($satir->yetki)."</td>
                <td><a href='yntislem.php?islem=duzenle&id=$satir->id'>Düzenle</a> | <a href='yntislem.php?islem=sil&id=$satir->id' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a></td>
                </tr>";
            }
        ?>
    </table>
    <p>Toplam <?php echo $yoneticisay; ?> Yönetici Listeleniyor...</p>
</body>
</html>